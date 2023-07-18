<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSkillController extends Controller
{
    public function index()
    {
        $skills = Skill::all();

    foreach ($skills as $skill) {
        $skill->in_use = DB::table('seeker_skills')
            ->where('skill_id', $skill->id)
            ->orWhereExists(function ($query) use ($skill) {
                $query->select(DB::raw(1))
                    ->from('job_skills')
                    ->whereRaw('job_skills.skill_id = seeker_skills.skill_id')
                    ->where('job_skills.skill_id', $skill->id);
            })
            ->exists();
    }

    return view('admin.settings.skills', compact('skills'));;
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $request->validate([
            'skill' => 'required|max:255',
        ]);

        $skill = Skill::create($request->all());

        return redirect()->back();
    }

    public function show(Skill $skill)
    {
        return response()->json($skill);
    }

    public function edit(Skill $skill)
    {
    }

    public function update(Request $request, Skill $skill)
    {
        $request->validate([
            'skill' => 'required|max:255',
        ]);

        $skill->update($request->all());

        return redirect()->back();
    }

   

     public function delete(Request $request, $id)
{
    $skill = Skill::findOrFail($id);
    $skill->delete();

    // Redirect back to the page
    return redirect()->back();
}
}
