Simple REST API
* `composer update`  
* Add in `.env`:
    * `DATABASE_URL`
    * `DATABASE_URL_TEST` - test DB
* `php bin/console doctrine:database:create`
* `php bin/console doctrine:migrations:migrate`
    * run tests `./bin/phpunit`




