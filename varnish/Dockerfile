ARG VARNISH_VERSION=6.0

FROM cooptilleuls/varnish:${VARNISH_VERSION}-alpine AS varnish

ENV VARNISH_PORT=80
ENV NETWORK_PREFIX="cwa_"

COPY conf/default.vcl /usr/local/etc/varnish/default.vcl

COPY run-varnish.sh /usr/local/bin/run-varnish
RUN chmod +x /usr/local/bin/run-varnish

EXPOSE ${VARNISH_PORT}

CMD ["run-varnish"]
