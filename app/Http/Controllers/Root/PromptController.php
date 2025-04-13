<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromptRequest;
use App\Models\Prompt;
use Illuminate\Http\Request;
use Inertia\Inertia;
class PromptController extends Controller
{
    public function create()
    {
        return Inertia::render('Root/Prompts/Create', [
            'models' => collect(config('models.models'))->map(function ($models, $provider) {
                return [
                    'provider' => $provider,
                    'models' => $models,
                ];
            }),
        ]);
    }

    public function store(PromptRequest $request)
    {
        Prompt::create($request->validated());

        return redirect()->route('root.prompts.index')->with('success', 'Prompt created successfully');
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

    public function update(PromptRequest $request, Prompt $prompt)
    {
        $prompt->update($request->validated());

        return redirect()->route('root.prompts.show', $prompt)->with('success', 'Prompt updated successfully');
    }
}