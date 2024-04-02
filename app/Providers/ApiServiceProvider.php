<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        require_once base_path('api/classes/API.php'); // Adjust the path as necessary

        $this->app->singleton('API', function ($app) {
            $pdo = DB::connection()->getPdo();
            return new API($pdo, '');
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
