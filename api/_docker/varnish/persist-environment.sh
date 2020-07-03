#!/bin/sh
set -e
mkdir -p /usr/local/etc/varnish
envsubst '${UPSTREAM},${UPSTREAM_PORT},${PHP_SERVICE}' < /tmp/varnish/conf/default.vcl > /etc/varnish/default.vcl
exec "$@"
