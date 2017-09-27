.DEFAULT_GOAL = help

vendor:	composer.json
	composer install

CONSOLE := $(shell which bin/console)

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-10s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

composer.lock: composer.json
	composer update

install: vendor composer.lock ## install project

test: install ## launch test
	php ./vendor/bin/phpunit
.PHONY: test


cache-clear: ## clear cache
ifdef CONSOLE
	@$(CONSOLE) cache:clear --no-warmup
else
	@rm -rf var/cache/*
endif
.PHONY: cache-clear

cache-warmup: cache-clear ## warmup cache
ifdef CONSOLE
	@$(CONSOLE) cache:warmup
else
	@printf "Cannot warm up the cache (needs symfony/console)\n"
endif
.PHONY: cache-warmup

serve: install ## run PHP server
	@$(CONSOLE) serv:star

serve-stop: install ## stop the server
	@$(CONSOLE) serv:sto
.PHONY: serve serve-stop

show-event: install ## show events symfony
	@$(CONSOLE) debug:event-dispatcher

show-service: install ## show services symfony
	@$(CONSOLE) debug:container
.PHONY: show-event show-service

doctrine-dump: install ## show dump sql
	@$(CONSOLE) doctrine:schema:create --dump-sql
doctrine-sql: install ## launch sql query
	@$(CONSOLE) doctrine:schema:create --force
.PHONY: doctrine-dump doctrine-sql
