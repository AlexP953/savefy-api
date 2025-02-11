# Savefy-api

This project is a personal finance management system that organizes financial data. It allows users to keep track of their income, categorized in a personalized way, and their expenses divided into specific categories. The system is built as a RESTful API using Laravel, MySQL and Passport for authentication, ensuring secure and efficient data management.

##  Dependencies

- PHP 8.2+
- Laravel 11
- Composer 2.8.3
- Passport
- Spatie
- Breeze
- FakerPHP
- PHPUnit

## Initial Setup

### Configure your credentials in the `.env` file:

  Copy the `.env.example` file to create the `.env` file:

   ```
   cp .env.example .env
   ```


Modify the sections related to the database in the `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=savefy
DB_USERNAME=<YOUR_USERNAME>
DB_PASSWORD=<YOUR_PASSWORD>
```

## Installing Dependencies

### Install PHP dependencies with Composer:
   ```
   composer install
  ```


## Generate the application key and migrate the database


### Create the database locally:
   - Make sure to create a database named `savefy` in your database manager.

### Run the migrations and seed the data:
```
php artisan migrate:fresh --seed
```

### Generate the application key:
```
php artisan key:generate
```

## Running the Project

### Start the development server:

  In one terminal, run the following command to start the server:

  ```
php artisan serve
  ```

## Credentials

To obtain the Bearer_token used in Postman, the “Get Token” endpoint of the oAuth collection must be called.

For this we will need to define the following variables:

```
username - alexperis95@gmail.com (admin) || alexperis95X@gmail.com (user)
password - 1234
client_secret - 
client_id -
```

To generate a client_id and a client_secret we must execute the command:

```
php artisan passport:client --password
```

What should we name the password grant client? - ENTER  
Which user provider should this client use to retrieve users? [users]: - ENTER

You will get something like

  Client ID -- 1  
  Client secret -- mWpjNtcuF5ntvISje3E9Hbu4orVKCgIjYxgnOYc9

  Defines the variables of the same name with the result obtained.


## Testing

### Testing Setup

#### Configure your credentials in the `.env` file:

  Copy the `.env.example` file to create the `.env.testing` file:

   ```
   cp .env.example .env.testing
   ```


Modify the sections related to the database in the `.env` file:

```
APP_ENV=testing
APP_KEY= 
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=savefy_test
DB_USERNAME=<YOUR_USERNAME>
DB_PASSWORD=<YOUR_PASSWORD>
```

To generate APP_KEY leave it empty and run

```
php artisan key:generate --env=testing
```

Migraciones
```
php artisan migrate --env=testing
php artisan db:seed --env=testing
```
Finally, run the tests. It may take a while 

```
php artisan test
```