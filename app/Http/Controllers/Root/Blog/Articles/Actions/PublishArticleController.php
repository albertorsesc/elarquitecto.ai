<?php

namespace App\Http\Controllers\Root\Blog\Articles\Actions;

use App\Http\Controllers\Controller;
use App\Models\Blog\Article;
use Illuminate\Http\RedirectResponse;

class PublishArticleController extends Controller
{
    public function __invoke(Article $article) : RedirectResponse
    {
        $article->update([
            'published_at' => now(),
        ]);
        
        return to_route('root.articles.index');
    }
}
