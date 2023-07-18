<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::all();

        return response()->json($skills);
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

        return response()->json($skill, 201);
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

        return response()->json($skill);
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return response()->json(['message' => 'Skill deleted successfully']);
    }
}
