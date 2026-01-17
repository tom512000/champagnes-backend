# Variables
PHP=docker exec -it symfony-php-champagnes
COMPOSE=docker compose

# ------------------------------
# Containers
# ------------------------------

up:
	$(COMPOSE) up -d

down:
	$(COMPOSE) down

build:
	$(COMPOSE) up -d --build

install: 
	$(COMPOSE) up -d
	$(PHP) composer install
	$(PHP) php bin/console doctrine:migrations:migrate --no-interaction --env=dev
	$(PHP) php bin/console doctrine:fixtures:load --no-interaction --env=dev
	$(PHP) php bin/console cache:clear --env=dev

jwt:
	$(PHP) php bin/console lexik:jwt:generate-keypair --overwrite

restart: down up

logs:
	$(COMPOSE) logs -f

bash:
	$(PHP) bash

# ------------------------------
# Symfony / Doctrine
# ------------------------------

cc:
	$(PHP) php bin/console cache:clear

migrate:
	$(PHP) php bin/console doctrine:migrations:migrate --no-interaction

migration:
	$(PHP) php bin/console make:migration

migration-diff:
	$(PHP) php bin/console doctrine:migrations:diff

fixtures:
	$(PHP) php bin/console doctrine:fixtures:load --no-interaction --env=dev

db-create:
	$(PHP) php bin/console doctrine:database:create --if-not-exists

db-drop:
	$(PHP) php bin/console doctrine:database:drop --force --if-exists

# ------------------------------
# Quality of life
# ------------------------------

cs-fix:
	$(PHP) vendor/bin/php-cs-fixer fix

tests:
	$(PHP) php bin/phpunit
