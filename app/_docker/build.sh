#!/bin/sh
set -e

if [ "$NODE_ENV" = 'production' ]; then
  yarn run build
fi
