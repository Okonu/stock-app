<?php

use Illuminate\Support\Facades\Route;

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

// uncomment from here

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
    Route::get('/warehouses/create', 'WarehouseController@create')->name('warehouses.create');
    Route::post('/warehouses', 'WarehouseController@store')->name('warehouses.store');

    // Route::resource('legacies', 'LegacyController');
    // Route::get('/apiLegacies', 'LegacyController@apiLegacies')->name('api.legacies');
    // Route::post('/importLegacies', 'LegacyController@ImportExcel')->name('import.legacies');

    Route::resource('owners', 'OwnerController');
    Route::get('/apiOwners', 'OwnerController@apiOwners')->name('api.owners');

    Route::resource('gardens', 'GardenController');
    Route::get('/apiGardens', 'GardenController@apiGardens')->name('api.gardens');

    Route::resource('packages', 'PackageController');
    Route::get('/apiPackages', 'PackageController@apiPackages')->name('api.packages');

    Route::resource('grades', 'GradeController');
    Route::get('/apiGrades', 'GradeController@apiGrades')->name('api.grades');

    Route::resource('bays', 'WarehouseController');
    Route::get('/apiBays', 'WarehouseController@apiBays')->name('api.bays');
    Route::post('/apiBays', 'WarehouseController@apiBays')->name('api.bays');

    Route::resource('stocks', 'StockController');
    Route::get('/apiStocks', 'StockController@apiStocks')->name('api.stocks');
    Route::get('/exportStockAllExcel', 'StockController@exportExcel')->name('exportExcel.stockAll');
    Route::post('/apiImport', 'StockController@apiStocks')->name('api.import');
    Route::post('/stocks/import', 'StockController@import')->name('stocks.import');

    Route::get('stocks/total-bags', 'StockController@countTotalBags');

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
