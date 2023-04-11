# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target

# https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
ARG PHP_VERSION=7.4
ARG OPENRESTY_VERSION=1.21.4.1-0
ARG VARNISH_VERSION=7.1

# "php" stage
FROM registry.gitlab.com/silverback-web-apps/cwa/docker/php:${PHP_VERSION}-fpm-alpine AS cwa_php

RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY _docker/php/conf.d $PHP_INI_DIR/

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
# install Symfony Flex globally to speed up download of Composer packages (parallelized prefetching)
RUN set -eux; \
	composer global config --no-plugins allow-plugins.symfony/flex true; \
    composer global require "symfony/flex" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

# build for production
ARG APP_ENV=prod

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.* symfony.lock ./
RUN set -eux; \
	composer install --prefer-dist --no-dev --no-scripts --no-progress --no-suggest; \
	composer clear-cache

# do not use .env files in production
COPY .env ./
RUN composer dump-env prod; \
	rm .env

# copy only specifically what we need
COPY bin bin/
COPY config config/
COPY public public/
COPY src src/
COPY assets assets/
COPY templates templates/

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; sync

VOLUME /srv/api/var

COPY _docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

# "nginx" stage
# depends on the "php" stage above
# The OpenResty distribution of NGINX is only needed for Kubernetes compatiblity (dynamic upstream resolution)
FROM openresty/openresty:${OPENRESTY_VERSION}-alpine AS cwa_nginx

RUN echo -e "env UPSTREAM;\n$(cat /usr/local/openresty/nginx/conf/nginx.conf)" > /usr/local/openresty/nginx/conf/nginx.conf
COPY _docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /srv/api/public

COPY --from=cwa_php /srv/api/public ./

# "varnish" stage
# does not depend on any of the above stages
FROM varnish:${VARNISH_VERSION} AS cwa_varnish

#USER root
#
#RUN set -e; \
#    apt-get update; \
#    apt-get -y install gettext-base $VMOD_DEPS /pkgs/*.deb; \
#    \
## install one, possibly multiple vmods
#   install-vmod https://github.com/varnish/varnish-modules/releases/download/0.20.0/varnish-modules-0.20.0.tar.gz; \
#    \
## clean up and set the user back to varnish
#    apt-get -y purge --auto-remove $VMOD_DEPS varnish-dev; \
#    rm -rf /var/lib/apt/lists/*
#
#USER varnish

USER root
RUN apt-get update; apt-get -y install gettext-base

#COPY _docker/varnish/conf/default.vcl /tmp/varnish/conf/default.vcl
#CMD ["envsubst", "'${UPSTREAM},${UPSTREAM_PORT},${PHP_SERVICE},${CORS_ALLOW_ORIGIN}' < /tmp/varnish/conf/default.vcl > /etc/varnish/default.vcl"]

COPY _docker/varnish/persist-environment.sh /usr/local/bin/persist-environment
COPY _docker/varnish/conf/default.vcl /tmp/varnish/conf/default.vcl

RUN chmod -R 777 /tmp/varnish/conf \
    && chmod -R 777 /etc/varnish \
    && chmod +x /usr/local/bin/persist-environment

USER varnish

EXPOSE 8080
ENTRYPOINT ["/usr/local/bin/docker-varnish-entrypoint", "/usr/local/bin/persist-environment"]
CMD ["varnishd", "-F", "-f", "/etc/varnish/default.vcl", "-p", "http_resp_hdr_len=65536", "-p", "http_resp_size=98304", "-p", "workspace_backend=512K", "-a", "http=:8080,HTTP", "-a", "proxy=:8443,PROXY", "-p", "feature=+http2", "-s", "malloc,100M"]
