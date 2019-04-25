FROM silverbackis/php:symfony4

# blackfire enable
RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/alpine/amd64/$version \
    && mkdir -p /tmp/blackfire \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz

# Cron jobs
COPY ./_docker/php/crontab /etc/cron.d/app-cron
RUN chmod 0644 /etc/cron.d/app-cron; \
  crontab /etc/cron.d/app-cron; \
  touch /var/log/cron.log
CMD cron && tail -f /var/log/cron.log

COPY ./_docker/php/mods-available/* /usr/local/etc/php/mods-available/

WORKDIR /srv/api

# Prevent Symfony Flex from generating a project ID at build time
ARG SYMFONY_SKIP_REGISTRATION=1
ARG APP_ENV='prod'
ENV APP_ENV=${APP_ENV}


COPY composer.* symfony.lock ./

RUN set -eux; \
	php -d memory_limit=-1 /usr/bin/composer install --prefer-dist --no-dev --no-progress --no-suggest --no-scripts; \
  composer dump-env --empty prod;

COPY . ./

RUN set -eux; \
	mkdir -p var/cache var/sessions var/log var/uploads config/jwt; \
	composer dump-autoload --classmap-authoritative --no-dev --optimize; \
  sync

COPY _docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint

RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
