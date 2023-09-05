<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



// Route::group(['prefix' => 'v1'], function() {
//     Route::apiResource('stocks', StockController::class);
//     Route::apiResource('owners', OwnerController::class);
//     Route::apiResource('warehouse', WarehouseController::class);
//     Route::apiResource('gardens', GardenController::class);
//     Route::apiResource('packages', PackageController::class);
//     Route::apiResource('grades', GradeController::class);
//     Route::apiResource('users', UserController::class);

// });
