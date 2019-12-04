#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
  PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-production"
	if [ "$APP_ENV" != 'prod' ]; then
		PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-development"
	fi
	ln -sf "$PHP_INI_RECOMMENDED" "$PHP_INI_DIR/php.ini"

	mkdir -p var/cache var/log public/media/cache
	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var || true
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var || true
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX public/media || true
	setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX public/media || true

  if [ "$APP_ENV" != 'prod' ]; then
		jwt_passphrase=$(grep '^JWT_PASSPHRASE=' .env | cut -f 2 -d '=')
		if [ ! -f config/jwt/private.pem ] || ! echo "$jwt_passphrase" | openssl pkey -in config/jwt/private.pem -passin stdin -noout > /dev/null 2>&1; then
			echo "Generating public / private keys for JWT"
			mkdir -p config/jwt
			echo "$jwt_passphrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
			echo "$jwt_passphrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout
			setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt || true
      setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt || true
		fi
	fi

	if [ "$APP_ENV" != 'prod' ]; then
		composer install --prefer-dist --no-progress --no-suggest --no-interaction
	fi

	echo "Waiting for db to be ready..."
	until bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
		sleep 1
	done

  # we want this extension for case insensitive column data types in postgresql
  # it will fail if the user is not a super user, may need to be created manually
	bin/console d:q:s "CREATE EXTENSION IF NOT EXISTS citext" || EXIT_CODE=$? && true
	echo ${EXIT_CODE}

  if [ "$APP_ENV" != 'prod' ]; then
		bin/console doctrine:schema:update --force --no-interaction || EXIT_CODE=$? && true
    echo ${EXIT_CODE}
	fi
fi

exec docker-php-entrypoint "$@"
