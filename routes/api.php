<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/packages', 'ApiController@apiPackages');
//     Route::get('/gardens', 'ApiController@apiGardens');
//     Route::get('/grades', 'ApiController@apiGrades');
//     Route::get('/warehouses', 'ApiController@apiWarehouses');
//     Route::get('/bays', 'ApiController@apiBays');
//     Route::get('/owners', 'ApiController@apiOwners');
//     Route::get('/users', 'ApiController@apiUsers');
// });
