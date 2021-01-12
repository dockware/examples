#
# Makefile
# Author: Christian Dangl
#

.PHONY: help
.DEFAULT_GOAL := help


help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'


# ------------------------------------------------------------------------------------------------------------
# Initial Setup, if using SFTP

download: ## Downloads files from the container to your host
	mkdir -p ../../src
	docker cp shop:/var/www/html/. ../../src

# ------------------------------------------------------------------------------------------------------------
# Updating, rebasing, starting new tasks

pull: ## Pulls the latest changes from the development and platform repository
	cd ../../src && git pull
	cd ../../src/platform && git pull

upload: ## Upload all files to your container
	docker exec -it shop bash -c 'sudo rm -rf /var/www/html/platform/* && sudo rm -rf /var/www/html/platform/.*' || true
	docker cp ../../src/. shop:/var/www/html/
	docker exec -it shop bash -c 'sudo chown www-data:www-data /var/www -R'

run: ## Upload all files to your container and install Shopware
	@make upload -B
	docker exec -it shop bash -c 'sudo rm -rf composer.lock'
	docker exec -it shop bash -c './psh.phar install'
	open http://localhost
	open http://localhost/admin

# ------------------------------------------------------------------------------------------------------------
# dev, tools and more

ssh: ## Start a new SSH connection into the container
	docker exec -it shop bash

test: ## Runs all PHPUnit tests. Optionally provide a custom group with group=xy
ifdef group
	docker exec -it shop bash -c 'php ./vendor/bin/phpunit --configuration ./vendor/shopware/platform/phpunit.xml.dist --group $(group)'
else
	docker exec -it shop bash -c 'php ./vendor/bin/phpunit --configuration ./vendor/shopware/platform/phpunit.xml.dist'
	docker exec -it shop bash -c './psh.phar administration:unit'
endif

pr: ## Runs everything to prepare the PR files and downloads it to your host
	docker exec -it shop bash -c './psh.phar fix-cs'
	docker exec -it shop bash -c './psh.phar static-analyze'
	docker exec -it shop bash -c 'cd platform/src/Administration/Resources/app/administration && ./node_modules/.bin/eslint --fix --ext .js,.vue src test'
ifndef mounted
	docker cp shop:/var/www/html/platform/src/. ../../src/platform/src
endif
