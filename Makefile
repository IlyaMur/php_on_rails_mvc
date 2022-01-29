install:
	composer install

test:
	composer exec phpunit tests

lint:
	composer exec phpcs -- --standard=PSR12 src tests

test-coverage:
	composer exec --verbose phpunit -- --testsuite gh-actions --coverage-clover build/logs/clover.xml

docker-start: 
	docker-compose up -d && make docker-install

docker-stop: 
	docker-compose down

docker-install:
	docker exec -it php-apache composer install

docker-test:
	docker exec -it php-apache make test

docker-bash:
	docker exec -it php-apache bash

env-prepare:
	cp -n .env.example .env || true