## Synopsis

TDD-built laravel 5.4 project that allows Teams, Users (each with roles, permissions, owned models, et cetera) to CRUD many leadboxes belonging to blogs, which belong to bloggroups. 

## Models
The eloquent models used in this project are
* Role
* Permission
* Team
* User
* BlogGroup
* Blog
* Leadbox

## Tests
Run tests whenever you refactor code!
The tests are located in the 'tests' directory. Acceptance tests are set up for current site functionality and authorization so controllers should only really be edited once you have set up your environment for testing and are able to run tests.
* This project was built mostly by TDD (writing tests for your desired functionality, then coding to make the tests pass. This saves countless hours of manually testing different functionality and scenarios in the browser and accounts for many more scenarios.) When additions are conceptualized for the project, they should be approached in this way.

## Database Migrations
There are migrations set up for above models.
* The only database level constraints are on the Team <-> User relationship (with pivot table). 
* Deletion of other tightly coupled tables is done via the boot method on the BlogGroup, Blog, and Lead models to keep flexibility should policies change

There are Model factories set up for all models, but before using, check and make sure you know which ones create relationship models at the same time.

There is also a seeder set up (run ```php artisan db:seed```) to get you some dummy data to play with

## Authorization
Authorization uses the standard auth scaffolding included out-of-the-box but with custom policies.
Policies are located in the app/Policies directory and are called in the AuthServiceProvider. They follow the BasicAuthModelPolicy abstract class to provide boilerplate store/delete methods. Once authorization is more deeply ingrained in the code this will be a good place to define policy methods that must be implemented by children. User and Team models do not follow this abstract class as they have more specific admin uses.
* Documentation on how the authorization rules can be found in these Policy files and more importantly, in the 'Feature' tests.
* All application routes are protected by basic authorization and additional gates are used in the controllers for certain methods

## Controllers
All controllers are in app/Https/Controllers
* Controllers are set up basically as CRUD resource controllers and are not entirely api-centric. These will likely need slight updates when the front end is updated to us AJAX for requests. 

## Views
To keep this system as simple as possible for as long as possible. There are only 3 derived views being used in resources/views/models/ called index, show, and create (show also doubles as an edit view).
Once the functionality of each model becomes more specific, sub-views will be broken out to respective directories.

## Set up

* Clone the repo
* Navigate to the file directory in the console and run (you must have composer installed globally on your machine): 
<code><pre>composer install</pre></code>

* Create a MySQL database on your machine (for example, entitled 'LB_Maker'). And also create one for testing (for example 'LB_Maker_Test')
* Retrieve a .env file from the project maintainer and update with your machine's information
* From the console, run the command:
<code><pre>php artisan migrate</pre></code>

* For testing, update the .env file to the test database, and run 'php artisan migrate' again. Then reset your .env file back to you main database. (This ensures unit tests are able to work on the test database)
* For testing, make sure your phpunit.xml file contains the name of your test database
