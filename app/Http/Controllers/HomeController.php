<?php

namespace App\Http\Controllers;

use App\ImportedData;
use App\Warehouse;
use App\ImportedData;
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
        $importedData = new ImportedData();
<<<<<<< HEAD
        // $totalMismatchQty= $stockController->calculateTotalMismatchQty($importedData);

=======
        $totalMismatchQty= $stockController->calculateTotalMismatchQty($importedData);
    
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
        $warehouse = Warehouse::pluck('name', 'id');

        return view('home', compact('totalBags', 'bagsPerWarehouse', 'totalMismatchQty', 'warehouse'));
    }
}


// namespace App\Http\Controllers;

// use App\Warehouse;
// use App\Http\Controllers\StockController;

// class HomeController extends Controller
// {
//     /**
//      * Create a new controller instance.
//      *
//      * @return void<>?
//      */
//     public function __construct()
//     {
//         $this->middleware('auth');
//     }

//     /**
//      * Show the application dashboard.
//      *
//      * @return \Illuminate\Contracts\Support\Renderable
//      */
//     public function index()
//     {
//         $stockController = new StockController();

//         $totalBags = $stockController->countTotalBags();
//         $bagsPerWarehouse = $stockController->calculateBagsPerWarehouse();
//         $totalMismatchQty = $stockController->apiImport(request()); // Pass the request object to the apiImport method
//         $warehouse = Warehouse::pluck('name', 'id');

//         return view('home', compact('totalBags', 'bagsPerWarehouse', 'totalMismatchQty', 'warehouse'));
//     }
// }
