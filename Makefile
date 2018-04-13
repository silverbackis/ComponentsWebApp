CURRENT_DIRECTORY:=$(shell pwd)
ENV?=dev

env:
	@cp ./api/.env.dist ./api/.env
	@cp ./app/.env.dist ./app/.env

start:
	@docker-compose -f ./docker-compose.yaml -f ./docker-compose-$(ENV).yaml up -d --force-recreate

stop:
	@docker-compose down

pull:
	@docker-compose pull --ignore-pull-failures

.PHONY: env start stop pull
