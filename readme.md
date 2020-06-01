Optimization && future tasks

Optimization
The external api should have a flag 'updated_at' which help us to take data differential
For exemple 
I did some schedule tasks with collect data from third party application. 
After first import we should take only the data that has changed by comparing the updated_at flag from our entity with updated_at flag from external entity
This can help up to run very often crons and take data up to date with third party app

Future tasks
Write unit and integration tests. I know this part is very importanr but time was a big problem for me and I can't cover this topic
I can write tests if you give me one more day.
Isolate much better components like ThirdParty directory
Use a test database to execute integration tests

Approaches and structure, database design

I used and MVC pattern to build this app

Structure of folders

api-doc
	-> a postman collection with all apis built
commands
	-> which collect and import data from tirdparty to the database
console
	-> to run tasks automatically at some time
dto
   -> to map an external resource to a valid entity
exceptions
	-> handle http exceptions and transform them to a json error to UI
controllers
	-> define actions to execute(eg: getUsers, getPosts etc)
models
	-> define the metadata definitions of the sql tables
providers
	-> boot route app
repository
	-> query the db
services
	-> collect data from repository and model modelate them
thirtParty
	-> colect data from external source

routes
	-> define own routes of the app
config + database (set up env of the app and database conection + migrations)

Database design

user one to many relation with post
post one to many relation with comments
comments

user has an unique email and autoincremented id
post has a body text because body should contain large text
comment has a body text because body should contain large text

As a php developer I decided to have a clean code and implement oop principle on this project
I know can be a better version in the future. Now this version is a poc

I would be very exciting if I have the posibility to have the tehnical interview to discuss on details
