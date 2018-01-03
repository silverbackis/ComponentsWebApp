FROM alpine:3.6

RUN apk add --no-cache varnish

COPY _docker/varnish/conf /etc/varnish/
COPY _docker/varnish/start.sh /usr/local/bin/docker-app-start

RUN chmod +x /usr/local/bin/docker-app-start

CMD ["docker-app-start"]
