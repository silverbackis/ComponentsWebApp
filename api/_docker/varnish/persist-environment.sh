#!/bin/sh
set -e
envsubst '${UPSTREAM},${UPSTREAM_PORT},${PHP_SERVICE},${CORS_ALLOW_ORIGIN}' < /tmp/varnish/conf/default.vcl > /etc/varnish/default.vcl
exec "$@"
