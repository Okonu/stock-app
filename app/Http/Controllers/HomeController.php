<?php

namespace App\Http\Controllers;

use App\Warehouse;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void<>?
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stockController = new StockController();

        $totalBags = $stockController->countTotalBags();
        $bagsPerWarehouse = $stockController->calculateBagsPerWarehouse();
        $warehouse = Warehouse::pluck('name', 'id');

        return view('home', compact('totalBags', 'bagsPerWarehouse', 'warehouse'));
    }
}
