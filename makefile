test: phpunit phpcs phpmd

phpunit:
	vendor/bin/phpunit --no-coverage

phpunit-coverage:
	vendor/bin/phpunit

phpcbf:
	vendor/bin/phpcbf -p --standard=PSR2 src tests

phpcs:
	vendor/bin/phpcs -p --standard=PSR2 src tests

phpmd:
	vendor/bin/phpmd src text cleancode,codesize,controversial,design,naming,unusedcode
