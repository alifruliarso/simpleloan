

sail := ./vendor/bin/sail

.PHONY: rm
rm:
	$(sail) down -v

.PHONY: docker-up
docker-up:
	$(sail) up -d # get services running
	sleep 10

.PHONY: backend-install
backend-install:
	docker run --rm \
        -u "$$(id -u):$$(id -g)" \
        -v $$PWD:/var/www/html \
        -w /var/www/html \
        laravelsail/php82-composer:latest \
        composer install --ignore-platform-reqs

.PHONY: backend-setup
backend-setup:
	$(sail) artisan key:generate # generate app key

.PHONY: backend-migrate
backend-migrate:
	$(sail) artisan migrate --seed # db migration

.PHONY: frontend-clean
frontend-clean:
	rm -rf node_modules 2>/dev/null || true
	rm package-lock.json 2>/dev/null || true
	rm yarn.lock 2>/dev/null || true
	$(sail) yarn cache clean

.PHONY: frontend-install
frontend-install:
	make frontend-clean
	$(sail) yarn install
	$(sail) npx mix

.PHONY: dev
dev:
	make backend-install
	make docker-up
	make backend-setup
	make backend-migrate

.PHONY: watch
watch:
	$(sail) npx mix watch

.PHONY: down
down:
	$(sail) down