<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Prompt;
use Illuminate\Http\Request;

class PromptController extends Controller
{
    /**
     * Display a listing of published prompts.
     */
    public function index(Request $request)
    {
        $prompts = Prompt::whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->paginate(15);
            
        return view('public.prompts.index', [
            'prompts' => $prompts,
        ]);
    }

    /**
     * Display the specified prompt.
     */
    public function show(Prompt $prompt)
    {
        if (!$prompt->published_at) {
            abort(404);
        }
        
        $relatedPrompts = Prompt::whereNotNull('published_at')
            ->where('id', '!=', $prompt->id)
            ->limit(3)
            ->get();
            
        return view('public.prompts.show', [
            'prompt' => $prompt,
            'relatedPrompts' => $relatedPrompts,
        ]);
    }
}
