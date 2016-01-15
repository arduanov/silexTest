[![Build Status](https://travis-ci.org/arduanov/silexTest.svg?branch=master)](https://travis-ci.org/arduanov/silexTest)

## Vagarant

    $ vagrant plugin install vagrant-hostsupdater
    $ vagrant up
    $ vagrant ssh

    
## Project Installation

    $ cd /var/www
    $ composer install && composer run-script post-root-package-install
    
[Open site http://silex.dev](http://symfony.dev)
	
## DB commands
	$ php ./bin/db.console orm:schema-tool:update --force
    $ php ./bin/db.console migrations:migrate --no-interaction

## Tests

    $ ./vendor/bin/codecept run