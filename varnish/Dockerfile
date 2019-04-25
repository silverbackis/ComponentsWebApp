ARG VARNISH_VERSION=6.0

FROM cooptilleuls/varnish:${VARNISH_VERSION}-alpine AS varnish

COPY conf/default.vcl /usr/local/etc/varnish/default.vcl

CMD ["varnishd", "-F", "-f", "/usr/local/etc/varnish/default.vcl", "-p", "http_resp_hdr_len=256000"]
# , "-p", "http_resp_size=98304"
