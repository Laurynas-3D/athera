DC := docker compose
APP := $(DC) exec athera_app

.PHONY: help up down shell cache-clear migrate stan rector rector-fix

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-14s\033[0m %s\n", $$1, $$2}'

up:
	$(DC) up -d --build

down:
	$(DC) down

shell: ## Open a shell in the app container
	$(APP) bash

cache-clear cc:
	$(APP) php bin/console cache:clear

migrate mi:
	$(APP) php bin/console doctrine:migrations:migrate --no-interaction

stan:
	$(APP) vendor/bin/phpstan analyse --memory-limit=1G

rector:
	$(APP) vendor/bin/rector process --dry-run

rector-fix:
	$(APP) vendor/bin/rector process
