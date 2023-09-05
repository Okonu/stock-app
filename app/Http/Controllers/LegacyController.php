<?php

namespace App\Http\Controllers;

use App\Imports\LegaciesImport;
use App\Legacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

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

        return view('legacies.index', compact('legacies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

     /**
      * Store a newly created resource in storage.
      *
      * @return \Illuminate\Http\Response
      */
<<<<<<< HEAD

    public function apiImports(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
            
        ]);
    
        $file = $request->file('file');
    
        $import = new LegaciesImport(); // Create an instance of the import class
        // dd($file); 
        Excel::import($import, $file); // Pass the import instance to the import method
    
        return redirect()->back()->with('success', 'Import successful.');
    }

//////
=======
     public function apiImports(Request $request)
     {
         $this->validate($request, [
             'file' => 'required|mimes:xls,xlsx',
         ]);

         if ($request->hasFile('file')) {
             try {
                 $file = $request->file('file');
                 $rows = Excel::toArray(new LegaciesImport(), $file);

                 // Iterate over each chunk of rows

                 foreach ($rows[0] as $row) {
                     // Validate and process each row individually

                     $validator = Validator::make($row, [
                         'Garden' => 'required',
                         'Invoice' => 'required',
                         'Balance Qty' => 'required',
                         'Grade' => 'required',
                         'Package Type' => 'required',
                     ]);

                     if ($validator->fails()) {
                         // Log or handle the validation errors
                         Log::error($validator->errors());
                         continue; // Skip to the next row
                     }
                     
                     // Create a new Legacy instance and assign the data from the row

                     $legacy = new Legacy([
                         'garden' => $row['Garden'],
                         'invoice' => $row['Invoice'],
                         'qty' => $row['Balance Qty'],
                         'grade' => $row['Grade'],
                         'package' => $row['Package Type'],
                     ]);

                     // Save the legacy to the database
                     $legacy->save();
                 }

                 return redirect()->back()->with(['success' => 'Uploaded file successfully!']);
             } catch (\Exception $e) {
                 return redirect()->back()->with(['error' => 'An error occurred while importing the file: '.$e->getMessage()]);
             }
         }

         return redirect()->back()->with(['error' => 'Please choose a file to upload.']);
     }

>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'garden' => 'required',
            'invoice' => 'required',
            'qty' => 'required',
            'grade' => 'required',
            'package' => 'required',
        ]);

        // Check if there are validation errors
        if ($validator->fails()) {
            // Log or display the validation errors
            Log::error($validator->errors());
            // Or you can return a response with the errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new Legacy instance and assign the validated data
        $legacy = new Legacy();
        $legacy->garden = $request->input('garden');
        $legacy->invoice = $request->input('invoice');
        $legacy->qty = $request->input('qty');
        $legacy->grade = $request->input('grade');
        $legacy->package = $request->input('package');

        // Save the legacy to the database
        $legacy->save();

        return response()->json([
            'success' => true,
            'message' => 'Legacy created successfully',
            'data' => $legacy,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
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
