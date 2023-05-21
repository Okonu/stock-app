<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Stock;
use App\Warehouse;
use App\Http\Controllers\StockController;

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
     
         View::composer(['home', 'bags', 'layouts', 'sidebar', 'stocks'], function ($view) {
             $stockController = new StockController();
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
        //
    }
}
