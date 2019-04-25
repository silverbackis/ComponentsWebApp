#!/bin/sh

if [ "$NODE_ENV" != 'prod' ]; then
  yarn start
else
  yarn build
  yarn serve
fi

exec "$@"
