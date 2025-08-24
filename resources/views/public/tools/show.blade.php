@extends('public.layouts.guest')

@section('title', $tool->meta_title ?: $tool->title . ' - Herramienta IA')
@section('description', $tool->meta_description ?: $tool->excerpt)
@section('keywords', $tool->meta_keywords ? implode(', ', $tool->meta_keywords) : 'herramienta IA, ' . $tool->title . ', inteligencia artificial')
@section('og-type', 'article')
@section('og-image', $tool->featured_image_url ?? $tool->featured_image)
@php
    $businessModelLabel = $tool->business_model->label();
    $businessModelColor = $tool->business_model->color();
    
    // Enhanced structured data for SEO
    $schemaData = [
        '@context' => 'https://schema.org',
        '@type' => 'SoftwareApplication',
        'name' => $tool->title,
        'description' => $tool->excerpt,
        'applicationCategory' => 'WebApplication',
        'operatingSystem' => 'Web',
        'url' => route('tools.show', $tool->slug),
        'datePublished' => $tool->published_at?->toIso8601String(),
        'dateModified' => $tool->updated_at->toIso8601String(),
        'author' => [
            '@type' => 'Organization',
            'name' => config('app.name', 'El Arquitecto AI'),
            'url' => url('/')
        ],
        'offers' => [
            '@type' => 'Offer',
            'price' => $tool->business_model === \App\Enums\ToolBusinessModelEnum::FREE ? '0' : null,
            'priceCurrency' => 'USD',
            'availability' => 'https://schema.org/InStock',
            'priceValidUntil' => now()->addYear()->toIso8601String(),
        ],
        'aggregateRating' => [
            '@type' => 'AggregateRating',
            'ratingValue' => '4.5',
            'ratingCount' => '1',
            'bestRating' => '5',
            'worstRating' => '1'
        ]
    ];
    
    // Add image if available
    if ($tool->featured_image_url || $tool->featured_image) {
        $schemaData['image'] = $tool->featured_image_url ?? $tool->featured_image;
    }
    
    // Add software requirements
    if ($tool->website_url) {
        $schemaData['softwareRequirements'] = 'Web browser with internet connection';
        $schemaData['downloadUrl'] = $tool->website_url;
    }
    
    // Add review if description exists
    if ($tool->description) {
        $schemaData['review'] = [
            '@type' => 'Review',
            'reviewBody' => strip_tags($tool->description),
            'author' => [
                '@type' => 'Organization',
                'name' => config('app.name', 'El Arquitecto AI')
            ],
            'datePublished' => $tool->published_at?->toIso8601String() ?? $tool->created_at->toIso8601String()
        ];
    }
    
    // Breadcrumb structured data
    $breadcrumbData = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Inicio',
                'item' => url('/')
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Herramientas IA',
                'item' => route('tools.index')
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $tool->title,
                'item' => route('tools.show', $tool->slug)
            ]
        ]
    ];
    
    // FAQ structured data if we have content
    $faqData = null;
    if ($tool->description) {
        $faqData = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => '¿Qué es ' . $tool->title . '?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $tool->excerpt
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => '¿Cuál es el modelo de negocio de ' . $tool->title . '?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $tool->title . ' utiliza un modelo ' . $businessModelLabel . '.'
                    ]
                ]
            ]
        ];
    }
@endphp

@section('schema-type', 'SoftwareApplication')
@section('canonical', route('tools.show', $tool->slug))

@push('head')
<!-- Main Schema.org structured data -->
<script type="application/ld+json">
{!! json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
</script>

<!-- Breadcrumb structured data -->
<script type="application/ld+json">
{!! json_encode($breadcrumbData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
</script>

@if($faqData)
<!-- FAQ structured data -->
<script type="application/ld+json">
{!! json_encode($faqData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
</script>
@endif

<!-- Additional meta tags for social sharing -->
<meta property="article:published_time" content="{{ $tool->published_at?->toIso8601String() }}" />
<meta property="article:modified_time" content="{{ $tool->updated_at->toIso8601String() }}" />
<meta property="article:author" content="{{ config('app.name') }}" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $tool->meta_title ?: $tool->title }}" />
<meta name="twitter:description" content="{{ $tool->meta_description ?: $tool->excerpt }}" />
<meta name="twitter:image" content="{{ $tool->featured_image_url ?? $tool->featured_image ?? asset('img/logo.webp') }}" />
@endpush

@section('content')
    <article class="flex h-full flex-1 flex-col gap-4 p-4 mb-24 relative z-10 max-w-6xl mx-auto">
        <!-- Header -->
        <div class="glass-effect neon-border rounded-xl overflow-hidden mb-6">
            @if($tool->featured_image_url || $tool->featured_image)
            <div class="relative h-64 md:h-96 w-full">
                <img src="{{ $tool->featured_image_url ?? $tool->featured_image }}" alt="{{ $tool->title }}" 
                     class="h-full w-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-background/90 to-transparent"></div>
                
                <!-- Title overlay -->
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 text-shadow-lg">{{ $tool->title }}</h1>
                    <p class="text-lg text-white/90 text-shadow">{{ $tool->excerpt }}</p>
                </div>
            </div>
            @else
            <div class="p-6">
                <h1 class="text-3xl md:text-4xl font-bold text-glow animate-text-glow mb-2">{{ $tool->title }}</h1>
                <p class="text-lg text-muted-foreground">{{ $tool->excerpt }}</p>
            </div>
            @endif

            <!-- Metadata bar -->
            <div class="flex flex-wrap items-center gap-4 p-4 border-t border-border/30">
                <!-- Business Model -->
                <span class="px-3 py-1 text-sm rounded-full font-medium
                    @if($businessModelColor === 'green') bg-green-500/20 text-green-500 border border-green-500/30
                    @elseif($businessModelColor === 'blue') bg-blue-500/20 text-blue-500 border border-blue-500/30
                    @elseif($businessModelColor === 'red') bg-red-500/20 text-red-500 border border-red-500/30
                    @elseif($businessModelColor === 'purple') bg-purple-500/20 text-purple-500 border border-purple-500/30
                    @elseif($businessModelColor === 'orange') bg-orange-500/20 text-orange-500 border border-orange-500/30
                    @elseif($businessModelColor === 'cyan') bg-cyan-500/20 text-cyan-500 border border-cyan-500/30
                    @endif">
                    {{ $businessModelLabel }}
                </span>

                @if($tool->is_featured)
                <span class="px-3 py-1 text-sm rounded-full bg-yellow-500/20 text-yellow-500 border border-yellow-500/30 font-medium">
                    Destacado
                </span>
                @endif

                <!-- Categories -->
                @foreach($tool->categories as $category)
                <a href="{{ route('tools.index', ['categoria' => $category->slug]) }}" 
                   class="px-3 py-1 text-sm rounded-full bg-primary/20 text-primary border border-primary/30 hover:bg-primary/30 transition-colors">
                    {{ $category->name }}
                </a>
                @endforeach

                <span class="text-sm text-muted-foreground ml-auto">
                    Publicado {{ $tool->published_at->diffForHumans() }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tags -->
                @if($tool->tags->isNotEmpty())
                <section class="glass-effect neon-border rounded-xl p-6" aria-labelledby="tags-heading">
                    <h2 id="tags-heading" class="text-xl font-bold mb-4">Etiquetas</h2>
                    <div class="flex flex-wrap gap-2" role="list">
                        @foreach($tool->tags as $tag)
                        <span role="listitem" class="px-3 py-1 text-sm rounded-full bg-sidebar-accent text-sidebar-accent-foreground" itemprop="keywords">
                            {{ $tag->name }}
                        </span>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- Description -->
                @if($tool->description)
                <section class="glass-effect neon-border rounded-xl p-6" aria-labelledby="description-heading">
                    <h2 id="description-heading" class="text-2xl font-bold mb-4">Descripción</h2>
                    <div class="prose prose-invert max-w-none" itemprop="description">
                        {!! \Illuminate\Support\Str::markdown($tool->description) !!}
                    </div>
                </section>
                @endif
            </div>

            <!-- Sidebar -->
            <aside class="space-y-6" role="complementary">
                <!-- Links -->
                @if($tool->website_url || $tool->pricing_url || $tool->documentation_url)
                <nav class="glass-effect neon-border rounded-xl p-6" aria-labelledby="links-heading">
                    <h2 id="links-heading" class="text-xl font-bold mb-4">Enlaces</h2>
                    <ul class="space-y-3" role="list">
                        @if($tool->website_url)
                        <li role="listitem">
                            <a href="{{ $tool->website_url }}" target="_blank" rel="noopener noreferrer" itemprop="url"
                               class="flex items-center justify-between p-3 rounded-lg bg-sidebar-accent/20 hover:bg-sidebar-accent/30 transition-colors group">
                                <span class="font-medium">Sitio web oficial</span>
                                <svg class="w-5 h-5 text-primary group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </li>
                        @endif

                        @if($tool->pricing_url)
                        <li role="listitem">
                            <a href="{{ $tool->pricing_url }}" target="_blank" rel="noopener noreferrer"
                               class="flex items-center justify-between p-3 rounded-lg bg-sidebar-accent/20 hover:bg-sidebar-accent/30 transition-colors group">
                                <span class="font-medium">Ver precios</span>
                                <svg class="w-5 h-5 text-primary group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </li>
                        @endif

                        @if($tool->documentation_url)
                        <li role="listitem">
                            <a href="{{ $tool->documentation_url }}" target="_blank" rel="noopener noreferrer"
                               class="flex items-center justify-between p-3 rounded-lg bg-sidebar-accent/20 hover:bg-sidebar-accent/30 transition-colors group">
                                <span class="font-medium">Documentación</span>
                                <svg class="w-5 h-5 text-primary group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </li>
                        @endif
                    </ul>
                </nav>
                @endif

                <!-- Share -->
                <div class="glass-effect neon-border rounded-xl p-6">
                    <h2 class="text-xl font-bold mb-4">Compartir</h2>
                    <div class="flex gap-3">
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($tool->title) }}&url={{ urlencode(request()->url()) }}" 
                           target="_blank" rel="noopener noreferrer"
                           class="p-3 rounded-lg bg-sidebar-accent/20 hover:bg-sidebar-accent/30 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                           target="_blank" rel="noopener noreferrer"
                           class="p-3 rounded-lg bg-sidebar-accent/20 hover:bg-sidebar-accent/30 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); alert('URL copiada al portapapeles')" 
                                class="p-3 rounded-lg bg-sidebar-accent/20 hover:bg-sidebar-accent/30 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Download Markdown -->
                <div class="glass-effect neon-border rounded-xl p-6">
                    <h2 class="text-xl font-bold mb-4">Exportar</h2>
                    <a href="{{ route('tools.markdown', $tool->slug) }}" 
                       class="flex items-center justify-center p-3 rounded-lg bg-primary/20 text-primary hover:bg-primary/30 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                        Descargar en Markdown
                    </a>
                </div>
            </aside>
        </div>

        <!-- Related Tools -->
        @if($relatedTools->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Herramientas relacionadas</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedTools as $relatedTool)
                    <x-tool-card :tool="$relatedTool" />
                @endforeach
            </div>
        </div>
        @endif
    </article>
    
    <!-- Include neon scrollbar styles -->
    <x-neon-scrollbar />
@endsection

@push('styles')
<style>
.glass-effect {
  background-color: hsl(var(--card)) !important;
}

.text-shadow {
    text-shadow: 0 2px 4px rgba(0,0,0,0.8);
}

.text-shadow-lg {
    text-shadow: 0 2px 8px rgba(0,0,0,0.9), 0 0 20px rgba(0,0,0,0.5);
}

.prose {
    color: hsl(var(--foreground));
}

.prose h1, .prose h2, .prose h3, .prose h4 {
    color: hsl(var(--foreground));
}

.prose a {
    color: hsl(var(--primary));
}

.prose code {
    color: hsl(var(--primary));
    background: hsl(var(--primary) / 0.1);
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
}

.prose pre {
    background: hsl(var(--card));
    border: 1px solid hsl(var(--border));
}
</style>
@endpush