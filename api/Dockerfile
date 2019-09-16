# This is using API Platform's Dockerfile

# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target
# https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact

# 7.3.9 has issues with environment vars
ARG PHP_VERSION=7.3.8
ARG NGINX_VERSION=1.17
ARG VARNISH_VERSION=6.2

# "varnish" stage
# does not depend on any of the above stages, but placed here to keep everything in one Dockerfile
FROM cooptilleuls/varnish:${VARNISH_VERSION}-alpine AS sbwa_varnish

RUN apk add --no-cache gettext libintl
COPY _docker/varnish/conf/default.vcl /tmp/varnish/conf/default.vcl
COPY _docker/varnish/start.sh /usr/local/bin/start

RUN chmod +x /usr/local/bin/start
ENTRYPOINT ["start"]

CMD ["varnishd", "-F", "-f", "/usr/local/etc/varnish/default.vcl", "-p", "http_resp_hdr_len=256000", "-p", "http_req_hdr_len=256000"]

# "php" stage
FROM php:${PHP_VERSION}-fpm-alpine AS sbwa_php

# persistent / runtime deps
RUN apk add --no-cache \
		acl \
		file \
		gettext \
		git \
		# required for inky (email extension)
		libxslt-dev \
    imagemagick \
    freetype \
    libpng \
    libjpeg-turbo \
    yarn \
    # for intl
    icu-libs \
	;

ARG APCU_VERSION=5.1.17
RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
		zlib-dev \
		rabbitmq-c-dev \
    imagemagick-dev \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libssh-dev \
		postgresql-dev \
	; \
	\
	docker-php-ext-configure zip --with-libzip; \
	docker-php-ext-install -j$(nproc) \
		intl \
		pdo_pgsql \
		zip \
		xsl \
		exif \
		bcmath \
		sockets \
		gd \
	; \
	pecl install \
		apcu-${APCU_VERSION} \
		amqp \
	; \
	pecl install imagick ; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
		amqp \
		imagick \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
	\
	apk del .build-deps

# Enable Blackfire probe
RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/alpine/amd64/$version \
    && mkdir -p /tmp/blackfire \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY _docker/php/conf.d/silverback-web-apps.ini $PHP_INI_DIR/conf.d/silverback-web-apps.ini

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
# install Symfony Flex globally to speed up download of Composer packages (parallelized prefetching)
RUN set -eux; \
	composer global require "symfony/flex" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /srv/api

# build for production
ARG APP_ENV=prod
ARG APP_SECRET=UNSET

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.json *.lock ./

RUN set -eux; \
	composer install --prefer-dist --no-dev --no-scripts --no-progress --no-suggest; \
  composer clear-cache

COPY bin bin/
COPY config config/
COPY assets assets/
COPY templates templates/
COPY public public/
COPY src src/

# do not use .env files in production
COPY .env .
RUN composer dump-env prod; \
  rm .env

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; \
	sync;

VOLUME /srv/api/var

COPY _docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

# "nginx" stage
# depends on the "php" stage above
FROM nginx:${NGINX_VERSION}-alpine AS sbwa_nginx

ENV CORS_ALLOW_ORIGIN="https?://localhost:?[0-9]*"
ENV UPSTREAM_CONTAINER="php"
ENV UPSTREAM_PORT=9000

COPY _docker/nginx/conf.d/custom.conf /etc/nginx/conf.d/custom.conf
COPY _docker/nginx/conf.d/default.conf /srv/api/tmp/default.conf

WORKDIR /srv/api

COPY --from=sbwa_php /srv/api/public public/

COPY _docker/nginx/start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start
ENTRYPOINT ["start"]

CMD ["nginx", "-g", "daemon off;"]
