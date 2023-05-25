<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Legacy;
use Yajra\DataTables\DataTables;
use Excel;
use App\Imports\LegaciesImport;

class LegacyController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $legacies = Legacy::all();
        
        return view('legacies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'garden' => 'required',
            'invoice' => 'required',
            'qty' => 'required',
            'grade' => 'required',
            'package' => 'required',
        ]);

        Legacy::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Imported Successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Legacy::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Successfully deleted'
        ]);
    }

    public function apiLegacies()
    {
        $legacy = Legacy::all();

        return Datatables::of($legacy)
            ->addColumn('action', function($legacy){
                return '<a onclick="deleteData('.$legacy->id.')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['action'])->make(true);
    }

    public function ImportExcel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Excel::import(new LegaciesImport, $file);
            return redirect()->back()->with(['success' => 'Uploaded file successfully !']);
        }

        return redirect()->back()->with(['error' => 'Please choose file to upload.']);
    }
}
