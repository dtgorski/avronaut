
BIN := ./vendor/bin

help:                                            # Displays this list
	@echo; grep -E "^[a-z][a-zA-Z0-9_<> -]+:.*(#.*)" Makefile | sed -r "s/:[^#]*?#?(.*)?/\r\t\t\t\1/" | uniq | sed "s/^/ make /"; echo
	@echo " Usage: make <TARGET> [ARGS=...]"; echo

clean:                                           # Removes generated files
	@rm -rf $(PWD)/tests/reports

dist-clean: clean                                # Removes generated files and ./vendor
	@rm -rf $(PWD)/vendor

install: dist-clean                              # Installs ./vendor dependencies
	@composer validate --strict
	@composer install $(ARGS)

update: clean                                    # Updates ./vendor dependencies
	@composer update $(ARGS)

test: clean .autoload                            # Executes unit tests
	@XDEBUG_MODE=coverage $(BIN)/phpunit -c ./tests/phpunit.xml $(ARGS) && if test -t 1 && test -z "$${PLATFORM}"; then echo "View in browser: <\e[32mfile://$(PWD)/tests/reports/coverage/index.html\e[0m>\n"; fi

test-all: sniff analyse test                     # Runs linter, static analysis, unit tests

sniff: clean .autoload                           # Runs linter on source and tests
	@if test `grep -rHEL "^// MIT" ./src/ ./tests/src/ | wc -l` != "0"; then echo "Missing License:"; grep -rHEL "^// MIT" ./src/ ./tests/src/; fi
	@if test `grep -rHEL "^declar" ./src/ ./tests/src/ | wc -l` != "0"; then echo "Missing declare:"; grep -rHEL "^declar" ./src/ ./tests/src/; fi
	@$(BIN)/phpcs -s --standard=tests/phpcs.xml ./src/ ./tests/src/ $(ARGS)

sniff-fix: clean .autoload                       # Tries to fix linter complaints
	@$(BIN)/phpcbf --standard=tests/phpcs.xml ./src/ ./tests/src $(ARGS)

analyse: clean .autoload                         # Performs static analysis
	@$(BIN)/psalm -c tests/psalm.xml --no-cache --no-suggestions --monochrome --no-progress $(ARGS) | perl -0pe "s/-{3,}\s+([^\!]+)\!\s+-{3,}\s+/\1.\n/"

.autoload:                                       # Creates the autoloader
	@composer -q dumpautoload
