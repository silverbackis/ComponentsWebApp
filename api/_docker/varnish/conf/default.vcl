vcl 4.0;

import std;

backend default {
  .host = "api";
  .port = "80";
  # Health check
  #.probe = {
  #  .url = "/";
  #  .timeout = 5s;
  #  .interval = 10s;
  #  .window = 5;
  #  .threshold = 3;
  #}
}

# Hosts allowed to send BAN requests
acl ban {
  "localhost";
  "php";
}

sub vcl_backend_response {
  # Ban lurker friendly header
  set beresp.http.url = bereq.url;

  # Add a grace in case the backend is down
  set beresp.grace = 1h;
}

sub vcl_deliver {
  # Don't send cache tags related headers to the client
  unset resp.http.url;
  # Uncomment the following line to NOT send the "Cache-Tags" header to the client (prevent using CloudFlare cache tags)
  #unset resp.http.Cache-Tags;
  if (obj.hits > 0) {
       set resp.http.X-Cache = "HIT";
  } else {
       set resp.http.X-Cache = "MISS";
  }
}

sub vcl_recv {
  # Remove the "Forwarded" HTTP header if exists (security)
  unset req.http.forwarded;

  # To allow API Platform to ban by cache tags
  if (req.method == "BAN") {
    if (client.ip !~ ban) {
      return(synth(405, "Not allowed"));
    }

    if (req.http.ApiPlatform-Ban-Regex) {
      ban("obj.http.Cache-Tags ~ " + req.http.ApiPlatform-Ban-Regex);

      return(synth(200, "Ban added"));
    }

    return(synth(400, "ApiPlatform-Ban-Regex HTTP header must be set."));
  }

  if (req.http.Cookie) {
    set req.http.Cookie = ";" + req.http.Cookie;
    set req.http.Cookie = regsuball(req.http.Cookie, "; +", ";");
    set req.http.Cookie = regsuball(req.http.Cookie, ";(PHPSESSID)=", "; \1=");
    set req.http.Cookie = regsuball(req.http.Cookie, ";[^ ][^;]*", "");
    set req.http.Cookie = regsuball(req.http.Cookie, "^[; ]+|[; ]+$", "");

    if (req.http.Cookie == "") {
      // If there are no more cookies, remove the header to get page cached.
      unset req.http.Cookie;
    }
  }

  set req.http.Surrogate-Capability = "abc=ESI/1.0";

  if (req.http.X-Forwarded-Proto == "https" ) {
    set req.http.X-Forwarded-Port = "443";
  } else {
    set req.http.X-Forwarded-Port = "80";
  }
}

# From https://github.com/varnish/Varnish-Book/blob/master/vcl/grace.vcl
sub vcl_hit {
  if (obj.ttl >= 0s) {
    # Normal hit
    return (deliver);
  } elsif (std.healthy(req.backend_hint)) {
    # The backend is healthy
    # Fetch the object from the backend
    return (fetch);
  } else {
    # No fresh object and the backend is not healthy
    if (obj.ttl + obj.grace > 0s) {
      # Deliver graced object
      # Automatically triggers a background fetch
      return (deliver);
    } else {
      # No valid object to deliver
      # No healthy backend to handle request
      # Return error
      return (synth(503, "API is down"));
    }
  }
}

sub vcl_backend_response {
    // Check for ESI acknowledgement and remove Surrogate-Control header
    if (beresp.http.Surrogate-Control ~ "ESI/1.0") {
        unset beresp.http.Surrogate-Control;
        set beresp.do_esi = true;
    }
}
