#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then

  # X-Debug
  sed -i "s/xdebug\.remote_host\=.*/xdebug\.remote_host\=$XDEBUG_HOST/g" /usr/local/etc/php/mods-available/xdebug.ini
  if [ "$APP_ENV" != "prod" ] && [ "$ENABLE_XDEBUG" != "0" ]; then
    LINK_XDEBUG=true
  fi

  if [ "$ENABLE_XDEBUG" == "1" ] || "$LINK_XDEBUG"; then
    ln -sf "$PHP_INI_DIR/mods-available/xdebug.ini" "$PHP_INI_DIR/conf.d/xdebug.ini"
    docker-php-ext-enable xdebug --ini-name docker-php-ext-xdebug.ini
  else
    if [ -e $PHP_INI_DIR/conf.d/xdebug.ini ]; then
        rm -f $PHP_INI_DIR/conf.d/xdebug.ini
    fi
    if [ -e $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini ]; then
        rm -f $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini
    fi
  fi

  # INI Configure
	PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.prod.ini"
	if [ "$APP_ENV" != 'prod' ]; then
		PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.dev.ini"
	fi
	ln -sf "$PHP_INI_RECOMMENDED" "$PHP_INI_DIR/php.ini"

	# Create required directories that may not exist
	mkdir -p var/cache var/log
	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var

  # If not a production build, then install dev dependencies
	if [ "$APP_ENV" != 'prod' ]; then
		composer install --prefer-dist --no-progress --no-suggest --no-interaction
	else
    composer run-script --no-dev post-install-cmd || EXIT_CODE=$? && true
    echo ${EXIT_CODE}
	fi

	# Make console script executable
	chmod +x bin/console

	echo "Waiting for db to be ready..."
	until bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
		sleep 1
	done

  # Not to update database in production - soft fail
	if [ "$APP_ENV" != 'prod' ]; then
		bin/console doctrine:schema:update --force --no-interaction || EXIT_CODE=$? && true
    echo ${EXIT_CODE}
	fi

	# Generate keys for JWT
	if [ ! -f $JWT_SECRET_KEY ]; then
    openssl genrsa -out $JWT_SECRET_KEY -aes256 -passout pass:$JWT_PASSPHRASE 4096
    openssl rsa -passin pass:$JWT_PASSPHRASE -pubout -in $JWT_SECRET_KEY -out $JWT_PUBLIC_KEY
  fi
fi

exec docker-php-entrypoint "$@"
