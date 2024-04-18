# Start project
up:
	docker-compose up -d
composer-install:
	docker-compose exec php composer install
# Stop project
down:
	docker-compose down
# Connect containers
php:
	docker-compose exec php /bin/bash
nginx:
	docker-compose exec nginx /bin/bash
# Alternatives commands
cache-clear:
	docker builder prune --all
autoload:
	docker-compose exec php composer dump-autoload
update:
	docker-compose exec php composer update