# Variables
DOCKER_COMPOSE = docker compose -f docker-compose.yml
PHP_CONTAINER = app
NGINX_CONTAINER = nginx
DB_CONTAINER = db

up:
	$(DOCKER_COMPOSE) up -d 

build:
	$(DOCKER_COMPOSE) build

down:
	$(DOCKER_COMPOSE) down

restart:
	$(DOCKER_COMPOSE) down && $(DOCKER_COMPOSE) up -d --build

composer-install:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) composer install

npm-install:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) npm install

npm-dev:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) npm run dev

npm-build:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) npm run build

migrate:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan migrate

artisan:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan $(cmd)

fix-permission:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) sh -c "mkdir -p storage/framework/{sessions,views,cache} storage/app/public bootstrap/cache && chown -R www:www storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"

seed:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan db:seed

test:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan test