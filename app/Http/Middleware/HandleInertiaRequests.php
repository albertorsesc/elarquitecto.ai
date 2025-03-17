<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        // Add SEO data and JSON-LD
        $seoData = $this->getSeoData($request);
        $jsonLd = $this->getJsonLd($request, $seoData);

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
                'is_root' => $request->user()?->email === config('app.users.root'),
                'root_email' => config('app.users.root'),
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
                'warning' => $request->session()->get('warning'),
                'info' => $request->session()->get('info'),
            ],
            'seo' => $seoData,
            'jsonLd' => $jsonLd,
        ];
    }

    /**
     * Get SEO data for the current request
     */
    protected function getSeoData(Request $request): array
    {
        $routeName = $request->route()?->getName();
        $params = $request->route()?->parameters() ?? [];
        $baseUrl = config('app.url');

        // Default SEO data
        $default = [
            'title' => 'El Arquitecto A.I.',
            'description' => 'Democratizando I.A. para el beneficio de Latinoamérica',
            'keywords' => 'inteligencia artificial, IA, machine learning, español, Latinoamérica',
            'canonicalUrl' => $request->url(),
            'ogType' => 'website',
            'ogImage' => "$baseUrl/logo.png",
            'twitterCard' => 'summary_large_image',
        ];

        // Route-specific SEO data
        $routeSeo = $this->getRouteSeoData($routeName, $params);

        return array_merge($default, $routeSeo);
    }

    /**
     * Get route-specific SEO data
     */
    protected function getRouteSeoData(?string $routeName, array $params): array
    {
        if (! $routeName) {
            return [];
        }

        $baseUrl = config('app.url');

        // Define SEO data for specific routes
        $routesSeo = [
            'home' => [
                'title' => 'El Arquitecto A.I. - Democratizando I.A.',
                'description' => 'Aprende sobre Inteligencia Artificial en español con El Arquitecto A.I. Blog, tutoriales, prompts y más.',
                'ogImage' => "$baseUrl/images/home-og.png",
                'canonicalUrl' => route('home'),
            ],
            'blog.index' => [
                'title' => 'Blog - El Arquitecto A.I.',
                'description' => 'Artículos sobre IA, tutoriales y mejores prácticas para mantenerte actualizado.',
                'ogType' => 'blog',
                'ogImage' => "$baseUrl/images/blog-og.png",
                'canonicalUrl' => route('blog.index'),
            ],
            'prompts.index' => [
                'title' => 'Prompts - El Arquitecto A.I.',
                'description' => 'Colección de prompts de IA optimizados para diferentes casos de uso.',
                'ogType' => 'website',
                'ogImage' => "$baseUrl/images/prompts-og.png",
                'canonicalUrl' => route('prompts.index'),
            ],
        ];

        // Handle blog articles
        if ($routeName === 'blog.show' && isset($params['article'])) {
            $article = $params['article'];
            $canonicalUrl = route('blog.show', $article);

            // Format the image path correctly
            $imageUrl = null;
            if (! empty($article->image)) {
                $imageUrl = str_starts_with($article->image, 'http')
                    ? $article->image
                    : "$baseUrl/storage/".ltrim($article->image, '/');
            }

            // Fallback default image if none provided
            if (empty($imageUrl)) {
                $imageUrl = "$baseUrl/images/blog-og.png";
            }

            return [
                'title' => $article->title,
                'description' => $article->excerpt ?: substr(strip_tags($article->content ?? ''), 0, 160),
                'keywords' => 'inteligencia artificial, artículo, blog, '.$article->title,
                'ogType' => 'article',
                'ogImage' => $imageUrl,
                'canonicalUrl' => $canonicalUrl,
            ];
        }

        // Handle prompts
        if ($routeName === 'prompts.show' && isset($params['prompt'])) {
            $prompt = $params['prompt'];
            $canonicalUrl = route('prompts.show', $prompt);

            return [
                'title' => $prompt->name,
                'description' => $prompt->description ?: 'Prompt de IA optimizado por El Arquitecto A.I.',
                'keywords' => 'inteligencia artificial, prompt, IA, '.$prompt->name,
                'ogType' => 'article',
                'ogImage' => "$baseUrl/images/prompts-og.png",
                'canonicalUrl' => $canonicalUrl,
            ];
        }

        return $routesSeo[$routeName] ?? [];
    }

    /**
     * Get JSON-LD structured data
     */
    protected function getJsonLd(Request $request, array $seoData): array
    {
        $routeName = $request->route()?->getName();
        $params = $request->route()?->parameters() ?? [];
        $baseUrl = config('app.url');

        // Default JSON-LD (WebSite)
        $default = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'El Arquitecto A.I.',
            'url' => $baseUrl,
            'description' => $seoData['description'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => "$baseUrl/search?q={search_term_string}",
                'query-input' => 'required name=search_term_string',
            ],
        ];

        // Article structured data
        if ($routeName === 'blog.show' && isset($params['article'])) {
            $article = $params['article'];
            $articleUrl = route('blog.show', $article);

            // Format image URL correctly
            $imageUrl = null;
            if (! empty($article->image)) {
                $imageUrl = str_starts_with($article->image, 'http')
                    ? $article->image
                    : "$baseUrl/storage/".ltrim($article->image, '/');
            }

            // Fallback default image if none provided
            if (empty($imageUrl)) {
                $imageUrl = "$baseUrl/images/blog-og.png";
            }

            $articleData = [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $article->title,
                'description' => $article->excerpt ?: substr(strip_tags($article->content ?? ''), 0, 160),
                'image' => $imageUrl,
                'datePublished' => $article->published_at?->toIso8601String(),
                'dateModified' => $article->updated_at?->toIso8601String(),
                'author' => [
                    '@type' => 'Person',
                    'name' => $article->author?->name ?? 'El Arquitecto A.I.',
                    'url' => $article->author?->id ? "$baseUrl/authors/{$article->author->id}" : null,
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'El Arquitecto A.I.',
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => "$baseUrl/logo.png",
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
                        'item' => route('home'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Blog',
                        'item' => route('blog.index'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $article->title,
                        'item' => $articleUrl,
                    ],
                ],
            ];

            return [
                'article' => $articleData,
                'breadcrumb' => $breadcrumb,
            ];
        }

        // Prompt structured data
        if ($routeName === 'prompts.show' && isset($params['prompt'])) {
            $prompt = $params['prompt'];
            $promptUrl = route('prompts.show', $prompt);

            $promptData = [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => $prompt->name,
                'description' => $prompt->description ?: 'Prompt de IA optimizado por El Arquitecto A.I.',
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
                        'item' => route('home'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Prompts',
                        'item' => route('prompts.index'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $prompt->name,
                        'item' => $promptUrl,
                    ],
                ],
            ];

            return [
                'prompt' => $promptData,
                'breadcrumb' => $breadcrumb,
            ];
        }

        return $default;
    }
}
