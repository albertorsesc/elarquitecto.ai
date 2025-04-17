{{-- Structured Data Component
    This component renders JSON-LD structured data following schema.org standards.
    It accepts a type parameter to determine which schema to use.
--}}

@props([
    'type' => 'WebPage', 
    'data' => [],
    'jsonData' => null
])

@php
    // Default data for different schema types
    $defaultData = [
        'WebPage' => [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'url' => request()->url(),
            'name' => $data['title'] ?? config('app.name'),
            'description' => $data['description'] ?? null,
            'inLanguage' => 'es-LA'
        ],
        'Article' => [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'datePublished' => $data['published_at'] ?? null,
            'dateModified' => $data['updated_at'] ?? null,
            'author' => [
                '@type' => 'Person',
                'name' => $data['author'] ?? config('app.author', 'Alberto Rosas')
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('/img/logo.webp')
                ]
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => request()->url()
            ]
        ],
        'BreadcrumbList' => [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $data['items'] ?? []
        ],
        'Organization' => [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => config('app.url'),
            'logo' => asset('/img/logo.webp'),
            'sameAs' => [
                'https://instagram.com/elarquitectoai',
                'https://facebook.com/elarquitectoai',
                'https://youtube.com/@elarquitectoai',
                'https://tiktok.com/@elarquitectoai',
            ]
        ],
        'HowTo' => [
            '@context' => 'https://schema.org',
            '@type' => 'HowTo',
            'name' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'datePublished' => $data['published_at'] ?? null,
            'dateModified' => $data['updated_at'] ?? null,
            'author' => [
                '@type' => 'Person',
                'name' => $data['author'] ?? config('app.author', 'Alberto Rosas')
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('/img/logo.webp')
                ]
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => request()->url()
            ]
        ]
    ];
    
    // Combine default data with provided data
    $finalData = $jsonData ?? array_merge(
        $defaultData[$type] ?? $defaultData['WebPage'],
        array_filter($data, function($value) { return !is_null($value); })
    );
@endphp

<script type="application/ld+json">
    {!! json_encode($finalData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script> 