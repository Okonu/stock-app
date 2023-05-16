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
    Route::resource('warehouses', 'WarehouseController');
    Route::get('/apiWarehouses', 'WarehouseController@apiWarehouses')->name('api.warehouses');

    Route::resource('owners', 'OwnerController');
    Route::get('/apiOwners', 'OwnerController@apiOwners')->name('api.owners');

    Route::resource('gardens', 'GardenController');
    Route::get('/apiGardens', 'GardenController@apiGardens')->name('api.gardens');

    Route::resource('packages', 'PackageController');
    Route::get('/apiPackages', 'PackageController@apiPackages')->name('api.packages');

    Route::resource('grades', 'GradeController');
    Route::get('/apiGrades', 'GradeController@apiGrades')->name('api.grades');

    Route::resource('bays', 'BayController');
    Route::get('/apiBays', 'BayController@apiBays')->name('api.bays');

    Route::resource('stocks', 'StockController');
    Route::get('/apiStocks', 'StockController@apiStocks')->name('api.stocks');
    Route::get('/exportStockAll', 'StockController@exportStockAll')->name('exportPDF.stockAll');
    Route::get('/exportStockAllExcel', 'StockController@exportExcel')->name('exportExcel.stockAll');
    Route::get('/exportStock/{id}', 'StockController@exportStock')->name('exportPDF.stock');

    Route::resource('user', 'UserController');
    Route::get('/apiUser', 'UserController@apiUsers')->name('api.users');
});

// Route::get('/', function () {
//     return view('auth.login');
// });

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// Route::get('dashboard', function () {
//     return view('layouts.master');
// });

// Route::group(['middleware' => 'auth'], function () {
//     Route::resource('packages', 'PackageController');
//     Route::resource('grades', 'GradeController');
//     Route::resource('gardens', 'GardenController');
//     Route::resource('warehouses', 'WarehouseController');
//     Route::resource('bays', 'BayController');
//     Route::resource('owners', 'OwnerController');
//     Route::resource('user', 'UserController');
// });
