<?php

namespace App\Http\Controllers;

use App\Models\Prompts\Prompt;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PromptController extends Controller
{
    public function index() : Response
    {
        return Inertia::render('Prompts/Index', [
            'prompts' => Prompt::with('author:id,name')->get(),
        ]);
    }
    
    public function show(Prompt $prompt) : Response
    {
        return Inertia::render('Prompts/Show', [
            'prompt' => $prompt,
        ]);
    }
}
