<?php

namespace App\Http\Controllers;

use App\Http\Requests\Grade\StoreGradeRequest;
use App\Http\Requests\Grade\UpdateGradeRequest;
use App\Models\Grade;
use Illuminate\View\View;
use Yajra\DataTables\Datatables;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $grades = Grade::all();

        return view('grades.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGradeRequest $request)
    {
        Grade::create($request->validated());

        return response()->json([
           'success' => true,
           'message' => 'Grade Created',
        ]);
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
        $grade = Grade::find($id);

        if($grade) {
            return response()->json($grade, 200);
        } else {
            return response()->json(['error' => 'Grade Not Found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGradeRequest $request, $id)
    {

        $grade = Grade::findOrFail($id);

        $grade->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Grade Updated',
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
        Grade::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Grade Deleted',
        ]);
    }

    public function apiGrades()
    {
        $grades = Grade::all();

        return Datatables::of($grades)
            ->addColumn('action', function ($grades) {
                return '<a onclick="editForm('.$grades->id.')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> '.
                    '<a onclick="deleteData('.$grades->id.')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['action'])->make(true);
    }
}
