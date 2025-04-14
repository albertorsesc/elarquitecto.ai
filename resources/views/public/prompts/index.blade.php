@extends('public.layouts.guest')

@section('title', 'Prompts | El Arquitecto A.I.')
@section('description', 'Explora nuestra colección de prompts de IA optimizados para ayudarte a obtener los mejores resultados con diferentes modelos de IA.')
@section('keywords', 'prompts, inteligencia artificial, AI prompts, IA prompts, ChatGPT, El Arquitecto AI')
@section('og-type', 'website')
@section('schema-type', 'CollectionPage')

@php
// Define schema data for SEO component to use in the layout
$schemaData = [
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => $prompts->map(function($prompt, $index) {
            return [
                '@type' => 'ListItem',
                'position' => (int)$index + 1,
                'url' => route('prompts.show', $prompt),
                'name' => $prompt->title
            ];
        })->toArray()
    ]
];
@endphp

@section('content')
    <div class="flex h-full flex-1 flex-col gap-4 p-4 mb-24 relative z-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-glow animate-text-glow">Prompts</h1>
        </div>

        <!-- Grid of Prompt Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($prompts as $prompt)
                <x-prompt-card :prompt="$prompt" />
            @endforeach
        </div>
        
        <!-- Empty State (shown when no prompts) -->
        @if(count($prompts) == 0)
        <div class="col-span-full flex flex-col items-center justify-center gap-4 p-8 text-center">
            <div class="rounded-full p-3 bg-muted">
                <div class="h-10 w-10 text-muted-foreground"></div>
            </div>
            <div class="space-y-2">
                <h3 class="text-xl font-semibold">No prompts found</h3>
                <p class="text-muted-foreground">Check back later for new prompts.</p>
            </div>
        </div>
        @endif
        
        <div class="mt-8">
            {{ $prompts->links() }}
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