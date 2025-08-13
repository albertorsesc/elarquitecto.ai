@extends('public.layouts.guest')

@section('title', 'Resources')
@section('description', 'Explora nuestra colección de recursos de IA optimizados para ayudarte a obtener los mejores resultados con diferentes modelos de IA.')
@section('keywords', 'recursos, inteligencia artificial, AI resources, IA recursos, ChatGPT, El Arquitecto AI')
@section('og-type', 'website')
@section('schema-type', 'CollectionPage')

@php
// Define schema data for SEO component to use in the layout
$schemaData = [
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => $resources->map(function($prompt, $index) {
            return [
                '@type' => 'ListItem',
                'position' => (int)$index + 1,
                'url' => route('resources.show', $prompt),
                'name' => $prompt->title
            ];
        })->toArray()
    ]
];
@endphp

@section('content')
    <div class="flex h-full flex-1 flex-col gap-4 p-4 mb-24 relative z-10 max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-glow animate-text-glow">Resources</h1>
        </div>

        <!-- Grid of Resource Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($resources as $prompt)
                <x-prompt-card :prompt="$prompt" />
            @endforeach
        </div>
        
        <!-- Empty State (shown when no resources) -->
        @if(count($resources) == 0)
        <div class="col-span-full flex flex-col items-center justify-center gap-4 p-8 text-center">
            <div class="rounded-full p-3 bg-muted">
                <div class="h-10 w-10 text-muted-foreground"></div>
            </div>
            <div class="space-y-2">
                <h3 class="text-xl font-semibold">No hay recursos disponibles</h3>
                <p class="text-muted-foreground">Revisa más tarde para nuevos recursos.</p>
            </div>
        </div>
        @endif
        
        <div class="mt-8">
            {{-- {{ $resources->links() }} --}}
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