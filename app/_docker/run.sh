#!/bin/sh
set -e

if [ "$NODE_ENV" = 'production' ]; then
  yarn run build
  yarn run start
else
  yarn run dev
fi
