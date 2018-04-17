#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/console' ]; then
  mkdir -p var/cache var/sessions var/logs public/media
	composer install --prefer-dist --no-progress --no-suggest --no-interaction

	if [ "$APP_ENV" != 'prod' ]; then
		bin/console api-component-bundle:fixtures:load
		bin/console c:c
  else
    composer run-script --no-dev post-install-cmd
    bin/console assets:install
		bin/console c:c
    bin/console c:w
	fi

	# Permissions hack because setfacl does not work on Mac and Windows
	chown -R www-data var public/media
fi

exec docker-php-entrypoint "$@"
