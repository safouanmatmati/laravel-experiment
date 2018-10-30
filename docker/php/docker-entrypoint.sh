#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] ; then
	mkdir -p storage

	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX storage
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX storage

	if [ "$APP_ENV" != 'prod' ]; then
		composer install --prefer-dist --no-progress --no-suggest --no-interaction
	fi
fi

exec docker-php-entrypoint "$@"
