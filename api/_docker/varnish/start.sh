#!/bin/sh
set -xe

varnishd -V
varnishd -a :80 -f /etc/varnish/default.vcl -s malloc,256m -t 120
varnishlog
