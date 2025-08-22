<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Prompt;
use App\Models\Tag;
use Illuminate\Http\Request;

class PromptController extends Controller
{
    /**
     * Display a listing of published prompts.
     */
    public function index(Request $request)
    {
        $query = Prompt::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['category', 'tags']);

        // Category filter
        if ($request->filled('categoria')) {
            $category = Category::where('slug', $request->categoria)->first();
            if ($category) {
                $query->whereHas('category', fn ($q) => $q->where('categories.id', $category->id));
            }
        }

        // Tag filter
        if ($request->filled('etiqueta')) {
            $tag = Tag::where('slug', $request->etiqueta)->first();
            if ($tag) {
                $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tag->id));
            }
        }

        // Search filter
        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Target model filter
        if ($request->filled('modelo')) {
            $query->whereJsonContains('target_models', $request->modelo);
        }

        // Sorting
        $sort = $request->get('ordenar', 'recientes');
        $query = match ($sort) {
            'populares' => $query->orderBy('word_count', 'desc')->orderBy('published_at', 'desc'),
            'alfabetico' => $query->orderBy('title'),
            default => $query->orderBy('published_at', 'desc'),
        };

        $prompts = $query->paginate(12)->withQueryString();

        // Get categories with prompt count
        $categories = Category::withCount(['prompts' => function ($query) {
            $query->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        }])
            ->orderBy('name')
            ->get()
            ->filter(function ($category) {
                return $category->prompts_count > 0;
            });

        // Get popular tags
        $tags = Tag::withCount(['prompts' => function ($query) {
            $query->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        }])
            ->orderBy('prompts_count', 'desc')
            ->limit(10)
            ->get()
            ->filter(function ($tag) {
                return $tag->prompts_count > 0;
            });

        return view('public.prompts.index', compact('prompts', 'categories', 'tags'));
    }

    /**
     * Display the specified prompt.
     */
    public function show(Prompt $prompt)
    {
        if (! $prompt->published_at) {
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
