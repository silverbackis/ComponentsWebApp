#!/bin/sh
set -e

if [ "${NODE_ENV}" = 'production' ]; then
  echo "PRODUCTION MODE: BUILDING"
  yarn run build
fi
