FROM silverbackis/nginx:symfony4
ENV CORS_HOST="https?://(serve.b-w.uk|localhost):?[0-9]*"
ENV UPSTREAM_CONTAINER="php"
ENV UPSTREAM_PORT=9000
RUN envsubst '\$CORS_HOST \$UPSTREAM_CONTAINER \$UPSTREAM_PORT' < /srv/api/default.conf > /etc/nginx/conf.d/default.conf
