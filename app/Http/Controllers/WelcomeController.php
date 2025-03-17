<?php

namespace App\Http\Controllers;

use App\Models\Blog\Article;
use App\Models\Timeline;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function index() : Response
    {
        $articles = Article::query()
            ->published()
            ->with(['author'])
            ->latest('published_at')
            ->paginate(9);
        $timelineItems = Timeline::with('author:id,name')->latest()->paginate(10);
        
        return Inertia::render('Welcome', [
            'articles' => $articles,
            'items' => $timelineItems
        ]);
    }
}
