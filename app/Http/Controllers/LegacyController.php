<?php

namespace App\Http\Controllers;

use App\Http\Requests\Legacy\ImportLegacyRequest;
use App\Imports\LegaciesImport;
use App\Models\Legacy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class LegacyController extends Controller
{
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
    public function store(ImportLegacyRequest $request): RedirectResponse
    {
        $file = $request->file('file');
    
        $import = new LegaciesImport();
    
        try {
            Excel::import($import, $file);
    
            return redirect()->back()->with('success', 'Import successful.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
    
            return redirect()->back()->with('error', 'Failed to import data.');
        }
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
