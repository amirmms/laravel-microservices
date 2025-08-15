#!make

default: help

build: ## run docker compose build
	docker compose build

ps: ## docker compose ps
	docker compose ps

up: ## docker compose up
	docker compose up -d

down: ## docker compose down
	docker compose down

down-volumes: ## docker compose down with volumes
	docker compose down --volumes

restart: ## docker compose restart
	docker compose restart

ucomposer: ## run composer commands in user service
	docker compose exec user-service composer $(filter-out $@,$(MAKECMDGOALS))
%:

acomposer: ## run composer commands in auth service
	docker compose exec auth-service composer $(filter-out $@,$(MAKECMDGOALS))
%:

uart: ## run artisan command in user service
	docker compose exec user-service php artisan $(filter-out $@,$(MAKECMDGOALS))
%:
	@:

aart: ## run artisan command in auth service
	docker compose exec auth-service php artisan $(filter-out $@,$(MAKECMDGOALS))
%:
	@:

logs: ## docker compose logs
	docker compose logs -f

rebuild: ## rebuild one container by name
	docker compose up -d --no-deps --build $(filter-out $@,$(MAKECMDGOALS))
%:
	@:

# makefile help
help:
	@echo "usage: make [command]"
	@echo ""
	@echo "available commands:"
	@sed \
    		-e '/^[a-zA-Z0-9_\-]*:.*##/!d' \
    		-e 's/:.*##\s*/:/' \
    		-e 's/^\(.\+\):\(.*\)/$(shell tput setaf 6)\1$(shell tput sgr0):\2/' \
    		$(MAKEFILE_LIST) | column -c2 -t -s :