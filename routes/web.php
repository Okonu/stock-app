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

Route::get('/warehouse', function () {return view('warehouse'); })->name('warehouse');

Route::get('/owners', function () {return view('owners'); })->name('owners');

Route::get('/garden', function () {return view('garden'); })->name('garden');

Route::get('/bays', function () {return view('bays'); })->name('bays');

Route::get('/grade', function () {return view('grade'); })->name('grade');

Route::get('package', function () {return view('package'); })->name('package');

Route::get('staff', function () {return view('staff'); })->name('staff');
