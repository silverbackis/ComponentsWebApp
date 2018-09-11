FROM silverbackis/php:symfony4

WORKDIR /srv/api

# Prevent Symfony Flex from generating a project ID at build time
ARG SYMFONY_SKIP_REGISTRATION=1
ARG APP_ENV=prod

COPY composer.* ./
RUN composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-suggest

COPY . ./

# Adjust the following 2 lines if your web application needs any other folders creating or to be writable
RUN mkdir -p var/cache var/sessions var/log var/uploads config/jwt
RUN chown -R www-data var config
RUN openssl genrsa -out ${JWT_PRIVATE_KEY_PATH} -aes256 -passout pass:${JWT_PASSPHRASE} 4096
RUN openssl rsa -passin pass:${JWT_PASSPHRASE} -pubout -in ${JWT_PRIVATE_KEY_PATH} -out ${JWT_PUBLIC_KEY_PATH}

RUN composer dump-autoload --classmap-authoritative --no-dev

COPY _docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint
ENTRYPOINT ["docker-entrypoint"]

CMD ["php-fpm"]
