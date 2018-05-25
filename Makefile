#
# N3 project Makefile
#
# @see https://www.gnu.org/software/make/
#

PHPUNIT_OPTIONS ?= ''

.PHONY: install clean test coverage update

install:
	composer install --no-interaction

clean:
	rm -rf vendor/ dist/

test: install
	./vendor/bin/phpunit $(PHPUNIT_OPTIONS)
	./vendor/bin/php-cs-fixer fix --dry-run -v

coverage: install
	./vendor/bin/phpunit --coverage-clover=dist/tests.clover $(PHPUNIT_OPTIONS)

update:
	composer update --no-interaction

format: install
	./vendor/bin/php-cs-fixer fix -v
