ifndef DOCKER_PROJECT_NAME
override DOCKER_PROJECT_NAME = meter_verification_module
endif

dev_initialize-environment: change-context-to-dev dev_down dev_up-rebuild dev_composer-install add-files dev_get-environment
dev_up:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.dev.yaml up -d
dev_up-rebuild:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.dev.yaml up -d --build
dev_down:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.dev.yaml down --remove-orphans
dev_down-clear:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.dev.yaml down --remove-orphans -v
dev_restart:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.dev.yaml down && docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.dev.yaml up -d
dev_composer-install:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.dev.yaml run --rm nginx_php-fpm /bin/bash -c "composer install --no-interaction --working-dir=/var/www/app/"
dev_get-environment: dev_database-migrate dev_messenger-setup-transport dev_messenger-start-consume
dev_database-create:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.dev.yaml run --rm nginx_php-fpm /bin/bash -c "php /var/www/app/bin/console doctrine:database:create --if-not-exists --no-interaction"
dev_database-migrate: dev_database-create
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.dev.yaml run --rm nginx_php-fpm /bin/bash -c "php /var/www/app/bin/console doctrine:migrations:migrate --allow-no-migration --all-or-nothing --no-interaction --no-debug "
dev_messenger-setup-transport:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.dev.yaml run --rm nginx_php-fpm /bin/bash -c " php /var/www/app/bin/console messenger:setup-transports --no-interaction --no-debug "
dev_messenger-start-consume:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.dev.yaml exec -T nginx_php-fpm /bin/bash -c "supervisorctl start messenger-consume:*"
dev_fixtures-load:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.dev.yaml run --rm nginx_php-fpm /bin/bash -c " php /var/www/app/bin/console doctrine:fixtures:load --no-debug --no-interaction"

prod_initialize-environment: prod_down prod_up-rebuild prod_composer-install add-files prod_get-environment
prod_up:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.prod.yaml up -d
prod_up-rebuild:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.prod.yaml up -d --build
prod_down:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.prod.yaml down --remove-orphans
prod_down-clear:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.prod.yaml down --remove-orphans -v
prod_restart:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.prod.yaml down && docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.prod.yaml up -d
prod_composer-install:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.prod.yaml run --rm nginx_php-fpm /bin/bash -c "composer install --no-dev --optimize-autoloader --no-interaction --working-dir=/var/www/app/ && composer dump-env prod --no-interaction --working-dir=/var/www/app/"
prod_get-environment: prod_database-migrate prod_messenger-setup-transport prod_messenger-start-consume
prod_database-create:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.prod.yaml run --rm nginx_php-fpm /bin/bash -c "php /var/www/app/bin/console doctrine:database:create --if-not-exists --no-interaction"
prod_database-migrate: prod_database-create
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.prod.yaml run --rm nginx_php-fpm /bin/bash -c "php /var/www/app/bin/console doctrine:migrations:migrate --allow-no-migration --all-or-nothing --no-interaction --no-debug "
prod_messenger-setup-transport:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.prod.yaml run --rm nginx_php-fpm /bin/bash -c " php /var/www/app/bin/console messenger:setup-transports --no-interaction --no-debug "
prod_messenger-start-consume:
	docker-compose -p $(DOCKER_PROJECT_NAME) -f docker-compose.prod.yaml exec -T nginx_php-fpm /bin/bash -c "supervisorctl start messenger-consume:*"
send-data-to-communication-module:
	docker exec meter_verification-nginx_php-fpm /bin/bash -c "php /var/www/app/bin/console app:sendDataToCommunicationModule"

add-files: create-files change-mod
create-files:
	docker exec meter_verification-nginx_php-fpm  /bin/bash -c "mkdir -p /var/www/app/var/csv && touch /var/www/app/var/csv/notificationData.csv && touch /var/www/app/var/csv/notificationData.csv.gz"
change-mod:
	docker exec meter_verification-nginx_php-fpm  /bin/bash -c "chmod -R 777 /var/www/app/var/csv && chmod -R 777 /var/www/app/var/cache/prod/pools"

create-env_local-prod: create-env_local change-APP_ENV-to-prod
create-env_local:
	cp .env .env.local
change-APP_ENV-to-prod:
	sed -i 's/APP_ENV=dev/APP_ENV=prod/' .env.local

imitate-prod: imitate-prod-down change-context-to-prod dev_up-rebuild prod_composer-install dev_get-environment change-context-to-dev
imitate-prod-down: dev_down
change-context-to-prod: create-env_local
	sed -i 's/.docker\/dev\/nginx_php-fpm/.docker\/prod\/nginx_php-fpm/' docker-compose.dev.yaml && sed -i 's/APP_ENV=dev/APP_ENV=prod/' .env.local
change-context-to-dev:
	sed -i 's/.docker\/prod\/nginx_php-fpm/.docker\/dev\/nginx_php-fpm/' docker-compose.dev.yaml && sed -i 's/APP_ENV=prod/APP_ENV=dev/' .env.local
