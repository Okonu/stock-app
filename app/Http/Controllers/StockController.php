<?php

namespace App\Http\Controllers;

use App\Bay;
use App\Http\Controllers\UserController;
use App\Exports\ExportStock;
use App\Garden;
use App\Grade;
use App\Owner;
use App\Package;
use App\Stock;
use App\Warehouse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Imports\StockImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB; // Add this line to import the DB facade

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }

    public function countTotalBags()
    {
        $totalBags = Stock::sum('qty');
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

        $warehouse = Warehouse::orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        $bay = Bay::orderBy('name', 'ASC')
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

        $package = Package::orderBy('name', 'ASC');

        $user = auth()->id();

        $stocks = Stock::where('user_id')
            ->get();

        return view('stocks.index', compact(['stocks' => $stocks], 'warehouse', 'bay', 'owner', 'garden', 'grade', 'package', 'stocks',  'totalBags', 'bagsPerWarehouse'));
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

        $bay = Bay::orderBy('name', 'ASC')
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
            'bay_id' => 'required',
            'owner_id' => 'required',
            'garden_id' => 'required',
            'grade_id' => 'required',
            'package_id' => 'required',
            'invoice' => 'required|string',
            'qty' => 'required|string',
            'year' => 'required|string',
            'remark' => 'required|string',
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // $stock = new Stock();
        // $stock->user_id = auth()->id();
        // $stock->warehouse_id = $request->warehouse_id;
        // $stock->bay_id = $request->bay_id;
        // $stock->owner_id = $request->owner_id;
        // $stock->garden_id = $request->garden_id;
        // $stock->grade_id = $request->grade_id;
        // $stock->package_id = $request->package_id;
        // $stock->qty = $request->qty;
        // $stock->bag_no = $request->bag_no;
        // $stock->date = $request->date;
        // $stock->save();

        // Save the uploaded file
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $fileName);
        $filePath = public_path('uploads/' . $fileName);

        // Import and process the data
        $import = new StockImport;

        $import->import($filePath);

        $rows = $import->getRowCount();
        $failedRows = $import->getFailedRowCount();

        // Check if any rows failed during import
        if ($failedRows > 0) {
            // Delete the uploaded file if there were failed rows
            unlink($filePath);

            return redirect()->back()->with('error', 'Failed to import ' . $failedRows . ' rows. Please check your file and try again.');
        }

        // Process the imported data
        $data = $import->getData();

        foreach ($data as $row) {
            // Create a new stock record
            $stock = new Stock();
            $stock->warehouse_id = $request->warehouse_id;
            $stock->bay_id = $request->bay_id;
            $stock->owner_id = $request->owner_id;
            $stock->garden_id = $request->garden_id;
            $stock->grade_id = $request->grade_id;
            $stock->package_id = $request->package_id;
            $stock->invoice = $row['invoice'];
            $stock->qty = $row['qty'];
            $stock->year = $row['year'];
            $stock->remark = $row['remark'];
            $stock->save();
        }

        // Delete the uploaded file after successful import
        unlink($filePath);

        return redirect()->back()->with('success', 'Stock data imported successfully.');
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $fileName);
        $filePath = public_path('uploads/' . $fileName);

        try {
            Excel::import(new StockImport, $filePath);

            unlink($filePath);

            return redirect()->back()->with('success', 'Stock data imported successfully.');
        } catch (\Exception $e) {
            unlink($filePath);

            return redirect()->back()->with('error', 'Failed to import stock data. Please check your file and try again.');
        }
    }

    public function export()
    {
        return Excel::download(new ExportStock(), 'stock.xlsx');
    }

    

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        $warehouse = Warehouse::orderBy('name', 'ASC')->pluck('name', 'id');
        $bay = Bay::orderBy('name', 'ASC')->pluck('name', 'id');
        $owner = Owner::orderBy('name', 'ASC')->pluck('name', 'id');
        $garden = Garden::orderBy('name', 'ASC')->pluck('name', 'id');
        $grade = Grade::orderBy('name', 'ASC')->pluck('name', 'id');
        $package = Package::orderBy('name', 'ASC')->pluck('name', 'id');

        return view('stocks.edit', compact('stock', 'warehouse', 'bay', 'owner', 'garden', 'grade', 'package'));

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'warehouse_id' => 'required',
            'bay_id' => 'required',
            'owner_id' => 'required',
            'garden_id' => 'required',
            'grade_id' => 'required',
            'package_id' => 'required',
            'invoice' => 'required|string',
            'qty' => 'required|string',
            'year' => 'required|string',
            'remark' => 'required|string',
        ]);

        $stock = Stock::findOrFail($id);
        $stock->warehouse_id = $request->warehouse_id;
        $stock->bay_id = $request->bay_id;
        $stock->owner_id = $request->owner_id;
        $stock->garden_id = $request->garden_id;
        $stock->grade_id = $request->grade_id;
        $stock->package_id = $request->package_id;
        $stock->invoice = $request->invoice;
        $stock->qty = $request->qty;
        $stock->year = $request->year;
        $stock->remark = $request->remark;
        $stock->save();

        return redirect()->route('stocks.index')->with('success', 'Stock updated successfully.');
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Stock deleted successfully.');
    }

    public function apiStocks(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('stocks')
                ->join('warehouses', 'stocks.warehouse_id', '=', 'warehouses.id')
                ->join('bays', 'stocks.bay_id', '=', 'bays.id')
                ->join('owners', 'stocks.owner_id', '=', 'owners.id')
                ->join('gardens', 'stocks.garden_id', '=', 'gardens.id')
                ->join('grades', 'stocks.grade_id', '=', 'grades.id')
                ->join('packages', 'stocks.package_id', '=', 'packages.id')
                ->select('stocks.id', 'warehouses.name as warehouse', 'bays.name as bay', 'owners.name as owner', 'gardens.name as garden', 'grades.name as grade', 'packages.name as package', 'stocks.invoice', 'stocks.qty', 'stocks.year', 'stocks.remark', 'stocks.mismatch')
                ->orderBy('stocks.id', 'DESC')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->mismatch) {
                        return '<span class="text-danger">Mismatched</span>';
                    } else {
                        return '<span class="text-success">Matched</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                    $btn .= ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }
}