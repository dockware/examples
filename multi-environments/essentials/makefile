.PHONY: help
.DEFAULT_GOAL := help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

# ---------------------------------------------------------------------------------------------

init: ## Installs and initializes all environments
	docker exec -it shop1 bash -c 'cd /var/www && make download url=https://www.shopware.com/en/Download/redirect/version/sw6/file/install_v6.4.11.1_4acac2b5012d6a03377ae5881590aec8cda0196b.zip'
	docker cp configs/shop1/.env shop1:/var/www/html/.env
	docker exec -it shop1 bash -c 'php bin/console system:install --create-database --basic-setup'
	docker exec -it shop1 bash -c 'APP_ENV=prod php bin/console store:download -p SwagPlatformDemoData && APP_ENV=prod php bin/console plugin:refresh && APP_ENV=prod php bin/console plugin:install --activate SwagPlatformDemoData'
	docker exec -it shop1 bash -c 'php bin/console cache:clear'
	# -----------------------------------------------------------------------------------------------------------
	docker exec -it shop2 bash -c 'cd /var/www && make download url=https://www.shopware.com/en/Download/redirect/version/sw6/file/install_v6.4.11.1_4acac2b5012d6a03377ae5881590aec8cda0196b.zip'
	docker cp configs/shop2/.env shop2:/var/www/html/.env
	docker exec -it shop2 bash -c 'php bin/console system:install --create-database --basic-setup'
	docker exec -it shop2 bash -c 'APP_ENV=prod php bin/console store:download -p SwagPlatformDemoData && APP_ENV=prod php bin/console plugin:refresh && APP_ENV=prod php bin/console plugin:install --activate SwagPlatformDemoData'
	docker exec -it shop2 bash -c 'php bin/console cache:clear'
	# -----------------------------------------------------------------------------------------------------------
	@open https://shop1.shopware.com
	@open https://shop2.shopware.com

download: ## Downloads files from the containers to your host
	mkdir -p ./src/shop1
	mkdir -p ./src/shop2
	docker cp shop1:/var/www/html/. ./src/shop1
	docker cp shop2:/var/www/html/. ./src/shop2

watch-storefront: ## Starts the Storefront Watcher, [make storefront-admin shop=shop1]
	bash -c "trap 'make stop-watch-storefront' EXIT; docker exec -it $(shop) bash -c 'cd /var/www && APP_URL=http://0.0.0.0 make watch-storefront'"

watch-admin: ## Starts the Admin Watcher, [make watch-admin shop=shop1]
	docker exec -it $(shop) bash -c 'cd /var/www && make watch-admin'