CURRENT_DIRECTORY:=$(shell pwd)
env?=dev

env:
	./bin/copy-env.sh

api:
	@docker-compose -f ./docker-compose.yaml -f ./docker-compose-$(env).yaml up -d php
	read -r -p "Press any key when you can see all your files in your project's ./api firectory (composer install command running)" input
	make stop

update:
	@docker-compose run api php -d memory_limit=-1 /usr/bin/composer update
	@docker-compose run app yarn upgrade

pull:
	@docker-compose -f ./docker-compose.yaml -f ./docker-compose-$(env).yaml pull
	@docker-compose -f ./docker-compose.yaml -f ./docker-compose-$(env).yaml build --no-cache --pull

build:
	@docker-compose -f ./docker-compose.yaml -f ./docker-compose-$(env).yaml build

start:
	@docker-compose -f ./docker-compose.yaml -f ./docker-compose-$(env).yaml up -d --force-recreate

stop:
	@docker-compose down

restart:
	make stop
	make start env=$(env)

.PHONY: env api update pull build start stop restart
