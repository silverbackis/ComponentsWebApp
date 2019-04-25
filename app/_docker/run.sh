#!/bin/sh
set -e

if [ "$NODE_ENV" = 'production' ]; then
  yarn run start
else
  yarn install
  yarn run dev
fi
