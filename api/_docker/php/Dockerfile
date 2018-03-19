ARG PHP_VERSION=7.2
ARG ALPINE_VERSION=3.7
FROM composer:1.5
FROM php:${PHP_VERSION}-fpm-alpine${ALPINE_VERSION}

RUN apk add --no-cache --virtual .persistent-deps \
		git \
		icu-libs \
		zlib

ENV APCU_VERSION 5.1.11

RUN set -xe \
	&& apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		zlib-dev \
		freetype-dev \
		libpng-dev \
		libjpeg-turbo-dev \
		rabbitmq-c-dev \
		libssh-dev \
		libxml2-dev autoconf g++ imagemagick-dev libtool make \
	&& docker-php-ext-configure gd \
	  --with-gd \
	  --with-freetype-dir=/usr/include/ \
	  --with-png-dir=/usr/include/ \
	  --with-jpeg-dir=/usr/include/ \
	&& docker-php-ext-install \
		exif \
    bcmath \
    sockets \
    pdo \
    pdo_mysql \
    intl \
    zip \
	&& docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) gd \
  && pecl install \
    apcu-${APCU_VERSION} \
    amqp \
    imagick \
  && docker-php-ext-enable --ini-name 20-apcu.ini apcu \
  && docker-php-ext-enable --ini-name 05-opcache.ini opcache \
  && docker-php-ext-enable amqp \
  && docker-php-ext-enable imagick \
	&& apk del .build-deps

RUN pecl install xdebug \
  && docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY _docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/wwww.conf

ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --classmap-authoritative

WORKDIR /srv/api

COPY . ./

RUN mkdir -p var/cache var/logs var/sessions \
	&& composer dump-autoload --classmap-authoritative --no-dev \
	&& chown -R www-data var

COPY _docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
