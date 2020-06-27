## Application structure

#### app/Exceptions -> handle exceptions of the application
#### app/Http -> perform the request + validate the request via Controllers
#### app/Models -> metadefinition of the tables + relationship between them
#### app/Providers
#### app/Services
#### app/Repository -> perform query to the database
#### config -> config files of the app
#### database -> migration, seeds, factories for unit tests
#### public -> index.php front controller of the app
#### routes -> public and api routes(need authentication)
#### tests -> perform integration tests for app

#### This is a MVC project structure

#### Run commands
`` php artisan config:cache --env=dev`` \
`` php artisan route:clear`` \
`` php artisan config:clear`` \
`` php artisan cache:clear`` \
`` php artisan migrate:refresh`` \
`` php artisan db:seed`` \
`` php artisan passport:install`` \
`` php artisan db:seed`` \
`` php artisan serve`` \
`` php artisan make:factory``

#### Create testing environment

``php artisan config:cache --env=testing`` 

#### Goals of the project
#### Create crud user, crud images for a specific user and the possibility to add comments to a image
