# STOCK MANAGEMENT SYSTEM
## About the project
This is a project I developed for a warehousing client. It consists of two interfaces:

    1. Admin interface
    2. Mobile app

This repository focuses on the web interface, and the endpoints for the mobile interface.

## API Reference

This API provides a set of endpoints for the mobile interface for managing stock entries, warehouses, bays, owners, gardens, grades, and packages. All endpoints require the user to be logged in.

## Login

**POST /api/login**

Parameter | Type | Description
--- | --- | ---
phone | string | Required. User's phone number
password | string | Required. User's password

## Get Warehouses with Bays

**GET /api/warehouses/${warehouseId}/bays**

Parameter | Type | Description
--- | --- | ---
warehouseId | integer | Required. Id of warehouse to fetch bays for

## Get Bays and Warehouse

**GET /api/bays-and-warehouse**

## Get Warehouse

**GET /api/warehouse**

## Get Bays

**GET /api/bays/${wid}**

Parameter | Type | Description
--- | --- | ---
wid | integer | Required. Id of warehouse to fetch bays for

## Get Owners

**GET /api/owners**

## Get Gardens

**GET /api/gardens/${owner_id}**

Parameter | Type | Description
--- | --- | ---
owner_id | integer | Required. Id of owner to fetch gardens for

## Get Grades

**GET /api/grades**

## Get Packages

**GET /api/packages**

## Record Stock Entry

**POST /api/stocks**

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `userid`  | `integer`| **Required**. Id of user making the entry |
| `warehouse` | `integer` | **Required**. Id of warehouse |
| `bay` | `integer` | **Required**. Id of bay |
| `owner` | `integer` | **Required**. Id of owner |
| `garden` | `integer` | **Required**. Id of garden |
| `grade` | `integer` | **Required**. Id of grade |
| `packageType` | `integer` | **Required**. Id of package type |
| `invoice` | `string` | **Required**. Invoice number |
| `packageNumber` | `integer` | **Required**. Number of packages |
| `yearOfManufacture` | `integer` | **Required**. Year of manufacture (four-digit number) |
| `remarks` | `string` | **Required**. Remarks |

## Update Stock Entry

**PUT /api/stocks/${entryId}**

Parameter | Type | Description
--- | --- | ---
entryId | integer | Required. Id of stock entry to update
warehouse | integer | Required. Id of warehouse
bay | integer | Required. Id of bay
owner | integer | Required. Id of owner
garden | integer | Required. Id of garden
grade | integer | Required. Id of grade
packageType | integer | Required. Id of package type
invoice | string | Required. Invoice number
packageNumber | integer | Required. Number of packages
yearOfManufacture | integer | Required. Year of manufacture (four-digit number)
remarks | string | Required. Remarks

## Get Entry Count

**GET /api/stocks/count/${uid}**

Parameter | Type | Description
--- | --- | ---
uid | integer | Required. Id of user to fetch entry count for

## Get Recent Entries

**GET /api/stocks/recent/${uid}**

Parameter | Type | Description
--- | --- | ---
uid | integer | Required. Id of user to fetch recent entries for

## Get Last 30 Days Entries

**GET /api/stocks/last-30-days/${uid}**

Parameter | Type | Description
--- | --- | ---
uid | integer | Required. Id of user to fetch entries from the last 30 days
```

## Features of the web interface:
1. can create/register new users.
    - Can customize warehouses by adding names, partitions, and product details. In this case, the product is Tea, and its  details include Tea Producer name, tea farm name, tea grade name, etc., which are associated with stock taking.
    - Can perform stock reconciliation.
    - Can generate reports on various tea stock taking activities.
    - Can view stock taken and the clerk who performed the stock taking.
    - Can view users who have taken stock.
    - Can activate or deactivate the signup of any user/stock taking clerk who uses the mobile application by generating authentication tokens.
    - Can view real-time total quantities of bags/products from the stock taken.
    - Can view totals of items from the stock by filtering by product details, warehouse partitions, warehouse name, etc.
    - Can view the number of stock entries made by each stock taking clerk.

### Setting up and running the application:
Assuming you have already set up the Laravel environment (if not, please refer to the documentation at laravel.com for guidance), follow these steps:

    - Clone this repository using Git.
    - Create the .env file and configure the database settings.
    - Run 'composer install' to download the required packages and libraries.
    - Run 'php artisan migrate' to set up the database migrations.
    - Run 'php artisan key:generate' to generate the application key.
    - Run 'php artisan db:seed' to seed the database.
    - Finally, run 'php artisan serve' to start the application. The application will run on port 8000. Open your browser and enter 'http://127.0.0.1:8000/'.

#### Trial login details can be found in the 'UserSeeder.php' file located in the '/database/seeds' directory.
I hope this information is helpful to you in some way. If you would like to collaborate, please reach out to me at ianyakundi015@gmail.com.

