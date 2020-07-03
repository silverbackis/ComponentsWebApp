vcl 4.0;

import std;

backend default {
  .host = "${UPSTREAM}";
  .port = "${UPSTREAM_PORT}";
  # Health check
  #.probe = {
  #  .url = "/";
  #  .timeout = 5s;
  #  .interval = 30s;
  #  .window = 5;
  #  .threshold = 3;
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
  if (req.esi_level > 0) {
    # ESI request should not be included in the profile.
    # Instead you should profile them separately, each one
    # in their dedicated profile.
    # Removing the Blackfire header avoids to trigger the profiling.
    # Not returning let it go trough your usual workflow as a regular
    # ESI request without distinction.
    unset req.http.X-Blackfire-Query;
  }

  set req.http.Surrogate-Capability = "abc=ESI/1.0";

  if (req.restarts > 0) {
    set req.hash_always_miss = true;
  }

  # Remove the "Forwarded" HTTP header if exists (security)
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

  # For health checks
  if (req.method == "GET" && req.url == "/healthz") {
    return (synth(200, "OK"));
  }
}

sub vcl_hit {
  if (obj.ttl >= 0s) {
    # A pure unadulterated hit, deliver it
    return (deliver);
  }

  if (std.healthy(req.backend_hint)) {
    # The backend is healthy
    # Fetch the object from the backend
    return (restart);
  }

  # No fresh object and the backend is not healthy
  if (obj.ttl + obj.grace > 0s) {
    # Deliver graced object
    # Automatically triggers a background fetch
    return (deliver);
  }

  # No valid object to deliver
  # No healthy backend to handle request
  # Return error
  return (synth(503, "API is down"));
}

sub vcl_deliver {
  # Don't send cache tags related headers to the client
  unset resp.http.url;
  # Comment the following line to send the "Cache-Tags" header to the client (e.g. to use CloudFlare cache tags)
  unset resp.http.Cache-Tags;
}

sub vcl_backend_response {
  # Ban lurker friendly header
  set beresp.http.url = bereq.url;

  # Add a grace in case the backend is down
  set beresp.grace = 1h;
}
