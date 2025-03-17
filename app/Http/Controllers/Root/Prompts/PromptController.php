<?php

namespace App\Http\Controllers\Root\Prompts;

use App\Http\Controllers\Controller;
use App\Models\Prompts\Prompt;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PromptController extends Controller
{
    public function index()
    {
        return Inertia::render('Root/Prompts/Index', [
            'prompts' => Prompt::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Root/Prompts/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);

        Prompt::create([
            'name' => $request->name,
            'description' => $request->description,
            'content' => $request->content,
        ]);

        return redirect()->route('root.prompts.index');
    }

    public function show(Prompt $prompt)
    {
        return Inertia::render('Root/Prompts/Show', [
            'prompt' => $prompt,
        ]);
    }

    public function edit(Prompt $prompt)
    {
        return Inertia::render('Root/Prompts/Edit', [
            'prompt' => $prompt,
        ]);
    }

    public function update(Request $request, Prompt $prompt)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);

        $prompt->update([
            'name' => $request->name,
            'description' => $request->description,
            'content' => $request->content,
        ]);

        return redirect()->route('root.prompts.index');
    }

    public function destroy(Prompt $prompt)
    {
        $prompt->delete();

        return redirect()->route('root.prompts.index');
    }
}
