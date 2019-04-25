#!/bin/sh

set -e

cp /usr/local/etc/varnish/default.vcl /usr/local/etc/varnish/custom.vcl

for name in NETWORK_PREFIX
do
    eval value=\$$name
    sed -i "s|\${${name}}|${value}|g" /usr/local/etc/varnish/custom.vcl
done

varnishd -a :${VARNISH_PORT} -F -f /usr/local/etc/varnish/custom.vcl -p http_resp_hdr_len=256000 -s malloc,256m -t 120
