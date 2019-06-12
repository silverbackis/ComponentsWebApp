vcl 4.0;

import std;

backend default {
  .host = "127.0.0.1";
  .port = "8080";
}

sub vcl_recv {
  return(synth(503, "Website under maintenance"));
}

sub vcl_synth {
  set resp.http.Content-Type = "text/html; charset=utf-8";
  synthetic(std.fileread("/var/www/errors/maintenance.html"));
  return (deliver);
}
