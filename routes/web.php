<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StockController;
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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('dashboard', function () {
    return view('layouts.master');
});

// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('warehouses', 'WarehouseController');
    Route::get('/apiWarehouses', 'WarehouseController@apiWarehouses')->name('api.warehouses');
    Route::get('/warehouses/create', 'WarehouseController@create')->name('warehouses.create');
    Route::post('/warehouses', 'WarehouseController@store')->name('warehouses.store');

    Route::post('/toggleTokenActivation', 'Auth\LoginController@toggleTokenActivation')->name('admin.toggleTokenActivation');

    Route::post('/generateTokens', 'Auth\LoginController@generateTokens')->name('admin.generateTokens');

    Route::resource('legacies', 'LegacyController');
    Route::get('/apiLegacies', 'LegacyController@apiLegacies')->name('api.legacies');

    // Import route for legacies
    Route::post('/store', 'LegacyController@store')->name('api.imports');

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
    Route::match(['get', 'post'], '/exportExcel', 'StockController@exportExcel')->name('exportExcel.stockAll');

    Route::post('/apiImport', 'StockController@apiImport')->name('api.import');
    Route::post('/stocks/import', 'StockController@import')->name('stocks.import');

    Route::get('/stock/reports', 'StockController@generateMonthlyReports')->name('stocks.reports');

    Route::get('/reconcileStockExport', 'StockController@reconcileStockExport')->name('reconcileStockExport');

    Route::get('/reconcileStock', 'StockController@reconcileStock')->name('reconcileStock');
    Route::get('/reconcileStock', 'StockController@reconcileStock')->name('reconciliation.index');

    Route::get('/stock/export/{warehouse_id}', [StockController::class, 'exportToExcel'])->name('stock.export');

    Route::get('stocks/total-bags', 'StockController@countTotalBags');

    // Route::get('reports', [StockController::class, 'index'])->name('stocks.reports');
    // // Route::get('reports/api', [StockController::class, 'apiReports'])->name('api.reports');
    // // Route::resource('reports', 'StockController');
    // Route::get('reports/api', 'StockController@apiReports')->name('api.reports');

    Route::resource('user', 'UserController');
    Route::get('/apiUser', 'UserController@apiUsers')->name('api.users');
    Route::delete('/user/{id}', 'UserController@destroy')->name('user.destroy');
});
