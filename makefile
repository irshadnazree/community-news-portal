up:
	docker compose up -d --build

build:
	docker compose build

down:
	docker compose down

restart:
	docker compose down && docker compose up -d --build

composer-install:
	docker compose exec app composer install

npm-install:
	docker compose exec app npm install

npm-dev:
	docker compose exec app npm run dev

npm-build:
	docker compose exec app npm run build

migrate:
	docker compose exec app php artisan migrate

artisan:
	docker compose exec app php artisan $(cmd)

fix-permission:
	docker compose exec app sh -c "mkdir -p storage/framework/{sessions,views,cache} storage/app/public bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"

seed:
	docker compose exec app php artisan db:seed

test:
	docker compose exec app php artisan test