<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Blog\SEOService;
use App\Models\Prompts\Prompt;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PromptController extends Controller
{
    public function index(): Response
    {
        // Use the SEO service to generate SEO data
        $seoData = SEOService::getPromptsIndexSEO();
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $seoData['title'],
            'description' => $seoData['description'],
            'url' => route('prompts.index'),
        ];

        return Inertia::render('Prompts/Index', [
            'prompts' => Prompt::with('author:id,name')->get(),
            'seo' => $seoData,
            'jsonLd' => $jsonLd,
        ]);
    }

    public function show(Prompt $prompt): Response
    {
        // Generate SEO data and JSON-LD for this prompt
        $seoData = SEOService::getPromptSEO($prompt);
        $jsonLd = SEOService::getPromptJSONLD($prompt);

        return Inertia::render('Prompts/Show', [
            'prompt' => $prompt->load('author'),
            'seo' => $seoData,
            'jsonLd' => $jsonLd,
            'breadcrumbs' => [
                [
                    'title' => 'Home',
                    'href' => route('home'),
                ],
                [
                    'title' => 'Prompts',
                    'href' => route('prompts.index'),
                ],
            ],
        ]);
    }
}