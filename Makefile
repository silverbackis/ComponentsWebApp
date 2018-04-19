CURRENT_DIRECTORY:=$(shell pwd)
env?=dev

env:
	./bin/copy-env.sh

php:
	@docker-compose -f ./docker-compose.yaml -f ./docker-compose-$(env).yaml up -d php
	read -r -p "Press any key when you can see all your files in your project's ./api firectory (composer install command running)" input
	make stop

start:
	@docker-compose -f ./docker-compose.yaml -f ./docker-compose-$(env).yaml up -d --force-recreate

stop:
	@docker-compose down

.PHONY: install start stop pull
