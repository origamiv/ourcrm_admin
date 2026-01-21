$(eval env=$(shell sh -c "cat ./.env | grep -v ^# | xargs -0"))
$(eval user=$(shell sh -c "echo $$(id -u)"))

.PHONY: start stop update app nginx es exec

build:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml up --build -d

start:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml up -d

run:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml up --build

stop:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml down

update:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml build --no-cache

install:
	git checkout dev
	cp .env.example .env
	chmod 600 ./docker/key/id_rsa
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml up -d
	env $(env) UID=$(user) chmod 600 ./docker/.ssh/id_rsa
	env $(env) UID=$(user) chmod 600 ./docker/key/id_rsa
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm composer update
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm npm install
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm npm run build
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan key:generate
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan storage:link --relative
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan project:install
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan project:fresh
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan project:menu
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm sh front_link.sh
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan project:menu
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan project:sync_permissions
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan optimize:clear

refresh:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan cache:clear
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan view:clear
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan config:clear
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan route:clear
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan project:fresh
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan project:menu
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan project:sync_permissions

db:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan migrate --seed

fresh:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec -T db psql database < database/init/shems.sql
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan fresh
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan l5-swagger:generate --all

fresh_test:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec -T db psql testing < database/init/test.sql
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan fresh --env=testing

test:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan test

app:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm sh

app_root:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec -u 0 fpm sh

fix:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php ./vendor/bin/php-cs-fixer fix ./

nginx:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec nginx sh

rundb:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec db bash

initdb:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec -T db psql database < database/init/shems.sql

exec:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm $(command)

cron:
	env $(env) UID=$(user) docker-compose --file ./docker-compose.yml exec fpm php artisan schedule:run >&1 2>&1
