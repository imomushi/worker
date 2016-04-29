all: cs unit
travis: cs unit-travis

init:
	if [ ! -d vendor ]; then composer install; fi;

cs: init
	./bin/phpcs --standard=PSR2 src/ tests/

unit: init
	./bin/phpunit --coverage-text --coverage-html covHtml

unit-travis: init
	./bin/phpunit --coverage-text --coverage-clover ./build/logs/clover.xml

travis-coverage: init
	if [ -f ./build/logs/clover.xml ]; then wget https://scrutinizer-ci.com/ocular.phar && php ocular.phar code-coverage:upload --format=php-clover ./build/logs/clover.xml; fi
