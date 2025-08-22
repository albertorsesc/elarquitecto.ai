<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Prompt;
use App\Models\Tool;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemaps = [
            ['loc' => route('sitemap.pages'), 'lastmod' => now()->toAtomString()],
            ['loc' => route('sitemap.tools'), 'lastmod' => Tool::published()->max('updated_at')?->toAtomString()],
            ['loc' => route('sitemap.prompts'), 'lastmod' => Prompt::published()->max('updated_at')?->toAtomString()],
            ['loc' => route('sitemap.articles'), 'lastmod' => Article::published()->max('updated_at')?->toAtomString()],
        ];

        return response()->view('sitemap.index', compact('sitemaps'))
            ->header('Content-Type', 'text/xml');
    }

    public function pages(): Response
    {
        $pages = [
            ['loc' => route('home'), 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => route('tools.index'), 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => route('prompts.index'), 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => route('articles.index'), 'changefreq' => 'daily', 'priority' => '0.9'],
        ];

        return response()->view('sitemap.pages', compact('pages'))
            ->header('Content-Type', 'text/xml');
    }

    public function tools(): Response
    {
        $tools = Tool::published()
            ->select('slug', 'updated_at', 'published_at')
            ->orderBy('published_at', 'desc')
            ->get()
            ->map(function ($tool) {
                return [
                    'loc' => route('tools.show', $tool->slug),
                    'lastmod' => $tool->updated_at->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            });

        return response()->view('sitemap.urls', ['urls' => $tools])
            ->header('Content-Type', 'text/xml');
    }

    public function prompts(): Response
    {
        $prompts = Prompt::published()
            ->select('slug', 'updated_at', 'published_at')
            ->orderBy('published_at', 'desc')
            ->get()
            ->map(function ($prompt) {
                return [
                    'loc' => route('prompts.show', $prompt->slug),
                    'lastmod' => $prompt->updated_at->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            });

        return response()->view('sitemap.urls', ['urls' => $prompts])
            ->header('Content-Type', 'text/xml');
    }

    public function articles(): Response
    {
        $articles = Article::published()
            ->select('slug', 'updated_at', 'published_at')
            ->orderBy('published_at', 'desc')
            ->get()
            ->map(function ($article) {
                return [
                    'loc' => route('articles.show', $article->slug),
                    'lastmod' => $article->updated_at->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            });

        return response()->view('sitemap.urls', ['urls' => $articles])
            ->header('Content-Type', 'text/xml');
    }
}
