ARG PHP_VERSION=7.2
ARG ALPINE_VERSION=3.7
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
		libssh-dev \
		libxml2-dev \
	&& apk add --no-cache autoconf \
		g++ \
		imagemagick-dev \
		libtool \
		make \
  && pecl install \
    apcu-${APCU_VERSION} \
    imagick \
    xdebug \
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
  && docker-php-ext-enable --ini-name 20-apcu.ini apcu \
  && docker-php-ext-enable --ini-name 05-opcache.ini opcache \
  && docker-php-ext-enable imagick \
  && docker-php-ext-enable xdebug \
	&& apk del .build-deps

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY _docker/php/php.ini /usr/local/etc/php/php.ini
COPY _docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/wwww.conf

ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --classmap-authoritative

WORKDIR /srv/api

COPY . ./
RUN mkdir -p ./var/cache ./var/log ./var/sessions
RUN chown -R www-data ./var

RUN composer dump-autoload --classmap-authoritative --no-dev

COPY _docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

RUN usermod -u 1000 www-data
ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
