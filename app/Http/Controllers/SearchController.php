<?php

namespace App\Http\Controllers;

use App\Models\Blog\Article;
use App\Models\Timeline;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'results' => [
                    'articles' => [],
                    // Add other resource types here as needed
                ]
            ]);
        }

        // Search articles
        $articles = Article::search($query)
            ->query(function ($builder) {
                return $builder->with(['author'])
                    ->published();
            })
            ->take(5)
            ->get();

        // Organize results by resource type
        $results = [
            'articles' => $articles,
            // Add other resource types here as needed
            // 'prompts' => $prompts,
            // 'tools' => $tools,
        ];

        if ($request->wantsJson()) {
            return response()->json(['results' => $results]);
        }

        // Get the data needed for the Welcome page
        $welcomeArticles = Article::query()
            ->published()
            ->with(['author'])
            ->latest('published_at')
            ->paginate(9);

        $timelineItems = Timeline::with('author:id,name')->latest()->paginate(10);

        return Inertia::render('Welcome', [
            'articles' => $welcomeArticles,
            'items' => $timelineItems,
            'searchResults' => $results
        ]);
    }
}