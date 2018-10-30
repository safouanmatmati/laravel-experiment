# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/compose/compose-file/#target

ARG PHP_VERSION=5.6-fpm-alpine
ARG NGINX_VERSION=1.15-alpine
ARG REDIS_VERSION=5.0-alpine
ARG MYSQL_VERSION=5.7
ARG NODE_VERSION=8.12.0-alpine

####################
########## PHP
####################
FROM php:${PHP_VERSION} AS php

# persistent / runtime deps
RUN apk add --no-cache \
		acl \
	;

ARG APCU_EXT_VERSION=4.0.11
ARG REDIS_EXT_VERSION=2.2.8

RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
	; \
	docker-php-ext-install -j$(nproc) \
		pdo_mysql \
	; \
	pecl install \
		apcu-${APCU_EXT_VERSION} \
		redis-${REDIS_EXT_VERSION} \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
	; \
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
	apk del .build-deps

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY docker/php/conf.d /usr/local/etc/php/conf.d
COPY docker/php/php-fpm.d /usr/local/etc/php-fpm.d

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN set -eux; \
	composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /srv/shop

# build for production
ARG APP_ENV=production

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.json composer.lock ./
RUN set -eux; \
	composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress --no-suggest; \
	composer clear-cache

COPY . ./

RUN set -eux; \
	mkdir -p storage/app storage/logs storage/framework/cache storage/framework/sessions storage/framework/views; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer run-script --no-dev post-install-cmd; \
	sync

VOLUME /srv/shop/storage

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

EXPOSE 9000

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

####################
########## NGINX
####################
FROM nginx:${NGINX_VERSION} AS nginx

COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /srv/shop

COPY --from=php /srv/shop/public public/

EXPOSE 80

####################
########## MYSQL
####################
FROM mysql:${MYSQL_VERSION} AS mysql
EXPOSE 3306 33060

####################
########## REDIS
####################
FROM redis:${REDIS_VERSION} AS redis
EXPOSE 6379

####################
########## NODE
####################
FROM node:${NODE_VERSION} AS node

# Set working directory
WORKDIR /srv/shop

# Prevent the reinstallation of node modules at every changes in the source code
COPY --from=php /srv/shop/package.json /srv/shop/yarn.lock /srv/shop/gulpfile.js ./
COPY --from=php /srv/shop/resources/assets resources/assets/
COPY --from=php /srv/shop/public public/

# Install python needed by some dependencies
# --no-cache: download package index on-the-fly, no need to cleanup afterwards
# --virtual: bundle packages, remove whole bundle at once, when done
RUN apk --no-cache --virtual build-dependencies add \
	    python \
	    make \
	    g++; \
		yarn global add gulp-cli; \
	    yarn; \
    apk del build-dependencies

EXPOSE 3000

CMD ["yarn", "start"]
