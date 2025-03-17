<?php

namespace App\Http\Controllers\Blog;

use App\Models\Blog\Article;
use App\Models\Prompts\Prompt;
use Illuminate\Support\Str;

class SEOService
{
    /**
     * Generate SEO data for the home page
     */
    public static function getHomePageSEO(): array
    {
        return [
            'title' => 'El Arquitecto A.I. - Democratizando I.A.',
            'description' => 'Aprende sobre Inteligencia Artificial en español con El Arquitecto A.I. Blog, tutoriales, prompts y más.',
            'keywords' => 'inteligencia artificial, IA, machine learning, español, Latinoamérica',
            'ogType' => 'website',
            'ogImage' => url('/images/home-og.png'),
            'canonicalUrl' => route('home'),
            'twitterCard' => 'summary_large_image',
        ];
    }

    /**
     * Generate SEO data for the blog index page
     */
    public static function getBlogIndexSEO(): array
    {
        return [
            'title' => 'Blog - El Arquitecto A.I.',
            'description' => 'Artículos sobre IA, tutoriales y mejores prácticas para mantenerte actualizado.',
            'keywords' => 'inteligencia artificial, IA, blog, artículos, tutoriales',
            'ogType' => 'blog',
            'ogImage' => url('/images/blog-og.png'),
            'canonicalUrl' => route('blog.index'),
            'twitterCard' => 'summary_large_image',
        ];
    }

    /**
     * Generate SEO data for a specific article page
     */
    public static function getArticleSEO(Article $article): array
    {
        // Format image URL if it exists
        $imageUrl = null;
        if (!empty($article->image)) {
            $imageUrl = url('storage/' . $article->image);
        } else {
            $imageUrl = url('/images/blog-og.png');
        }

        return [
            'title' => $article->title,
            'description' => $article->excerpt ?: Str::limit(strip_tags($article->content), 160),
            'keywords' => 'inteligencia artificial, IA, blog, ' . $article->title,
            'ogType' => 'article',
            'ogImage' => $imageUrl,
            'canonicalUrl' => route('blog.show', $article),
            'twitterCard' => 'summary_large_image',
        ];
    }

    /**
     * Generate SEO data for the prompts index page
     */
    public static function getPromptsIndexSEO(): array
    {
        return [
            'title' => 'Prompts - El Arquitecto A.I.',
            'description' => 'Colección de prompts de IA optimizados para diferentes casos de uso.',
            'keywords' => 'inteligencia artificial, IA, prompts, machine learning, español',
            'ogType' => 'website',
            'ogImage' => url('/images/prompts-og.png'),
            'canonicalUrl' => route('prompts.index'),
            'twitterCard' => 'summary_large_image',
        ];
    }

    /**
     * Generate SEO data for a specific prompt page
     */
    public static function getPromptSEO(Prompt $prompt): array
    {
        return [
            'title' => $prompt->name,
            'description' => $prompt->description ? Str::limit(strip_tags($prompt->description), 160) : 'Prompt de IA optimizado por El Arquitecto A.I.',
            'keywords' => 'inteligencia artificial, prompt, IA, ' . $prompt->name,
            'ogType' => 'article',
            'ogImage' => url('/images/prompts-og.png'),
            'canonicalUrl' => route('prompts.show', $prompt),
            'twitterCard' => 'summary_large_image',
        ];
    }

    /**
     * Generate JSON-LD structured data for an article
     */
    public static function getArticleJSONLD(Article $article): array
    {
        $articleUrl = route('blog.show', $article);

        // Format image URL if it exists
        $imageUrl = null;
        if (!empty($article->image)) {
            $imageUrl = url('storage/' . $article->image);
        } else {
            $imageUrl = url('/images/blog-og.png');
        }

        $articleData = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->title,
            'description' => $article->excerpt ?: Str::limit(strip_tags($article->content), 160),
            'image' => $imageUrl,
            'datePublished' => $article->published_at?->toIso8601String(),
            'dateModified' => $article->updated_at?->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $article->author?->name ?? 'El Arquitecto A.I.',
                'url' => $article->author?->id ? url("/authors/{$article->author->id}") : null,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'El Arquitecto A.I.',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => url('/logo.png'),
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $articleUrl,
            ],
        ];

        // Add breadcrumb schema
        $breadcrumb = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => route('home')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Blog',
                    'item' => route('blog.index')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $article->title,
                    'item' => $articleUrl
                ]
            ]
        ];

        return [
            'article' => $articleData,
            'breadcrumb' => $breadcrumb
        ];
    }

    /**
     * Generate JSON-LD structured data for a prompt
     */
    public static function getPromptJSONLD(Prompt $prompt): array
    {
        $promptUrl = route('prompts.show', $prompt);

        $promptData = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $prompt->name,
            'description' => $prompt->description ?: "Prompt de IA optimizado por El Arquitecto A.I.",
            'url' => $promptUrl,
            'author' => [
                '@type' => 'Person',
                'name' => $prompt->author?->name ?? 'El Arquitecto A.I.',
            ],
            'datePublished' => $prompt->created_at?->toIso8601String(),
            'dateModified' => $prompt->updated_at?->toIso8601String(),
        ];

        // Add breadcrumb schema
        $breadcrumb = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => route('home')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Prompts',
                    'item' => route('prompts.index')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $prompt->name,
                    'item' => $promptUrl
                ]
            ]
        ];

        return [
            'prompt' => $promptData,
            'breadcrumb' => $breadcrumb
        ];
    }
}