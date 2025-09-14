<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\SkillProgress;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skill::latest()->paginate(10);
        return view('skills.index', compact('skills'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('skills.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Skill::create($validated);

        return redirect()->route('skills.index')->with('status', 'スキルを登録しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill)
    {
        $skill->load(['progresses' => function ($q) {
            $q->latest('progress_date')->latest('id');
        }]);
        return view('skills.show', compact('skill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Skill $skill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Skill $skill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill)
    {
        //
    }

    public function storeProgress(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'progress_date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);

        $validated['skill_id'] = $skill->id;
        SkillProgress::create($validated);

        return redirect()->route('skills.show', $skill)->with('status', '進捗を追加しました');
    }
}
