<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Blog\SEOService;
use App\Models\Blog\Article;
use App\Models\Timeline;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function index(): Response
    {
        $articles = Article::query()
            ->published()
            ->with(['author'])
            ->latest('published_at')
            ->paginate(9);
        $timelineItems = Timeline::with('author:id,name')->latest()->paginate(10);

        // Use the SEO service to generate SEO data
        $seoData = SEOService::getHomePageSEO();
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'El Arquitecto A.I.',
            'url' => url('/'),
            'description' => $seoData['description'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => url('/search?q={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ],
        ];

        return Inertia::render('Welcome', [
            'articles' => $articles,
            'items' => $timelineItems,
            'seo' => $seoData,
            'jsonLd' => $jsonLd,
        ]);
    }
}