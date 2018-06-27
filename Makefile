#
# N3 project Makefile
#
# @see https://www.gnu.org/software/make/
#

PHPUNIT_OPTIONS ?= ''

.PHONY: install clean test coverage update

install:
	composer install --no-interaction
	cd lambda_functions/scanner && npm install && cd ../../

clean:
	rm -rf vendor/ dist/

test: install
	./vendor/bin/phpunit $(PHPUNIT_OPTIONS)
	./vendor/bin/php-cs-fixer fix --dry-run -v
	cd lambda_functions/scanner && npm test

coverage: install
	./vendor/bin/phpunit --coverage-clover=dist/tests.clover $(PHPUNIT_OPTIONS)

update:
	composer update --no-interaction

format: install
	./vendor/bin/php-cs-fixer fix -v
