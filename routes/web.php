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

Route::get('/', function () {return view('welcome'); });

Route::get('/dispplay/warehouse', function () {return view('display.warehouse'); });

Route::get('/dispplay/owners', function () {return view('display.owners'); });

Route::get('/dispplay/garden', function () {return view('display.garden'); });

Route::get('/display/bays', function () {return view('display.bays'); });

Route::get('/dispplay/grade', function () {return view('display.grade'); });

Route::get('/dispplaypackage', function () {return view('display.package'); });

Route::get('/dispplaystaff', function () {return view('display.staff'); });
