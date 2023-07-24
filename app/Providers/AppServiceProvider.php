<?php

namespace App\Providers;

use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        View::composer(['home', 'warehouse', 'bags', 'layouts', 'sidebar', 'stocks'], function ($view) {
            $stockController = app(StockController::class);
            $totalBags = $stockController->countTotalBags();
            $bagsPerWarehouse = $stockController->calculateBagsPerWarehouse();

            $view->with('totalBags', $totalBags)->with('bagsPerWarehouse', $bagsPerWarehouse);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
