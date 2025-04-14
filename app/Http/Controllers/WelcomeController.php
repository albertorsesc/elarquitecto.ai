<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Show the application welcome page.
     */
    public function index(): View
    {
        // Get timeline items - currently only prompts
        $timeline = $this->getTimelineItems();

        // Group timeline items by type
        $groupedTimeline = $timeline->groupBy('type');

        return view('welcome', [
            'seo' => [
                'title' => 'El Arquitecto A.I.',
                'description' => 'Democratizando I.A. para el beneficio de Latinoamérica',
                'keywords' => 'IA, Inteligencia Artificial, Latinoamérica, AI',
            ],
            'timeline' => $groupedTimeline,
            'articles' => ['data' => []], // Empty articles for now
        ]);
    }

    /**
     * Get all timeline items.
     * 
     * This method will be expanded later to include other resource types.
     */
    protected function getTimelineItems(): Collection
    {
        // Get all prompts sorted by creation date (newest first)
        $prompts = Prompt::query()
            ->with(['tags', 'category']) // Eager load relationships
            ->orderByDesc('created_at')
            ->limit(6) // Limit to most recent prompts
            ->get()
            ->map(function ($prompt) {
                // Map tags to a simple array with 'name' and 'slug' keys
                $tagsArray = $prompt->tags->map(function($tag) {
                    return [
                        'name' => $tag->name,
                        'slug' => $tag->slug,
                    ];
                })->toArray();

                return [
                    'id' => $prompt->id,
                    'type' => 'prompt', // Type identifier for grouping
                    'title' => $prompt->title,
                    'excerpt' => $prompt->excerpt,
                    'content' => $prompt->excerpt, // Use excerpt as timeline content
                    'date' => $prompt->created_at->format('M d, Y'),
                    'image' => $prompt->image,
                    'word_count' => $prompt->word_count,
                    'url' => route('prompts.show', $prompt),
                    'tags' => $tagsArray, // Use the transformed tags array
                    'category' => $prompt->category ? [$prompt->category] : [],
                ];
            });

        // In future, we'll add other resource types here and merge them
        // Example:
        // $articles = Article::query()->orderByDesc('created_at')->get()->map(...);
        // return $prompts->concat($articles)->sortByDesc('date');

        return $prompts;
    }
} 