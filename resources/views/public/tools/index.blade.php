@extends('public.layouts.guest')

@section('title', 'Herramientas IA | Directorio de Inteligencia Artificial 2024')
@section('description', 'Descubre las mejores herramientas de inteligencia artificial. Directorio completo con herramientas IA: ChatGPT, Midjourney, Claude y más. Análisis, precios y comparativas actualizadas.')
@section('keywords', 'herramientas IA, inteligencia artificial, AI tools, machine learning, ChatGPT, Midjourney, Claude, Stable Diffusion, software IA, tecnología 2024')
@section('og-type', 'website')
@section('schema-type', 'CollectionPage')

@php
// Get current filters from request
$currentCategoria = request('categoria');
$currentModelo = request('modelo');
$currentBuscar = request('buscar');
$currentOrdenar = request('ordenar', 'recientes');

// Build canonical URL without unnecessary parameters
$canonicalUrl = route('tools.index');
if ($currentCategoria || $currentModelo || $currentBuscar || $currentOrdenar !== 'recientes') {
    $canonicalParams = array_filter([
        'categoria' => $currentCategoria,
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
    'name' => 'Directorio de Herramientas de Inteligencia Artificial',
    'description' => 'Explora nuestro directorio completo de herramientas IA, desde generadores de texto hasta creadores de imágenes.',
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
                'name' => 'Herramientas IA',
                'item' => route('tools.index')
            ]
        ]
    ]
];

// Add tools list to schema if available
if (isset($tools) && $tools->count() > 0) {
    $schemaData['mainEntity'] = [
        '@type' => 'ItemList',
        'numberOfItems' => $tools->total(),
        'itemListElement' => $tools->map(function($tool, $index) use ($tools) {
            return [
                '@type' => 'ListItem',
                'position' => ($tools->currentPage() - 1) * $tools->perPage() + $index + 1,
                'item' => [
                    '@type' => 'SoftwareApplication',
                    'name' => $tool->title,
                    'description' => $tool->excerpt,
                    'url' => route('tools.show', $tool->slug),
                    'applicationCategory' => 'Artificial Intelligence',
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => $tool->business_model === 'free' ? '0' : null,
                        'priceCurrency' => 'USD',
                    ],
                ]
            ];
        })->toArray()
    ];
}
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
            <h1 class="text-3xl font-bold text-glow animate-text-glow mb-2">
                Herramientas de Inteligencia Artificial
            </h1>
            <p class="text-muted-foreground">
                Explora {{ $tools->total() }} herramientas IA para potenciar tu productividad y creatividad
            </p>
        </header>

        <!-- Filters Section with better UX -->
        <div class="glass-effect neon-border rounded-xl p-4 mb-6" role="search" aria-label="Filtros de búsqueda">
            <form method="GET" action="{{ route('tools.index') }}" id="filterForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="buscar" class="block text-sm font-medium text-foreground/80 mb-1">
                            Buscar herramientas
                        </label>
                        <input 
                            type="search" 
                            id="buscar"
                            name="buscar" 
                            value="{{ $currentBuscar }}"
                            placeholder="Ej: ChatGPT, Midjourney..."
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground 
                                   placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 
                                   focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                            aria-label="Buscar herramientas"
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
                                   focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 
                                   focus:ring-cyan-400/30 transition-all duration-300"
                            aria-label="Filtrar por categoría"
                        >
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ $currentCategoria == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->tools_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Business Model Filter -->
                    <div>
                        <label for="modelo" class="block text-sm font-medium text-foreground/80 mb-1">
                            Modelo de negocio
                        </label>
                        <select 
                            id="modelo"
                            name="modelo"
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground 
                                   focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 
                                   focus:ring-cyan-400/30 transition-all duration-300"
                            aria-label="Filtrar por modelo de negocio"
                        >
                            <option value="">Todos los modelos</option>
                            <option value="free" {{ $currentModelo == 'free' ? 'selected' : '' }}>Gratis</option>
                            <option value="freemium" {{ $currentModelo == 'freemium' ? 'selected' : '' }}>Freemium</option>
                            <option value="paid" {{ $currentModelo == 'paid' ? 'selected' : '' }}>Pago</option>
                            <option value="subscription" {{ $currentModelo == 'subscription' ? 'selected' : '' }}>Suscripción</option>
                            <option value="one_time" {{ $currentModelo == 'one_time' ? 'selected' : '' }}>Pago único</option>
                            <option value="open_source" {{ $currentModelo == 'open_source' ? 'selected' : '' }}>Código abierto</option>
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
                                   focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 
                                   focus:ring-cyan-400/30 transition-all duration-300"
                            aria-label="Ordenar resultados"
                        >
                            <option value="recientes" {{ $currentOrdenar == 'recientes' ? 'selected' : '' }}>Más recientes</option>
                            <option value="populares" {{ $currentOrdenar == 'populares' ? 'selected' : '' }}>Más populares</option>
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
                    @if($currentBuscar || $currentCategoria || $currentModelo || $currentOrdenar !== 'recientes')
                        <a 
                            href="{{ route('tools.index') }}"
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
        @if($currentBuscar || $currentCategoria || $currentModelo || $currentOrdenar !== 'recientes')
        <div class="flex flex-wrap gap-2 mb-4" aria-label="Filtros activos">
            @if($currentBuscar)
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm">
                Búsqueda: "{{ $currentBuscar }}"
                <a href="{{ route('tools.index', array_merge(request()->except('buscar'), ['categoria' => $currentCategoria, 'modelo' => $currentModelo, 'ordenar' => $currentOrdenar])) }}" 
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
                <a href="{{ route('tools.index', array_merge(request()->except('categoria'), ['buscar' => $currentBuscar, 'modelo' => $currentModelo, 'ordenar' => $currentOrdenar])) }}" 
                   class="hover:text-primary/80" aria-label="Eliminar filtro de categoría">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
            @endif
            @if($currentModelo)
            @php
                $modelLabels = [
                    'free' => 'Gratis',
                    'freemium' => 'Freemium',
                    'paid' => 'Pago',
                    'subscription' => 'Suscripción',
                    'one_time' => 'Pago único',
                    'open_source' => 'Código abierto',
                ];
            @endphp
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm">
                Modelo: {{ $modelLabels[$currentModelo] ?? $currentModelo }}
                <a href="{{ route('tools.index', array_merge(request()->except('modelo'), ['buscar' => $currentBuscar, 'categoria' => $currentCategoria, 'ordenar' => $currentOrdenar])) }}" 
                   class="hover:text-primary/80" aria-label="Eliminar filtro de modelo">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
            @endif
        </div>
        @endif

        <!-- Results count -->
        <div class="text-sm text-muted-foreground mb-4" role="status" aria-live="polite">
            Mostrando <strong>{{ $tools->firstItem() ?? 0 }}</strong> - <strong>{{ $tools->lastItem() ?? 0 }}</strong> de <strong>{{ $tools->total() }}</strong> herramientas
            @if($currentBuscar || $currentCategoria || $currentModelo)
                (filtradas)
            @endif
        </div>

        <!-- Grid of Tool Cards -->
        <main class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" role="list">
            @forelse ($tools as $tool)
                <article role="listitem">
                    <x-tool-card :tool="$tool" />
                </article>
            @empty
                <!-- Empty State -->
                <div class="col-span-full flex flex-col items-center justify-center gap-4 p-8 text-center">
                    <div class="rounded-full p-3 bg-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <h2 class="text-xl font-semibold">No se encontraron herramientas</h2>
                        <p class="text-muted-foreground">
                            @if($currentBuscar)
                                No hay resultados para "{{ $currentBuscar }}". Intenta con otros términos.
                            @else
                                Intenta ajustar los filtros de búsqueda.
                            @endif
                        </p>
                    </div>
                    @if($currentBuscar || $currentCategoria || $currentModelo)
                        <a 
                            href="{{ route('tools.index') }}"
                            class="px-4 py-2 rounded-lg font-medium border border-primary/30 bg-primary/10 text-primary-foreground 
                                   hover:bg-primary/20 focus:outline-none transition-all duration-300"
                        >
                            Ver todas las herramientas
                        </a>
                    @endif
                </div>
            @endforelse
        </main>
        
        <!-- Pagination -->
        @if($tools->hasPages())
        <nav class="mt-8" aria-label="Paginación de herramientas">
            {{ $tools->links() }}
        </nav>
        @endif

        <!-- SEO Content Section -->
        <section class="mt-12 prose prose-invert max-w-none">
            <h2 class="text-2xl font-bold mb-4">Directorio de Herramientas de Inteligencia Artificial</h2>
            <p class="text-muted-foreground mb-4">
                Nuestro directorio de herramientas IA está diseñado para ayudarte a encontrar las mejores soluciones 
                de inteligencia artificial para tus necesidades. Desde generadores de texto como ChatGPT y Claude, 
                hasta herramientas de creación de imágenes como Midjourney y Stable Diffusion, nuestra colección 
                incluye las aplicaciones más innovadoras del mercado.
            </p>
            
            @if(!$currentCategoria && !$currentModelo && !$currentBuscar)
            <h3 class="text-xl font-semibold mb-4 text-cyan-400">Categorías Populares</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-6">
                @foreach($categories->take(6) as $category)
                <a href="{{ route('tools.index', ['categoria' => $category->slug]) }}" 
                   class="group relative flex items-center justify-between px-4 py-3 rounded-lg border border-cyan-400/20 bg-cyan-400/5 hover:bg-cyan-400/10 hover:border-cyan-400/40 transition-all duration-300">
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-cyan-400 group-hover:text-cyan-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span class="text-cyan-100 group-hover:text-cyan-50 font-medium">{{ $category->name }}</span>
                    </span>
                    <span class="text-cyan-400/60 text-sm">({{ $category->tools_count }})</span>
                </a>
                @endforeach
            </div>
            @endif
            
            <h3 class="text-xl font-semibold mb-3">¿Por qué usar herramientas de IA?</h3>
            <p class="text-muted-foreground">
                Las herramientas de inteligencia artificial están revolucionando la forma en que trabajamos, 
                creamos y resolvemos problemas. Pueden ayudarte a automatizar tareas repetitivas, generar 
                contenido creativo, analizar datos complejos y mucho más. Explora nuestro directorio para 
                descubrir cómo la IA puede potenciar tu productividad.
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when select changes for better UX
    const form = document.getElementById('filterForm');
    const selects = form.querySelectorAll('select');
    
    selects.forEach(select => {
        select.addEventListener('change', function() {
            // Optional: Auto-submit on change
            // form.submit();
        });
    });
    
    // Add loading state to form submission
    form.addEventListener('submit', function() {
        const button = form.querySelector('button[type="submit"]');
        button.disabled = true;
        button.innerHTML = '<span class="flex items-center gap-2"><svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Aplicando...</span>';
    });
});
</script>
@endpush