# Fest Backend

Integrated system that supports the website, registration and runtime processes.

Uses [Laravel 4.2](http://laravel.com/docs/4.2). Please refer the official docs to better understand the code.


## Features

API that provides data for the website front-end. Backend web app for various managers.


## How to setup for *Development* (local)

1. Clone the repository.

2. Run `composer install`. If you don't have composer yet, get it [here](http://getcomposer.com). It will install all the required dependencies.

3. Fill in configuration details inside the folder`app/config/local`. All the configuration files you need to edit are in this folder.

4. Enter your hostname to detect the local environment in `bootstrap/start.php`. (Under Windows, use the command `hostname` in command prompt to get it. The value is case-sensitive.) To make sure Laravel has detected the correct environment, run the command `php artisan env` from the root folder. It should show the current environment as `local`.

5. To set up database automatically, run the command `php artisan migrate:refresh --seed`. It will automatically fill up the database with seed values. Check the `app/database/migrations` and `app/database/seeds` folder to change the database fields.

6. This should set up the installation, and should work locally.

## How to *deploy* on server

1. Fill up configuration details for the production environment under `app/config/production`. 

2. Export the database from phpMyAdmin and import it into the database on the server.

3. Copy **only** the `public` folder to the path on the web server inside `public_html`. The rest of the files will be stored outside the `public_html` folder.

4. Copy the `app`, `bootstrap` and `vendor` folders into a directory outside `public_html`, say `backend_files`. 

5. Update the paths in `public/index.php` with the full file paths to the bootstrap folder. Example, `/home/user_name/backend_files/bootstrap/...`.

6. Update the public path in `bootstrap/paths.php` to point to the `index.php` inside `public_html`.

7. You should be ready to go, and the app will be live.