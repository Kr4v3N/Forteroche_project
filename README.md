# Forteroche_project
## Description:
### Create a blog for a writer

## Statement :

 You have just won a contract with Jean Forteroche, actor and writer. He is currently working on his next novel, "Billet simple pour l'Alaska". He wants to innovate and publish it in episodes online on his own website.

Only problem: Jean doesn't like WordPress and wants to have his own blog tool, offering simpler features. You will therefore have to develop a blog engine in PHP and MySQL.

You will develop a simple blog application in PHP and with a MySQL database. It must provide a frontend interface (reading tickets) and a backend interface (administering tickets for writing). It must contain all the elements of a CRUD:

+ Create: creation of tickets
+ Read: reading tickets
+ Update: update of posts
+ Delete: deletion of tickets

Each post must allow comments to be added, which can be moderated in the administration interface if necessary.
Readers should be able to "flag" comments so that they can be more easily moved up in the administration interface 
to be moderated.

The administration interface will be password protected. The writing of posts will be done in a WYSIWYG interface based on TinyMCE, 
so that Jean doesn't need to write his story in HTML (we understand that he doesn't really want to!).

You will develop in PHP without using a framework to familiarize yourself with the basic concepts of programming. 
The code will be built on a MVC architecture. You will develop as much as possible in object-oriented 
(at a minimum, the model must be built as an object).

Skills to be validated
--

+ Support and argue its proposals
+ Organize the code in PHP language
+ Retrieve the entry of a user form in PHP language
+ Analyze the data used by the site or application
+ Build a database
+ Create a website, from its conception to its delivery
+ Insert or modify the data of a database
+ Retrieve data from a database

---------------------------------------------------------
## Steps to install the project:

1. Clone the repos from Github.
2. Run `composer install`.
3. Create *app/db.php* from *app/db.php.dist* file and add your DB parameters. Don't delete the *.dist* file, it must be kept.
```php
define('APP_DB_HOST', 'your_db_host');
define('APP_DB_NAME', 'your_db_name');
define('APP_DB_USER', 'your_db_user_wich_is_not_root');
define('APP_DB_PWD', 'your_db_password');
```
4. Import `database.sql` in your SQL server.
6. Go to `localhost:8000` with your browser.
