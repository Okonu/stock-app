<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GardenController;

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

$config = config('database.connections.mysql');
$dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
$pdo = new PDO($dsn, $config['username'], $config['password']);

require_once base_path('api/classes/API.php');

$api = new API($pdo, '');

Route::middleware('api')->group(function () use ($api) {
    Route::post('/login', function (Request $request) use ($api) {
        $data = $request->all();
        $result = $api->login($data);
        return response()->json($result);
    });

    Route::post('/record-stock-entry', function (Request $request) use ($api) {
        $data = $request->all();
        $result = $api->record_stock_entry($data);
        return response()->json($result);
    });

    Route::get('/get-warehouses-with-bays/{warehouseId}', function ($warehouseId) use ($api) {
        $result = $api->getWarehousesWithBays($warehouseId);
        return response()->json($result);
    });

    Route::get('/get-bays-and-warehouse', function () use ($api) {
        $result = $api->get_bays_and_warehouse();
        return response()->json($result);
    });

    Route::get('/get-warehouse', function () use ($api) {
        $result = $api->get_warehouse();
        return response()->json($result);
    });

    Route::get('/get-bays/{wid}', function ($wid) use ($api) {
        $result = $api->get_bays($wid);
        return response()->json($result);
    });

    Route::get('/get-owners', function () use ($api) {
        $result = $api->get_owners();
        return response()->json($result);
    });

    Route::get('/get-gardens/{owner_id?}', function ($owner_id = '') use ($api) {
        $result = $api->get_gardens($owner_id);
        return response()->json($result);
    });

    Route::get('/get-grades', function () use ($api) {
        $result = $api->get_grades();
        return response()->json($result);
    });

    Route::get('/get-packages', function () use ($api) {
        $result = $api->get_packages();
        return response()->json($result);
    });

    Route::post('/update-stock-entry', function (Request $request) use ($api) {
        $data = $request->all();
        $result = $api->update_stock_entry($data);
        return response()->json($result);
    });

    Route::get('/get-entry-count/{uid}', function ($uid) use ($api) {
        $result = $api->get_entry_count($uid);
        return response()->json($result);
    });

    Route::get('/get-recent-entries/{uid}', function ($uid) use ($api) {
        $result = $api->get_recent_entries($uid);
        return response()->json($result);
    });

    Route::get('/get-last-30days-entries/{uid}', function ($uid) use ($api) {
        $result = $api->get_last_30days_entries($uid);
        return response()->json($result);
    });
});

//for testing purposes only
Route::middleware('auth:api')->group(function () {
    Route::get('/gardens', [GardenController::class, 'index']);
    Route::post('/gardens', [GardenController::class, 'store']);
    Route::get('/gardens/{garden}', [GardenController::class, 'show']);
    Route::put('/gardens/{garden}', [GardenController::class, 'update']);
    Route::delete('/gardens/{garden}', [GardenController::class, 'destroy']);
});

