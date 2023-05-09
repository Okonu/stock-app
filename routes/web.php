<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('dashboard', function () {
    return view('layouts.master');
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('packages', 'PackageController');
    Route::get('/apiPackages', 'PackageController@apiPackage')->name('api.packages');

    Route::resource('customers', 'CustomerController');
    Route::get('/apiCustomers', 'CustomerController@apiCustomers')->name('api.customers');
    Route::post('/importCustomers', 'CustomerController@ImportExcel')->name('import.customers');
    Route::get('/exportCustomersAll', 'CustomerController@exportCustomersAll')->name('exportPDF.customersAll');
    Route::get('/exportCustomersAllExcel', 'CustomerController@exportExcel')->name('exportExcel.customersAll');

    Route::resource('warehouses', 'WarehouseController');
    Route::get('/apiWarehouses', 'WarehouseController@apiWarehouses')->name('api.warehouses');
    Route::get('/exportWarehousesAll', 'WarehouseController@exportWarehousesAll')->name('exportPDF.warehousesAll');
    Route::get('/exportWarehousesAllExcel', 'WarehouseController@exportExcel')->name('exportExcel.warehousesAll');

    Route::resource('bays', 'BayController');
    Route::get('/apiBays', 'BayController@apiBays')->name('api.bays');

    Route::resource('owners', 'OwnerController');
    Route::get('/apiOwners', 'OwnerController@apiOwners')->name('api.owners');

    Route::resource('stock', 'StockController');
    Route::get('apiStock', 'StockController@apiStock')->name('api.stock');

    Route::resource('user', 'UserController');
    Route::get('/apiUser', 'UserController@apiUsers')->name('api.users');
});
