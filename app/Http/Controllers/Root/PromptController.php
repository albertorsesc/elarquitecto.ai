<?php

namespace App\Http\Controllers\Root;

use App\Enums\PromptCategoryEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\PromptRequest;
use App\Models\Category;
use App\Models\Prompt;
use App\Models\Tag;
use Inertia\Inertia;

class PromptController extends Controller
{
    public function index()
    {
        return Inertia::render('Root/Prompts/Index', [
            'prompts' => Prompt::with(['category', 'tags'])->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Root/Prompts/Create', [
            'models' => collect(config('models.models'))->map(fn ($models, $provider) => $models),
            'categories' => Category::with('tags')->get(),
            'tags' => Tag::all(),
        ]);
    }

    public function store(PromptRequest $request)
    {
        $prompt = Prompt::create($request->validated());

        return redirect()->route('root.prompts.index')->with('success', 'Prompt created successfully');
    }

    public function show(Prompt $prompt)
    {
        return Inertia::render('Root/Prompts/Show', [
            'prompt' => $prompt->load(['category', 'tags']),
        ]);
    }

    public function edit(Prompt $prompt)
    {
        return Inertia::render('Root/Prompts/Edit', [
            'prompt' => $prompt->load(['category', 'tags']),
            'models' => collect(config('models.models'))->map(fn ($models, $provider) => $models),
            'categories' => Category::with('tags')->get(),
            'tags' => Tag::with('category')->get(),
        ]);
    }

    public function update(PromptRequest $request, Prompt $prompt)
    {
        $prompt->update($request->validated());

        return redirect()->route('root.prompts.show', $prompt)->with('success', 'Prompt updated successfully');
    }

    public function destroy(Prompt $prompt)
    {
        $prompt->delete();

        return redirect()->route('root.prompts.index')->with('success', 'Prompt deleted successfully');
    }
}
