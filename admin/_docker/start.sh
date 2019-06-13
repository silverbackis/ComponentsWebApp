#!/bin/sh

if [ "$NODE_ENV" != 'prod' ]; then
  yarn start
else
  yarn serve
fi

exec "$@"
