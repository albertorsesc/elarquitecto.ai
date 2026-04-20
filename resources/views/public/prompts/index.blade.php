@extends('public.layouts.guest')

@section('title', 'Prompts IA | Plantillas de Inteligencia Artificial 2024')
@section('description', 'Descubre los mejores prompts de inteligencia artificial. Colección de plantillas optimizadas para ChatGPT, Claude, Gemini y más. Mejora tu productividad con IA.')
@section('keywords', 'prompts IA, inteligencia artificial, ChatGPT prompts, Claude prompts, Gemini prompts, plantillas IA, comandos IA, El Arquitecto AI')
@section('og-type', 'website')
@section('schema-type', 'CollectionPage')

@php
// Get current filters from request
$currentCategoria = request('categoria');
$currentEtiqueta = request('etiqueta');
$currentModelo = request('modelo');
$currentBuscar = request('buscar');
$currentOrdenar = request('ordenar', 'recientes');

// Build canonical URL without unnecessary parameters
$canonicalUrl = route('prompts.index');
if ($currentCategoria || $currentEtiqueta || $currentModelo || $currentBuscar || $currentOrdenar !== 'recientes') {
    $canonicalParams = array_filter([
        'categoria' => $currentCategoria,
        'etiqueta' => $currentEtiqueta,
        'modelo' => $currentModelo,
        'buscar' => $currentBuscar,
        'ordenar' => $currentOrdenar !== 'recientes' ? $currentOrdenar : null,
    ]);
    if (!empty($canonicalParams)) {
        $canonicalUrl .= '?' . http_build_query($canonicalParams);
    }
}

// Basic schema data for SEO
$schemaData = [
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => 'Colección de Prompts de Inteligencia Artificial',
    'description' => 'Explora nuestra colección de prompts optimizados para diferentes modelos de IA.',
    'url' => $canonicalUrl,
    'breadcrumb' => [
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
                'name' => 'Prompts IA',
                'item' => route('prompts.index')
            ]
        ]
    ]
];

// Add prompts list to schema if available
if (isset($prompts) && $prompts->count() > 0) {
    $schemaData['mainEntity'] = [
        '@type' => 'ItemList',
        'numberOfItems' => $prompts->total(),
        'itemListElement' => $prompts->map(function($prompt, $index) use ($prompts) {
            return [
                '@type' => 'ListItem',
                'position' => ($prompts->currentPage() - 1) * $prompts->perPage() + $index + 1,
                'item' => [
                    '@type' => 'HowTo',
                    'name' => $prompt->title,
                    'description' => $prompt->excerpt,
                    'url' => route('prompts.show', $prompt->slug),
                ]
            ];
        })->toArray()
    ];
}

// Define available AI models for filter
$aiModels = [
    'chatgpt' => 'ChatGPT',
    'claude' => 'Claude',
    'gemini' => 'Gemini',
    'llama' => 'Llama',
    'mistral' => 'Mistral',
    'gpt-4' => 'GPT-4',
];
@endphp

@section('canonical', $canonicalUrl)

@push('head')
<script type="application/ld+json">
{!! json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
    <div class="flex h-full flex-1 flex-col gap-4 p-4 mb-24 relative z-10 max-w-7xl mx-auto">
        <!-- Header with SEO-friendly content -->
        <header class="mb-6">
            <h1 class="text-3xl font-bold text-glow-primary animate-text-glow mb-2">
                Prompts de Inteligencia Artificial
            </h1>
            <p class="text-muted-foreground">
                Explora {{ $prompts->total() }} prompts optimizados para potenciar tu creatividad con IA
            </p>
        </header>

        <!-- Filters Section with better UX -->
        <div class="glass-effect neon-border-primary rounded-xl p-4 mb-6" role="search" aria-label="Filtros de búsqueda">
            <form method="GET" action="{{ route('prompts.index') }}" id="filterForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="buscar" class="block text-sm font-medium text-foreground/80 mb-1">
                            Buscar prompts
                        </label>
                        <input 
                            type="search" 
                            id="buscar"
                            name="buscar" 
                            value="{{ $currentBuscar }}"
                            placeholder="Ej: escritura, código..."
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground 
                                   placeholder:text-foreground/50 focus:border-primary/30 focus:bg-background/70 
                                   focus:outline-none focus:ring-1 focus:ring-primary/30 transition-all duration-300"
                            aria-label="Buscar prompts"
                        >
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="categoria" class="block text-sm font-medium text-foreground/80 mb-1">
                            Categoría
                        </label>
                        <select 
                            id="categoria"
                            name="categoria"
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground 
                                   focus:border-primary/30 focus:bg-background/70 focus:outline-none focus:ring-1 
                                   focus:ring-primary/30 transition-all duration-300"
                            aria-label="Filtrar por categoría"
                        >
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ $currentCategoria == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->prompts_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tag Filter -->
                    <div>
                        <label for="etiqueta" class="block text-sm font-medium text-foreground/80 mb-1">
                            Etiqueta
                        </label>
                        <select 
                            id="etiqueta"
                            name="etiqueta"
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground 
                                   focus:border-primary/30 focus:bg-background/70 focus:outline-none focus:ring-1 
                                   focus:ring-primary/30 transition-all duration-300"
                            aria-label="Filtrar por etiqueta"
                        >
                            <option value="">Todas las etiquetas</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->slug }}" {{ $currentEtiqueta == $tag->slug ? 'selected' : '' }}>
                                    {{ $tag->name }} ({{ $tag->prompts_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- AI Model Filter -->
                    <div>
                        <label for="modelo" class="block text-sm font-medium text-foreground/80 mb-1">
                            Modelo IA
                        </label>
                        <select 
                            id="modelo"
                            name="modelo"
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground 
                                   focus:border-primary/30 focus:bg-background/70 focus:outline-none focus:ring-1 
                                   focus:ring-primary/30 transition-all duration-300"
                            aria-label="Filtrar por modelo IA"
                        >
                            <option value="">Todos los modelos</option>
                            @foreach($aiModels as $value => $label)
                                <option value="{{ $value }}" {{ $currentModelo == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label for="ordenar" class="block text-sm font-medium text-foreground/80 mb-1">
                            Ordenar por
                        </label>
                        <select 
                            id="ordenar"
                            name="ordenar"
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground 
                                   focus:border-primary/30 focus:bg-background/70 focus:outline-none focus:ring-1 
                                   focus:ring-primary/30 transition-all duration-300"
                            aria-label="Ordenar resultados"
                        >
                            <option value="recientes" {{ $currentOrdenar == 'recientes' ? 'selected' : '' }}>Más recientes</option>
                            <option value="populares" {{ $currentOrdenar == 'populares' ? 'selected' : '' }}>Más extensos</option>
                            <option value="alfabetico" {{ $currentOrdenar == 'alfabetico' ? 'selected' : '' }}>Alfabético</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button 
                        type="submit"
                        class="px-4 py-2 rounded-lg font-medium border border-primary/30 bg-primary/10 text-primary-foreground 
                               hover:bg-primary/20 focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all duration-300"
                        aria-label="Aplicar filtros"
                    >
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Aplicar filtros
                        </span>
                    </button>
                    @if($currentBuscar || $currentCategoria || $currentEtiqueta || $currentModelo || $currentOrdenar !== 'recientes')
                        <a 
                            href="{{ route('prompts.index') }}"
                            class="px-4 py-2 rounded-lg font-medium border border-border/30 bg-background/50 text-foreground 
                                   hover:bg-background/70 focus:outline-none focus:ring-2 focus:ring-border/50 transition-all duration-300"
                            aria-label="Limpiar todos los filtros"
                        >
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Limpiar filtros
                            </span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Active Filters Display -->
        @if($currentBuscar || $currentCategoria || $currentEtiqueta || $currentModelo || $currentOrdenar !== 'recientes')
        <div class="flex flex-wrap gap-2 mb-4" aria-label="Filtros activos">
            @if($currentBuscar)
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm">
                Búsqueda: "{{ $currentBuscar }}"
                <a href="{{ route('prompts.index', array_merge(request()->except('buscar'))) }}" 
                   class="hover:text-primary/80" aria-label="Eliminar filtro de búsqueda">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
            @endif
            @if($currentCategoria)
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm">
                Categoría: {{ $categories->firstWhere('slug', $currentCategoria)?->name }}
                <a href="{{ route('prompts.index', array_merge(request()->except('categoria'))) }}" 
                   class="hover:text-primary/80" aria-label="Eliminar filtro de categoría">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
            @endif
            @if($currentEtiqueta)
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm">
                Etiqueta: {{ $tags->firstWhere('slug', $currentEtiqueta)?->name }}
                <a href="{{ route('prompts.index', array_merge(request()->except('etiqueta'))) }}" 
                   class="hover:text-primary/80" aria-label="Eliminar filtro de etiqueta">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
            @endif
            @if($currentModelo)
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm">
                Modelo: {{ $aiModels[$currentModelo] ?? $currentModelo }}
                <a href="{{ route('prompts.index', array_merge(request()->except('modelo'))) }}" 
                   class="hover:text-primary/80" aria-label="Eliminar filtro de modelo">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
            @endif
            @if($currentOrdenar !== 'recientes')
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm">
                Orden: {{ $currentOrdenar == 'populares' ? 'Más extensos' : 'Alfabético' }}
                <a href="{{ route('prompts.index', array_merge(request()->except('ordenar'))) }}" 
                   class="hover:text-primary/80" aria-label="Eliminar filtro de orden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
            @endif
        </div>
        @endif
        
        <!-- Grid of Prompt Cards -->
        <main class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" role="list">
            @forelse ($prompts as $prompt)
                <article role="listitem">
                    <x-prompt-card :prompt="$prompt" />
                </article>
            @empty
                <!-- Empty State -->
                <div class="col-span-full flex flex-col items-center justify-center gap-4 p-8 text-center">
                    <div class="rounded-full p-3 bg-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <h2 class="text-xl font-semibold">No se encontraron prompts</h2>
                        <p class="text-muted-foreground">
                            @if($currentBuscar)
                                No hay resultados para "{{ $currentBuscar }}". Intenta con otros términos.
                            @else
                                Intenta ajustar los filtros de búsqueda.
                            @endif
                        </p>
                    </div>
                    @if($currentBuscar || $currentCategoria || $currentEtiqueta || $currentModelo)
                        <a 
                            href="{{ route('prompts.index') }}"
                            class="px-4 py-2 rounded-lg font-medium border border-primary/30 bg-primary/10 text-primary-foreground 
                                   hover:bg-primary/20 focus:outline-none transition-all duration-300"
                        >
                            Ver todos los prompts
                        </a>
                    @endif
                </div>
            @endforelse
        </main>
        
        <!-- Pagination -->
        @if($prompts->hasPages())
        <nav class="mt-8" aria-label="Paginación de prompts">
            {{ $prompts->links() }}
        </nav>
        @endif

        <!-- SEO Content Section -->
        <section class="mt-12 prose prose-invert max-w-none">
            <h2 class="text-2xl font-bold mb-4">Colección de Prompts de Inteligencia Artificial</h2>
            <p class="text-muted-foreground mb-4">
                Nuestra biblioteca de prompts está diseñada para ayudarte a obtener los mejores resultados 
                de los modelos de inteligencia artificial más avanzados. Desde ChatGPT hasta Claude, 
                pasando por Gemini y otros, cada prompt ha sido cuidadosamente optimizado para maximizar 
                la calidad de las respuestas y tu productividad.
            </p>
            
            @if(!$currentCategoria && !$currentEtiqueta && !$currentModelo && !$currentBuscar)
            <h3 class="text-xl font-semibold mb-4 text-primary">Categorías Populares</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-6">
                @foreach($categories->take(6) as $category)
                <a href="{{ route('prompts.index', ['categoria' => $category->slug]) }}" 
                   class="group relative flex items-center justify-between px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 hover:bg-primary/10 hover:border-primary/40 transition-all duration-300">
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary group-hover:text-primary-light transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span class="text-primary-foreground group-hover:text-primary-light font-medium">{{ $category->name }}</span>
                    </span>
                    <span class="text-primary/60 text-sm">({{ $category->prompts_count }})</span>
                </a>
                @endforeach
            </div>

            <h3 class="text-xl font-semibold mb-4 text-primary">Etiquetas Trending</h3>
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach($tags->take(15) as $tag)
                <a href="{{ route('prompts.index', ['etiqueta' => $tag->slug]) }}" 
                   class="inline-flex items-center gap-1 px-3 py-1 rounded-full border border-primary/20 bg-primary/5 hover:bg-primary/10 hover:border-primary/40 text-sm text-primary-foreground hover:text-primary-light transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                    </svg>
                    {{ $tag->slug }}
                    <span class="text-primary/60">({{ $tag->prompts_count }})</span>
                </a>
                @endforeach
            </div>
            @endif
            
            <h3 class="text-xl font-semibold mb-3">¿Por qué usar prompts optimizados?</h3>
            <p class="text-muted-foreground">
                Los prompts bien estructurados son la clave para desbloquear todo el potencial de la IA. 
                Pueden ayudarte a generar contenido más preciso, obtener respuestas más detalladas, 
                y ahorrar tiempo en iteraciones. Explora nuestra colección para encontrar el prompt 
                perfecto para tu próximo proyecto con inteligencia artificial.
            </p>
        </section>
    </div>
    
    <!-- Include neon scrollbar styles -->
    <x-neon-scrollbar />
@endsection

@push('styles')
<style>
.glass-effect {
  background-color: hsl(var(--card)) !important;
}

.neon-border-primary {
  position: relative;
  overflow: hidden;
}

.neon-border-primary::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 2px;
  background: linear-gradient(90deg, transparent, hsl(var(--primary)), transparent);
  animation: slide-primary 3s linear infinite;
}

@keyframes slide-primary {
  to {
    left: 100%;
  }
}

.text-glow-primary {
  text-shadow: 0 0 10px hsl(var(--primary) / 0.5);
}

/* Improve focus styles for accessibility */
button:focus-visible,
a:focus-visible,
select:focus-visible,
input:focus-visible {
  outline: 2px solid hsl(var(--primary));
  outline-offset: 2px;
}

/* Active filter pills animation */
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.inline-flex {
  animation: slideIn 0.3s ease-out;
}
</style>
@endpush