#!/bin/sh

set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi


if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/console' ]; then

  sed -i "s/xdebug\.remote_host\=.*/xdebug\.remote_host\=$XDEBUG_HOST/g" /usr/local/etc/php/mods-available/xdebug.ini

  if [ "$ENABLE_XDEBUG" == "1" ]; then
    ln -sf /usr/local/etc/php/mods-available/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
    docker-php-ext-enable xdebug --ini-name docker-php-ext-xdebug.ini
  else
    if [ -e /usr/local/etc/php/conf.d/xdebug.ini ]; then
        rm -f /usr/local/etc/php/conf.d/xdebug.ini
    fi
    if [ -e /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini ]; then
        rm -f /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
    fi
  fi

  # Add any other folders that your web application needs to create here
  mkdir -p var/cache var/log var/uploads config/jwt

	if [ "$APP_ENV" != 'prod' ]; then
	  if [ -e /usr/local/etc/php/conf.d/php.prod.ini ]; then
        rm -f /usr/local/etc/php/conf.d/php.prod.ini
    fi
    ln -sf /usr/local/etc/php/mods-available/php.dev.ini /usr/local/etc/php/conf.d/php.dev.ini

	  # local filesystem mounts after install in Dockerfile so run again here
	  php -d memory_limit=-1 /usr/bin/composer install --prefer-dist --no-progress --no-suggest --no-interaction || EXIT_CODE=$? && true
    echo ${EXIT_CODE}

		# Uncomment the following line if you are using Symfony Encore
		#yarn run watch
  else
    if [ -e /usr/local/etc/php/conf.d/php.dev.ini ]; then
        rm -f /usr/local/etc/php/conf.d/php.dev.ini
    fi
    ln -sf /usr/local/etc/php/mods-available/php.prod.ini /usr/local/etc/php/conf.d/php.prod.ini
    # php bin/console doctrine:fixtures:load --no-interaction
    composer run-script --no-dev post-install-cmd || EXIT_CODE=$? && true
    echo ${EXIT_CODE}
		# Uncomment the following line if you are using Symfony Encore
		#yarn run build
	fi

	if [ ! -f $JWT_SECRET_KEY ]; then
    openssl genrsa -out $JWT_SECRET_KEY -aes256 -passout pass:$JWT_PASSPHRASE 4096
    openssl rsa -passin pass:$JWT_PASSPHRASE -pubout -in $JWT_SECRET_KEY -out $JWT_PUBLIC_KEY
  fi

	# Permissions hack because setfacl does not work on Mac and Windows
	# Add any other paths that your web application may need to write to
	chown -R www-data var config
fi

exec docker-php-entrypoint "$@"
