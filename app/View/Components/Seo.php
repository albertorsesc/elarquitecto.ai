<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Seo extends Component
{
    /**
     * Page title
     */
    public string $title;

    /**
     * Page description
     */
    public string $description;

    /**
     * Page keywords
     */
    public string $keywords;

    /**
     * Canonical URL
     */
    public string $canonical;

    /**
     * OG image URL
     */
    public string $image;

    /**
     * Content type (article, website, etc.)
     */
    public string $type;

    /**
     * Robots directive
     */
    public ?string $robots;

    /**
     * Twitter creator
     */
    public ?string $twitterCreator;

    /**
     * Twitter site
     */
    public ?string $twitterSite;

    /**
     * Author name
     */
    public ?string $author;

    /**
     * Additional meta tags
     */
    public array $additionalMetaTags;

    /**
     * Schema type for structured data
     */
    public string $schemaType;

    /**
     * Schema data for structured data
     */
    public array $schemaData;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $title = '',
        string $description = '',
        string $keywords = '',
        ?string $content = null,
        ?string $canonical = null,
        ?string $image = null,
        string $type = 'website',
        ?string $robots = null,
        ?string $twitterCreator = null,
        ?string $twitterSite = null,
        ?string $author = null,
        array $additionalMetaTags = [],
        string $schemaType = 'WebPage',
        array $schemaData = []
    ) {
        $this->title = $title ?: config('app.name', 'El Arquitecto A.I.');

        // Use provided description or generate from content
        if (! empty($description)) {
            $this->description = $description;
        } elseif (! empty($content)) {
            $this->description = $this->generateExcerpt($content);
        } else {
            $this->description = config('app.description', 'Democratizando I.A. para el beneficio de Latinoamérica');
        }

        // Use provided keywords or extract from content
        if (! empty($keywords)) {
            $this->keywords = $keywords;
        } elseif (! empty($content)) {
            $this->keywords = $this->extractKeywords($content);
        } else {
            $this->keywords = config('app.keywords', 'IA, Inteligencia Artificial, Latinoamérica, AI');
        }

        $this->canonical = $canonical ?: request()->url();
        
        // Ensure image URL is absolute
        if (!empty($image)) {
            // If the image URL doesn't start with http/https, make it absolute
            if (!preg_match('/^https?:\/\//', $image)) {
                $this->image = url($image);
            } else {
                $this->image = $image;
            }
        } else {
            $this->image = url('/img/logo.webp');
        }
        
        $this->type = $type;
        $this->robots = $robots;
        $this->twitterCreator = $twitterCreator;
        $this->twitterSite = $twitterSite;
        $this->author = $author;
        $this->additionalMetaTags = $additionalMetaTags;
        $this->schemaType = $schemaType;
        $this->schemaData = $schemaData;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.seo.meta-tags', [
            'structuredData' => $this->getStructuredData(),
        ]);
    }

    /**
     * Extract keywords from content
     *
     * @param  string  $content  The content to extract keywords from
     * @param  int  $maxKeywords  The maximum number of keywords to extract
     * @param  array  $exclude  Words to exclude
     * @return string Comma-separated keywords
     */
    protected function extractKeywords(string $content, int $maxKeywords = 10, array $exclude = []): string
    {
        // Common Spanish stopwords to exclude
        $stopwords = array_merge([
            'el', 'la', 'los', 'las', 'un', 'una', 'unos', 'unas', 'y', 'o', 'pero', 'si',
            'de', 'del', 'a', 'en', 'para', 'por', 'con', 'sin', 'sobre', 'entre', 'que',
            'como', 'cuando', 'donde', 'quien', 'cual', 'es', 'son', 'fue', 'ha', 'han', 'ser',
            'este', 'esta', 'estos', 'estas', 'ese', 'esa', 'esos', 'esas', 'aquel',
            'aquella', 'aquellos', 'aquellas', 'su', 'sus', 'mi', 'mis', 'tu', 'tus',
            'más', 'menos', 'muy', 'mucho', 'poco', 'tanto', 'tan', 'al', 'del',
        ], $exclude);

        // Clean the content
        $content = strip_tags($content);
        $content = strtolower($content);
        $content = preg_replace('/[^\p{L}\p{N}\s]/u', '', $content);

        // Split into words
        $words = preg_split('/\s+/', $content);

        // Count word frequency excluding stopwords
        $wordCounts = [];
        foreach ($words as $word) {
            $word = trim($word);
            if (strlen($word) > 3 && ! in_array($word, $stopwords)) {
                if (! isset($wordCounts[$word])) {
                    $wordCounts[$word] = 0;
                }
                $wordCounts[$word]++;
            }
        }

        // Sort by frequency
        arsort($wordCounts);

        // Take top keywords
        $keywords = array_slice(array_keys($wordCounts), 0, $maxKeywords);

        return implode(', ', $keywords);
    }

    /**
     * Generate excerpt from content
     *
     * @param  string  $content  The content to generate excerpt from
     * @param  int  $length  Maximum length of the excerpt
     * @return string The excerpt
     */
    protected function generateExcerpt(string $content, int $length = 160): string
    {
        $content = strip_tags($content);
        $content = trim(preg_replace('/\s+/', ' ', $content));

        if (strlen($content) <= $length) {
            return $content;
        }

        $excerpt = substr($content, 0, $length);
        $lastSpace = strrpos($excerpt, ' ');

        return substr($excerpt, 0, $lastSpace).'...';
    }

    /**
     * Get structured data for the page
     *
     * @return array The structured data
     */
    protected function getStructuredData(): array
    {
        // Default data for different schema types
        $defaultData = [
            'WebPage' => [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'url' => $this->canonical,
                'name' => $this->title,
                'description' => $this->description,
                'inLanguage' => 'es-LA',
            ],
            'Article' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $this->title,
                'description' => $this->description,
                'image' => $this->image,
                'datePublished' => $this->schemaData['published_at'] ?? null,
                'dateModified' => $this->schemaData['updated_at'] ?? null,
                'author' => [
                    '@type' => 'Person',
                    'name' => $this->author ?? config('app.author', 'Alberto Rosas'),
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => config('app.name'),
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('/img/logo.webp'),
                    ],
                ],
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => $this->canonical,
                ],
            ],
            'BreadcrumbList' => [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $this->schemaData['items'] ?? [],
            ],
            'Organization' => [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => config('app.name'),
                'url' => config('app.url'),
                'logo' => asset('/img/logo.webp'),
                'sameAs' => $this->schemaData['sameAs'] ?? [],
            ],
            'HowTo' => [
                '@context' => 'https://schema.org',
                '@type' => 'HowTo',
                'name' => $this->title,
                'description' => $this->description,
                'datePublished' => $this->schemaData['published_at'] ?? null,
                'dateModified' => $this->schemaData['updated_at'] ?? null,
                'author' => [
                    '@type' => 'Person',
                    'name' => $this->author ?? config('app.author', 'Alberto Rosas'),
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => config('app.name'),
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('/img/logo.webp'),
                    ],
                ],
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => $this->canonical,
                ],
            ],
            'CollectionPage' => [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'url' => $this->canonical,
                'name' => $this->title,
                'description' => $this->description,
                'inLanguage' => 'es-LA',
            ],
        ];

        // Combine default data with provided data
        return array_merge(
            $defaultData[$this->schemaType] ?? $defaultData['WebPage'],
            array_filter($this->schemaData, function ($value) {
                return ! is_null($value);
            })
        );
    }
}
