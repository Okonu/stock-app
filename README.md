
# STOCK MANAGEMENT SYSTEM
## About the project
This is a project I developed for a warehousing client. It consists of two interfaces:
    1. Admin interface
    2. Mobile app

This repository focuses on the admin interface, as my specialization lies in implementing backend logic and system architecture.

## Features of the web/admin interface:
1. Admin roles:
    - Admin can create/register new users.
    - Admin can customize warehouses by adding names, partitions, and product details. In this case, the product is Tea, and its  details include Tea Producer name, tea farm name, tea grade name, etc., which are associated with stock taking.
    - Admin can perform stock reconciliation.
    - Admin can generate reports on various tea stock taking activities.
    - Admin can view stock taken and the clerk who performed the stock taking.
    - Admin can view users who have taken stock.
    - Admin can activate or deactivate the signup of any user/stock taking clerk who uses the mobile application by generating authentication tokens.
    - Admin can view real-time total quantities of bags/products from the stock taken.
    - Admin can view totals of items from the stock by filtering by product details, warehouse partitions, warehouse name, etc.
    - Admin can view the number of stock entries made by each stock taking clerk.

### User levels:
There are three different user levels for this product: admin, staff, and clerk. All users are created and registered by the admin. The system was designed for in-house use only.

### Mobile app endpoints:
##### The endpoints for the mobile app are located in the '/api/class/API.php' file. To use the endpoints, make sure to update the database configuration file 'database.php' located in the '/api/config' directory. The available endpoints include:
    - Mobile app login
    - Stock taking endpoints
    - Fetch stock taken by each user
    - Number of entries made by each user
    - Product (tea) details
    - Producers
    - Warehouses
    - Warehouse bays/partitions
    - Stock entries made by the specific user in the last 30 days

### Setting up and running the application:
Assuming you have already set up the Laravel environment (if not, please refer to the documentation at laravel.com for guidance), follow these steps:
    - Clone this repository using Git.
    - Create the .env file and configure the database settings.
    - Run 'composer install' to download the required packages and libraries.
    - Run 'php artisan migrate' to set up the database migrations.
    - Run 'php artisan key:generate' to generate the application key.
    - Run 'php artisan db:seed' to seed the database.
    - Finally, run 'php artisan serve' to start the application. The application will run on port 8000. Open your browser and enter 'http://127.0.0.1:8000/'.

#### Trial login details can be found in the 'DatabaseSeeder.php' file located in the '/database/seeds' directory.
I hope this information is helpful to you in some way. If you would like to collaborate, please reach out to me at ianyakundi015@gmail.com.

## Don't let the coffee get cold! Happy coding!