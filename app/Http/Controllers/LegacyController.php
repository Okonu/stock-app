<?php

namespace App\Http\Controllers;

use App\Imports\LegaciesImport;
use App\Models\Legacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class LegacyController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:admin,staff');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $legacies = Legacy::all();

        return view('legacies.index', compact('legacies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiImports(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $file = $request->file('file');

        $import = new LegaciesImport();
        // dd($file);
        Excel::import($import, $file);

        return redirect()->back()->with('success', 'Import successful.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'garden' => 'required',
            'invoice' => 'required',
            'qty' => 'required',
            'grade' => 'required',
            'package' => 'required',
        ]);

        if ($validator->fails()) {
            Log::error($validator->errors());

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $legacy = new Legacy();
        $legacy->garden = $request->input('garden');
        $legacy->invoice = $request->input('invoice');
        $legacy->qty = $request->input('qty');
        $legacy->grade = $request->input('grade');
        $legacy->package = $request->input('package');

        $legacy->save();

        return response()->json([
            'success' => true,
            'message' => 'Legacy created successfully',
            'data' => $legacy,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Legacy::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Successfully deleted',
        ]);
    }

    public function apiLegacies()
    {
        $legacy = Legacy::query();

        return DataTables::of($legacy)
            ->addColumn('action', function ($legacy) {
                return '<a onclick="deleteData('.$legacy->id.')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
