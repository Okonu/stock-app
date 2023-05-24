<?php

namespace App\Http\Controllers;

use App\Exports\ExportStock;
use App\Garden;
use App\Grade;
use App\Imports\StockImport;
use App\Owner;
use App\Package;
use App\Stock;
use App\User;
use App\Warehouse;
use App\WarehouseBay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }

    public function countTotalBags()
    {
        $totalBags = Stock::sum('qty');

        return $totalBags;
    }

    public function calculateBagsPerWarehouse()
    {
        $bagsPerWarehouse = Stock::select('warehouse_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('warehouse_id')
            ->pluck('total_qty', 'warehouse_id');

        return $bagsPerWarehouse;
    }

    public function index()
    {
        $totalBags = $this->countTotalBags();

        $bagsPerWarehouse = $this->calculateBagsPerWarehouse();

        $user = User::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $warehouse = Warehouse::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $bay = WarehouseBay::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $owner = Owner::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $garden = Garden::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $grade = Grade::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $package = Package::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');
        $stock = Stock::all();

        return view('stocks.index', compact('user', 'warehouse', 'bay', 'owner', 'garden', 'grade', 'package', 'totalBags', 'bagsPerWarehouse'));
    }

    public function create()
    {
        // Create
    }

    public function store(Request $request)
    {
        $warehouse = Warehouse::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $bay = WarehouseBay::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $owner = Owner::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $garden = Garden::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $grade = Grade::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $package = Package::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $this->validate($request, [
            'warehouse_id' => 'required',
            'warehouse_bay_id' => 'required',
            'owner_id' => 'required',
            'garden_id' => 'required',
            'grade_id' => 'required',
            'package_id' => 'required',
            'invoice' => 'required|string',
            'qty' => 'required|integer',
            'year' => 'required|string',
            'remark' => 'required|string',
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $this->validate($request, [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $fileName = time().'_'.$file->getClientOriginalName();

        // Save the uploaded file to the 'uploads' disk
        Storage::disk('uploads')->put($fileName, file_get_contents($file));

        // Get the full path of the saved file
        $filePath = Storage::disk('uploads')->path($fileName);

        // Read the Excel file
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        // Initialize arrays for storing mismatched data
        $mismatches = [];
        $totalMismatchQty = 0;

        // Iterate through each row in the Excel file
        for ($row = 2; $row <= $highestRow; ++$row) {
            $gardenName = $worksheet->getCell('A'.$row)->getValue();
            $invoice = $worksheet->getCell('B'.$row)->getValue();
            $qty = $worksheet->getCell('C'.$row)->getValue();
            $gradeName = $worksheet->getCell('D'.$row)->getValue();
            $packageType = $worksheet->getCell('E'.$row)->getValue();

            // Get the garden, grade, and package by their names
            $garden = Garden::where('name', $gardenName)->first();
            $grade = Grade::where('name', $gradeName)->first();
            $package = Package::where('type', $packageType)->first();

            // If any of the required data is missing, skip the row
            if (!$garden || !$grade || !$package) {
                continue;
            }

            // Compare the imported data with the invoiced stock
            $invoicedQty = Stock::where('garden_id', $garden->id)
                ->where('invoice', $invoice)
                ->where('grade_id', $grade->id)
                ->where('package_id', $package->id)
                ->sum('qty');

            if ($qty != $invoicedQty) {
                $mismatches[] = [
                    'garden' => $gardenName,
                    'invoice' => $invoice,
                    'qty' => $qty,
                    'grade' => $gradeName,
                    'package' => $packageType,
                    // 'invoiced_qty' => $invoicedQty,
                ];
                $totalMismatchQty += abs($qty - $invoicedQty);
            }
        }

        // Return the response with the mismatched data
        return response()->json([
            'success' => true,
            'message' => 'Stock validation complete',
            'mismatches' => $mismatches,
            'total_mismatch_qty' => $totalMismatchQty,
        ]);
    }

    // public function store(Request $request)
    // {
    //     // Retrieve necessary data (warehouses, bays, owners, gardens, grades, packages)
    //     $warehouse = Warehouse::orderBy('name', 'ASC')->get()->pluck('name', 'id');
    //     $bay = Bay::orderBy('name', 'ASC')->get()->pluck('name', 'id');
    //     $owner = Owner::orderBy('name', 'ASC')->get()->pluck('name', 'id');
    //     $garden = Garden::orderBy('name', 'ASC')->get()->pluck('name', 'id');
    //     $grade = Grade::orderBy('name', 'ASC')->get()->pluck('name', 'id');
    //     $package = Package::orderBy('name', 'ASC')->get()->pluck('name', 'id');

    //     // Validate the incoming request data
    //     $this->validate($request, [
    //         'warehouse_id' => 'required',
    //         'bay_id' => 'required',
    //         'owner_id' => 'required',
    //         'garden_id' => 'required',
    //         'grade_id' => 'required',
    //         'package_id' => 'required',
    //         'invoice' => 'required|string',
    //         'qty' => 'required|string',
    //         'year' => 'required|string',
    //         'remark' => 'required|string',
    //     ]);

    //     // Read and process the uploaded Excel file
    //     $import = new StockImport();
    //     $import->import($request->file('file'));

    //     // Get the imported data
    //     $importedData = $import->getData();

    //     // Perform stock comparison and store mismatches
    //     $mismatches = [];

    //     foreach ($importedData as $rowNumber => $data) {
    //         // Compare the imported data with the invoiced stock
    //         $invoicedQty = DB::table('stocks')
    //             ->where('invoice', $data->invoice)
    //             ->where('year', $data->year)
    //             ->where('package_id', $data->package_id)
    //             ->where('garden_id', $data->garden_id)
    //             ->where('grade_id', $data->grade_id)
    //             ->sum('qty');

    //         if ($data->qty != $invoicedQty) {
    //             $mismatch = [
    //                 'row_number' => $rowNumber + 1, // Add 1 to match Excel row numbers
    //                 'invoice' => $data->invoice,
    //                 'year' => $data->year,
    //                 'package_id' => $data->package_id,
    //                 'garden_id' => $data->garden_id,
    //                 'grade_id' => $data->grade_id,
    //                 'imported_qty' => $data->qty,
    //                 'invoiced_qty' => $invoicedQty,
    //             ];

    //             $mismatches[] = $mismatch;
    //         }
    //     }

    //     // Return the mismatches or highlight them in your desired way
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Stock created successfully',
    //         'mismatches' => $mismatches,
    //     ]);
    // }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $stock = Stock::find($id);

        return $stock;
    }

    // public function update(Request $request, $id)
    // {
    //     $this->validate($request, [
    //         'warehouse_id' => 'required',
    //         'warehouse_bay_id' => 'required',
    //         'owner_id' => 'required',
    //         'garden_id' => 'required',
    //         'grade_id' => 'required',
    //         'package_id' => 'required',
    //         'invoice' => 'required|string',
    //         'qty' => 'required|integer',
    //         'year' => 'required|string',
    //         'remark' => 'required|string',
    //     ]);

    //     $stock = Stock::findOrFail($id);
    //     $stock->update($request->all());

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Stock Updated Successfully',
    //     ]);
    // }

    public function apiStocks()
    {
        $stock = Stock::all();

        return Datatables::of($stock)
            ->addColumn('user_name', function ($stock) {
                return $stock->user->name;
            })
            ->addColumn('warehouse_name', function ($stock) {
                return $stock->warehouse->name;
            })
            ->addColumn('bay_name', function ($stock) {
                return $stock->bay->name;
            })
            ->addColumn('owner_name', function ($stock) {
                return $stock->owner->name;
            })
            ->addColumn('garden_name', function ($stock) {
                return $stock->garden->name;
            })
            ->addColumn('grade_name', function ($stock) {
                return $stock->grade->name;
            })
            ->addColumn('package_name', function ($stock) {
                return $stock->package->name;
            })
            ->addColumn('action', function ($stock) {
                return '<a onclick="editForm('.$stock->id.')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i>Edit</a>'.
                    '<a onclick="deleteData('.$stock->id.')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i>Delete</a>';
            })
            ->rawColumns(['user_name', 'warehouse_name', 'bay_name', 'owner_name', 'garden_name', 'grade_name', 'package_name', 'action'])->make(true);
    }

    public function ImportExcel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Excel::import(new StockImport(), $file);

            return redirect()->back()->with(['success' => 'Uploaded file successfully !']);
        }

        return redirect()->back()->with(['error' => 'Please choose file to upload.']);
    }

    // public function exportStockAll()
    // {
    //     $stock = Stock::all();
    //     $pdf = \PDF::loadView('stocks.stockAllPDF', compact('stock'));

    //     return $pdf->download('stock.pdf');
    // }

    // public function exportStock($id)
    // {
    //     $stock = Stock::findOrFail($id);
    //     $pdf = \PDF::loadView('stocks.exportStockPDF', compact('stock'));

    //     return $pdf->download($stock->id.'_stock.pdf');
    // }

    public function exportExcel()
    {
        return (new ExportStock())->download('stock.xlsx');
    }
}
