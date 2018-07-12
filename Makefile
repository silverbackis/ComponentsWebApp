CURRENT_DIRECTORY:=$(shell pwd)
env?=dev

env:
	./bin/copy-env.sh

api:
	@docker-compose -f ./docker-compose.yaml -f ./docker-compose-$(env).yaml up -d api
	read -r -p "Press any key when you can see all your files in your project's ./api firectory (composer install command running)" input
	make stop

update:
	# Update the applications and as long as the lock files are mounted from local fs they will update
	@docker-compose -f ./docker-compose.yaml up -d
	@docker-compose exec api php -d memory_limit=-1 /usr/bin/composer update
	@docker-compose exec app yarn upgrade
	make stop
	# prod environment needs a full rebuild as composer installation is not done when app starts
	make build

pull:
	@docker-compose -f ./docker-compose.yaml pull
	@docker-compose -f ./docker-compose.yaml build --no-cache --pull

build:
	@docker-compose -f ./docker-compose.yaml build

start:
	@docker-compose -f ./docker-compose.yaml -f ./docker-compose-$(env).yaml up -d --force-recreate

stop:
	@docker-compose -f ./docker-compose.yaml -f ./docker-compose-$(env).yaml down

restart:
	make stop
	make start env=$(env)

.PHONY: env api update pull build start stop restart
