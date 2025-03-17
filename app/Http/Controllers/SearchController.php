<?php

namespace App\Http\Controllers;

use App\Models\Blog\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query) || strlen($query) < 2) {
            return response()->json(['articles' => []]);
        }

        $articles = Article::search($query)
            ->query(function ($builder) {
                return $builder->with(['author'])
                    ->published();
            })
            ->take(10)
            ->get();

        if ($request->wantsJson()) {
            return response()->json(['articles' => $articles]);
        }

        return Inertia::render('Welcome', [
            'articles' => $articles
        ]);
    }
}