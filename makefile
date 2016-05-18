test: phpunit phpcs

build: phpunit-coverage phpcs

phpunit:
	vendor/bin/phpunit --no-coverage

phpunit-coverage:
	vendor/bin/phpunit

phpcs:
	vendor/bin/phpcs -p --standard=PSR2 src tests
