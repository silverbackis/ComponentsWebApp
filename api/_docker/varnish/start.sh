#!/bin/sh
set -e
mkdir -p /usr/local/etc/varnish
envsubst '\$CORS_ALLOW_ORIGIN' < /tmp/varnish/conf/default.vcl > /usr/local/etc/varnish/default.vcl
exec "$@"
