@extends('public.layouts.guest')

@section('title', 'Artículos | El Arquitecto A.I.')
@section('description', 'Explora nuestra colección de artículos sobre inteligencia artificial, programación y tecnología.')
@section('keywords', 'artículos, inteligencia artificial, AI, IA, tecnología, El Arquitecto AI')
@section('og-type', 'website')
@section('schema-type', 'CollectionPage')

@php
// Define schema data for SEO component to use in the layout
$schemaData = [
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => $articles->map(function($article, $index) {
            return [
                '@type' => 'ListItem',
                'position' => (int)$index + 1,
                'url' => route('articles.show', $article),
                'name' => $article->title
            ];
        })->toArray()
    ]
];
@endphp

@section('content')
    <div class="flex h-full flex-1 flex-col gap-4 p-4 mb-24 relative z-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-glow animate-text-glow">Artículos</h1>
        </div>

        <!-- Grid of Article Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($articles as $article)
                <x-article-card :article="$article" />
            @endforeach
        </div>
        
        <!-- Empty State (shown when no articles) -->
        @if(count($articles) == 0)
        <div class="col-span-full flex flex-col items-center justify-center gap-4 p-8 text-center">
            <div class="rounded-full p-3 bg-muted">
                <div class="h-10 w-10 text-muted-foreground"></div>
            </div>
            <div class="space-y-2">
                <h3 class="text-xl font-semibold">No hay artículos disponibles</h3>
                <p class="text-muted-foreground">Vuelve más tarde para encontrar nuevos artículos.</p>
            </div>
        </div>
        @endif
        
        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    </div>
    
    <!-- Include neon scrollbar styles -->
    <x-neon-scrollbar />
@endsection

@push('styles')
<style>
/* Ensure the glass effect maintains transparency in dark mode */
.glass-effect {
  background-color: hsl(var(--card)) !important;
}
</style>
@endpush 