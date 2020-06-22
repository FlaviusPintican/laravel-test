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
#### tests -> perform unit tests for app
