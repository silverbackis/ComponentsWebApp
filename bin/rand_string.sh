#!/usr/bin/env sh

openssl rand -base64 32 |md5 |head -c32;echo
