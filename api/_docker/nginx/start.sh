#!/bin/sh
set -e
envsubst '\$CORS_ALLOW_ORIGIN \$UPSTREAM_CONTAINER \$UPSTREAM_PORT' < /srv/api/tmp/default.conf > /etc/nginx/conf.d/default.conf
exec "$@"
