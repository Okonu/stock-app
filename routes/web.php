<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// routes/web.php

Route::get('admin/login', [App\Http\Controllers\AdminController::class, 'showLoginForm'])->name('admin.login');

Route::post('admin/login', [App\Http\Controllers\AdminController::class, 'loginSubmit'])->name('admin.login.submit');

Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'showDashboard'])->name('admin.dashboard');

Route::post('admin/create', [App\Http\Controllers\AdminController::class, 'createAdminUser'])->name('admin.createAdminUser');

// Routes for user management
Route::middleware(['auth'])->group(function () {
    // Display user management dashboard
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Display form for creating a new user account
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');

    // Store new user account in the database
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    // Display form for editing an existing user account
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

    // Update an existing user account in the database
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // Delete an existing user account from the database
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
