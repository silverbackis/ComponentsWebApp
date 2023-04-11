# Based on: https://github.com/mattiasgeniar/varnish-6.0-configuration-templates/blob/master/default.vcl
vcl 4.0;

import std;
import xkey;

backend default {
  .host = "${UPSTREAM}";
  .port = "${UPSTREAM_PORT}";
  .max_connections        = 300;
  .first_byte_timeout     = 300s;   # How long to wait before we receive a first byte from our backend?
  .connect_timeout        = 5s;     # How long to wait for a backend connection?
  .between_bytes_timeout  = 2s;     # How long to wait between bytes received from our backend?

  # Health check
  #.probe = {
  #  .request =
  #    "HEAD /health-check HTTP/1.1"
  #    "Host: ${UPSTREAM}:${UPSTREAM_PORT}"
  #    "Connection: close"
  #    "User-Agent: Varnish Health Probe";
  #  .timeout = 5s;
  #  .interval = 5s;
  #  .window = 4;
  #  .threshold = 2;
  #}
}

acl profile {
   # Authorized IPs, add your own IPs from which you want to profile.
   # "x.y.z.w";

   # Add the Blackfire.io IPs when using builds:
   # Ref https://blackfire.io/docs/reference-guide/faq#how-should-i-configure-my-firewall-to-let-blackfire-access-my-apps
   "46.51.168.2";
   "54.75.240.245";
}

# Hosts allowed to send BAN requests
acl invalidators {
  "localhost";
  "${PHP_SERVICE}";
  # local Kubernetes network
  "10.0.0.0"/8;
  "172.16.0.0"/12;
  "192.168.0.0"/16;
}

sub vcl_recv {
  # For health checks
  if (req.method == "GET" && req.url == "/healthz") {
    return (synth(200, "OK"));
  }

  if (req.esi_level > 0) {
    # ESI request should not be included in the profile.
    # Instead you should profile them separately, each one
    # in their dedicated profile.
    # Removing the Blackfire header avoids to trigger the profiling.
    # Not returning let it go trough your usual workflow as a regular
    # ESI request without distinction.
    unset req.http.X-Blackfire-Query;
  }

  unset req.http.x-cache;
  set req.http.grace = "none";
  set req.http.Surrogate-Capability = "abc=ESI/1.0";

  if (req.restarts > 0) {
    set req.hash_always_miss = true;
  }

  # Remove the "Forwarded" HTTP header if exists (security)
  # Removing this causes issues for development on same domain
  # Logins fail, OPTIONS requests fails and more... need to find out why...
  unset req.http.forwarded;

  # Remove fields and preload headers used for vulcain
  # https://github.com/dunglas/vulcain/blob/master/docs/cache.md
  unset req.http.fields;
  unset req.http.preload;

  # If it's a Blackfire query and the client is authorized,
  # just pass directly to the application.
  if (req.http.X-Blackfire-Query && client.ip ~ profile) {
    return (pass);
  }

  # To allow API Platform to ban by cache tags
  if (req.method == "BAN") {
    if (client.ip !~ invalidators) {
      return (synth(405, "Not allowed"));
    }

    if (req.http.ApiPlatform-Ban-Regex) {
      ban("obj.http.Cache-Tags ~ " + req.http.ApiPlatform-Ban-Regex);

      return (synth(200, "Ban added"));
    }

    return (synth(400, "ApiPlatform-Ban-Regex HTTP header must be set."));
  }

  # Allow purging (needs xkey mod)
  if (req.method == "PURGE") {
    # purge is the ACL defined at the beginning
    if (!client.ip ~ invalidators) {
      # Not from an allowed IP? Then die with an error.
      return (synth(403, "This IP is not allowed to send PURGE requests."));
    }

    if (req.http.xkey) {
      set req.http.n-gone = xkey.purge(req.http.xkey);
      # or: set req.http.n-gone = xkey.softpurge(req.http.xkey)
      return (synth(200, "Invalidated "+req.http.n-gone+" objects"));
    }

    # If you got this stage (and didn't error out above), purge the cached result
    return (purge);
  }

  # Strip hash, server doesn't need it.
  if (req.url ~ "\#") {
    set req.url = regsub(req.url, "\#.*$", "");
  }

  # Strip a trailing ? if it exists
  if (req.url ~ "\?$") {
    set req.url = regsub(req.url, "\?$", "");
  }

  #if (req.http.Cache-Control ~ "(?i)no-cache") {
    #if (client.ip ~ invalidators) {
      # Ignore requests via proxy caches and badly behaved crawlers
      # like msnbot that send no-cache with every request.
      #if (! (req.http.Via || req.http.User-Agent ~ "(?i)bot" || req.http.X-Purge)) {
        #set req.hash_always_miss = true; # Doesn't seems to refresh the object in the cache
        #return(purge); # Couple this with restart in vcl_purge and X-Purge header to avoid loops
      #}
    #}
  #}

  # Large static files are delivered directly to the end-user without
  # waiting for Varnish to fully read the file first.
  # Varnish 4 fully supports Streaming, so set do_stream in vcl_backend_response()
  if (req.url ~ "^[^?]*\.(7z|avi|bz2|flac|flv|gz|mka|mkv|mov|mp3|mp4|mpeg|mpg|ogg|ogm|opus|rar|tar|tgz|tbz|txz|wav|webm|xz|zip)(\?.*)?$") {
    unset req.http.Cookie;
    return (hash);
  }

  # Remove all cookies for static files
  # A valid discussion could be held on this line: do you really need to cache static files that don't cause load? Only if you have memory left.
  # Sure, there's disk I/O, but chances are your OS will already have these files in their buffers (thus memory).
  # Before you blindly enable this, have a read here: https://ma.ttias.be/stop-caching-static-files/
  if (req.url ~ "^[^?]*\.(7z|avi|bmp|bz2|css|csv|doc|docx|eot|flac|flv|gif|gz|ico|jpeg|jpg|js|less|mka|mkv|mov|mp3|mp4|mpeg|mpg|odt|otf|ogg|ogm|opus|pdf|png|ppt|pptx|rar|rtf|svg|svgz|swf|tar|tbz|tgz|ttf|txt|txz|wav|webm|webp|woff|woff2|xls|xlsx|xml|xz|zip)(\?.*)?$") {
    unset req.http.Cookie;
    return (hash);
  }

  # Send Surrogate-Capability headers to announce ESI support to backend
  set req.http.Surrogate-Capability = "key=ESI/1.0";

  if (req.http.Cookie) {
      set req.http.Cookie = ";" + req.http.Cookie;
      set req.http.Cookie = regsuball(req.http.Cookie, "; +", ";");
      set req.http.Cookie = regsuball(req.http.Cookie, ";(api_component)=", "; \1=");
      set req.http.Cookie = regsuball(req.http.Cookie, ";[^ ][^;]*", "");
      set req.http.Cookie = regsuball(req.http.Cookie, "^[; ]+|[; ]+$", "");

      # Ensure no cookies will always match the same cache - SSR loads will not have the header at all
      if (req.http.Cookie == "") {
        unset req.http.Cookie;
      }
  }

  # See builtin.vcl https://github.com/varnishcache/varnish-cache/blob/master/bin/varnishd/builtin.vcl
	call builtin_vcl_req_host;
	call builtin_vcl_req_method;

  # we skip the call vcl_req_cookie to cache with our cookie
  return(hash);
}

# See builtin.vcl https://github.com/varnishcache/varnish-cache/blob/master/bin/varnishd/builtin.vcl
sub builtin_vcl_req_host {
	if (req.http.host ~ "[[:upper:]]") {
		set req.http.host = req.http.host.lower();
	}
	if (!req.http.host &&
	    req.esi_level == 0 &&
	    req.proto == "HTTP/1.1") {
		# In HTTP/1.1, Host is required.
		return (synth(400));
	}
}

# See builtin.vcl https://github.com/varnishcache/varnish-cache/blob/master/bin/varnishd/builtin.vcl
sub builtin_vcl_req_method {
	if (req.method == "PRI") {
		# This will never happen in properly formed traffic.
		return (synth(405));
	}
	if (req.method != "GET" &&
	    req.method != "HEAD" &&
	    req.method != "PUT" &&
	    req.method != "POST" &&
	    req.method != "TRACE" &&
	    req.method != "OPTIONS" &&
	    req.method != "DELETE" &&
	    req.method != "PATCH") {
		# Non-RFC2616 or CONNECT which is weird.
		return (pipe);
	}
	if (req.method != "GET" && req.method != "HEAD") {
		# We only deal with GET and HEAD by default.
		return (pass);
	}
}

sub vcl_hit {
  set req.http.x-cache = "hit";

  if (obj.ttl >= 0s) {
    # A pure unadulterated hit, deliver it
    return (deliver);
  }

  # https://info.varnish-software.com/blog/grace-varnish-4-stale-while-revalidate-semantics-varnish
  if (std.healthy(req.backend_hint)) {
    # Backend is healthy. Limit age to 10s.
    if (obj.ttl + 10s > 0s) {
        set req.http.grace = "normal(limited)";
        return (deliver);
    } else {
        # Fetch the object from the backend
        return (restart);
    }
  }

  # No fresh object and the backend is not healthy
  if (obj.ttl + obj.grace > 0s) {
    # Deliver graced object
    # Automatically triggers a background fetch
    set req.http.grace = "full";
    return (deliver);
  }

  # No valid object to deliver
  # No healthy backend to handle request
  # Return error
  return (synth(503, "API is down"));
}

sub vcl_miss {
	set req.http.x-cache = "miss";
}

sub vcl_pass {
	set req.http.x-cache = "pass";
}

sub vcl_pipe {
	set req.http.x-cache = "pipe uncacheable";
}

sub vcl_synth {
  if (resp.status == 720) {
    # We use this special error status 720 to force redirects with 301 (permanent) redirects
    # To use this, call the following from anywhere in vcl_recv: return (synth(720, "http://host/new.html"));
    set resp.http.Location = resp.reason;
    set resp.status = 301;
    return (deliver);
  } elseif (resp.status == 721) {
    # And we use error status 721 to force redirects with a 302 (temporary) redirect
    # To use this, call the following from anywhere in vcl_recv: return (synth(720, "http://host/new.html"));
    set resp.http.Location = resp.reason;
    set resp.status = 302;
    return (deliver);
  }

  call cors;
  return (deliver);
}

sub vcl_deliver {
  # Called before a cached object is delivered to the client.
  if (obj.uncacheable) {
		set req.http.x-cache = req.http.x-cache + " uncacheable" ;
	} else {
		set req.http.x-cache = req.http.x-cache + " cached" ;
	}
	set resp.http.x-cache = req.http.x-cache;
  set resp.http.grace = req.http.grace;
  # Please note that obj.hits behaviour changed in 4.0, now it counts per objecthead, not per object
  # and obj.hits may not be reset in some cases where bans are in use. See bug 1492 for details.
  # So take hits with a grain of salt
  set resp.http.X-Cache-Hits = obj.hits;

  # Don't send cache tags related headers to the client
  unset resp.http.url;
  # Comment the following line to send the "Cache-Tags" header to the client (e.g. to use CloudFlare cache tags)
  unset resp.http.Cache-Tags;

  # Remove some headers: PHP version
  unset resp.http.X-Powered-By;

  # Remove some headers: Apache version & OS
  unset resp.http.Server;
  # unset resp.http.X-Drupal-Cache;
  unset resp.http.X-Varnish;
  unset resp.http.Via;
  # unset resp.http.Link;
  # unset resp.http.X-Generator;
  unset resp.http.xkey;

  call cors;
  return (deliver);
}

sub vcl_hash {
  hash_data(req.url);

  if (req.http.host) {
    hash_data(req.http.host);
  } else {
    hash_data(server.ip);
  }

  if (req.http.Referer) {
    hash_data(req.http.Referer);
  }

  # hash cookies for requests that have them
  if (req.http.Cookie) {
    hash_data(req.http.Cookie);
  }

  # Cache the HTTP vs HTTPs separately
  if (req.http.X-Forwarded-Proto) {
    hash_data(req.http.X-Forwarded-Proto);
  }

  # It's also possible to use the following to specify a cookie, as we remove all the rest the above is enough
  # if (req.http.cookie ~ "api_component=") {
  #   set req.http.x-authorization = regsub(req.http.cookie, ".*api_component=([^;]+);.*", "\1");
  #   hash_data(req.http.x-authorization);
  #   unset req.http.x-authorization;
  # }
}

sub vcl_backend_response {
  # Called after the response headers has been successfully retrieved from the backend.

  # Pause ESI request and remove Surrogate-Control header
  if (beresp.http.Surrogate-Control ~ "ESI/1.0") {
    unset beresp.http.Surrogate-Control;
    set beresp.do_esi = true;
  }

  # Enable cache for all static files
  # The same argument as the static caches from above: monitor your cache size, if you get data nuked out of it, consider giving up the static file cache.
  # Before you blindly enable this, have a read here: https://ma.ttias.be/stop-caching-static-files/
  if (bereq.url ~ "^[^?]*\.(7z|avi|bmp|bz2|css|csv|doc|docx|eot|flac|flv|gif|gz|ico|jpeg|jpg|js|less|mka|mkv|mov|mp3|mp4|mpeg|mpg|odt|otf|ogg|ogm|opus|pdf|png|ppt|pptx|rar|rtf|svg|svgz|swf|tar|tbz|tgz|ttf|txt|txz|wav|webm|webp|woff|woff2|xls|xlsx|xml|xz|zip)(\?.*)?$") {
    unset beresp.http.set-cookie;
  }

  # Large static files are delivered directly to the end-user without
  # waiting for Varnish to fully read the file first.
  # Varnish 4 fully supports Streaming, so use streaming here to avoid locking.
  if (bereq.url ~ "^[^?]*\.(7z|avi|bz2|flac|flv|gz|mka|mkv|mov|mp3|mp4|mpeg|mpg|ogg|ogm|opus|rar|tar|tgz|tbz|txz|wav|webm|xz|zip)(\?.*)?$") {
    unset beresp.http.set-cookie;
    set beresp.do_stream = true;  # Check memory usage it'll grow in fetch_chunksize blocks (128k by default) if the backend doesn't send a Content-Length header, so only enable it for big objects
  }

  # Sometimes, a 301 or 302 redirect formed via Apache's mod_rewrite can mess with the HTTP port that is being passed along.
  # This often happens with simple rewrite rules in a scenario where Varnish runs on :80 and Apache on :8080 on the same box.
  # A redirect can then often redirect the end-user to a URL on :8080, where it should be :80.
  # This may need finetuning on your setup.
  #
  # To prevent accidental replace, we only filter the 301/302 redirects for now.
  if (beresp.status == 301 || beresp.status == 302) {
    set beresp.http.Location = regsub(beresp.http.Location, ":[0-9]+", "");
  }

  # Set 2min cache if unset for static files
  if (beresp.ttl <= 0s || beresp.http.Set-Cookie || beresp.http.Vary == "*") {
    set beresp.ttl = 120s; # Important, you shouldn't rely on this, SET YOUR HEADERS in the backend
    set beresp.uncacheable = true;
    return (deliver);
  }

  # Don't cache 50x responses
  if (beresp.status == 500 || beresp.status == 502 || beresp.status == 503 || beresp.status == 504) {
    return (abandon);
  }

  # Allow stale content, in case the backend goes down.
  # make Varnish keep all objects for 1 hour beyond their TTL
  set beresp.grace = 1h;

  return (deliver);
}

sub cors {
  if (req.http.Origin ~ "${CORS_ALLOW_ORIGIN}") {
      set resp.http.Access-Control-Allow-Origin = req.http.Origin;
      set resp.http.Access-Control-Allow-Credentials = true;
  }
}

sub vcl_purge {
  # Only handle actual PURGE HTTP methods, everything else is discarded
  if (req.method == "PURGE") {
    # restart request
    set req.http.X-Purge = "Yes";
    return(restart);
  }
}
