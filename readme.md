## Smart Developer Hub Web

# How to add dashboards
To add a new dashboard, create a new file inside "/resources/views/dashboards/" directory with ".blade.php" extension.
The "/resources/views/empty.blade.php" file explains how this file must be filled and can be used as a template.

# How to install it

## Required services and tools
First of all you need to have the following services and tools:

- Web server (Apache, Nginx...)
- PHP 
- MySQL server
- Composer
- Bower
We recommend the use of Homestead (https://laravel.com/docs/master/homestead) as it contains all of them.
Make sure to point your web server root path to the /public folder of this repository.

## Install dependencies
Use composer to install all the dependencies:
```
composer install
```

## Configure environment
Create a copy of the .env.sample file provided in he repository and call it .env. Edit that .env file:

1. Set the database configuration variables (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD). Make sure the database exists in your MySQL server.
2. Set the API configuration variables:
    * SDH_API: this is an URL that will be used in the dashboards to access the SDH API, so it should be accessible from a web browser.
    * SDH_API_INTERNAL: this is an URL that will be used by Laravel to access he API authentication endpoints.
    
## Create and populate the database structure
Use the Laravel artisan cli to execute the migration and seed files:
```
php artisan migrate --force --seed
```

# Copyright
All rights reserved © 2015. Center Open Middleware