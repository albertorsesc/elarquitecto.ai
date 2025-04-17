@extends('public.layouts.guest')

@section('title', $article->title . ' | El Arquitecto AI')
@section('description', strlen($article->body) > 150 ? substr(strip_tags($article->body), 0, 150) . '...' : strip_tags($article->body))
@section('keywords')
{{ $article->tags->pluck('name')->join(', ') }}, artículos, inteligencia artificial, AI, IA
@endsection
@section('content-for-seo', $article->body)
@section('og-type', 'article')
@section('schema-type', 'Article')

@php
// Define schema data for SEO component to use in the layout
$schemaData = [
    'published_at' => $article->published_at ?? $article->created_at,
    'updated_at' => $article->updated_at,
    'author' => $article->author ? $article->author->name : 'Alberto Rosas',
    'category' => $article->category && $article->category->count() > 0 ? $article->category[0]->name : null,
    'tags' => $article->tags->pluck('name')->toArray()
];
@endphp

@section('content')
    <div class="flex flex-col gap-6 mb-24 mx-auto max-w-5xl">
        <!-- Main Content Card -->
        <div class="relative glass-effect border border-border/50 rounded-xl p-6 shadow-[0_0_15px_rgba(var(--primary-rgb),0.3)]">
            <!-- Animation wrapper to isolate animations -->
            <div class="neon-border-wrapper">
                <!-- Basic Neon Border -->
                <x-neon-border :hoverEffect="false" opacity="50" />
                
                <!-- Multi-colored sliding neon lights -->
                <x-multi-color-sliding-neon 
                    topColor="#FF1CF7" 
                    rightColor="#00FFE1" 
                    bottomColor="#01FF88" 
                    leftColor="#5B6EF7" 
                    opacity="30" 
                    :hoverEffect="false" 
                />
                
                <!-- Breathing Glow -->
                <x-breathing-glow :hoverEffect="false" opacity="15" />
            </div>

            <!-- Hero Image (if available) -->
            @if($article->hero_image_url)
                <div class="w-full h-64 md:h-80 mb-6 overflow-hidden rounded-lg relative">
                    <img 
                        src="{{ $article->hero_image_url }}" 
                        alt="{{ $article->title }}" 
                        class="w-full h-full object-cover"
                        loading="lazy"
                    />
                    <!-- Status Indicators -->
                    <div class="absolute bottom-0 left-0 right-0 p-3 flex justify-between items-center bg-gradient-to-t from-black/80 to-transparent">
                        <!-- Featured Badge -->
                        @if($article->is_featured)
                            <div class="article-card-badge article-card-featured-badge">
                                Destacado
                            </div>
                        @endif
                        
                        <!-- Pinned Indicator -->
                        @if($article->is_pinned)
                            <div class="article-card-badge bg-primary/90 text-white">
                                Destacado
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Header with Meta -->
            <div class="mb-6 flex flex-col gap-2">
                <h1 class="text-3xl font-bold text-glow animate-text-glow">{{ $article->title }}</h1>
                
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mt-2">
                    <!-- Left side meta -->
                    <div class="flex flex-wrap items-center gap-2 text-sm text-muted-foreground">
                        <!-- Reading time -->
                        <div class="flex items-center gap-1 mr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $article->reading_time }} min de lectura</span>
                        </div>
                        
                        <!-- View count - hidden for now as requested -->
                        {{-- <div class="flex items-center gap-1 mr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>{{ $article->view_count }} visitas</span>
                        </div> --}}
                        
                        <!-- Category -->
                        @if($article->category && count($article->category) > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/30">
                                {{ $article->category[0]->name }}
                            </span>
                        @endif
                    </div>
                    
                    <!-- Right side date and author -->
                    <div class="flex flex-col md:items-end text-sm">
                        <span class="text-muted-foreground">
                            {{ ($article->published_at ?? $article->created_at)->isoFormat('D [de] MMM, YYYY') }}
                        </span>
                        <span class="text-primary">
                            Por {{ $article->author ? $article->author->name : 'Alberto Rosas' }}
                        </span>
                    </div>
                </div>
                
                <!-- Tags -->
                @if($article->tags && count($article->tags) > 0)
                <div class="flex flex-wrap gap-1 mt-2">
                    @foreach($article->tags as $tag)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-sidebar-accent text-sidebar-accent-foreground">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
                @endif
            </div>
            
            <!-- Article Content -->
            <div class="prose prose-invert prose-custom max-w-none mb-8 border-t border-border/30 pt-6">
                {!! md_to_html($article->body) !!}
            </div>
            
            <!-- Social Sharing -->
            <div class="mb-6 pt-4 border-t border-border/30">
                <div class="text-center mb-2 text-sm text-muted-foreground">Compartir este artículo</div>
                <div class="flex flex-wrap justify-center gap-3">
                    <!-- Copy Link Button -->
                    <button
                       onclick="copyArticleLink()"
                       aria-label="Copiar enlace"
                       class="social-share-btn inline-flex items-center justify-center rounded-md font-medium h-10 px-3 sm:px-4 py-2 border border-border/50 hover:bg-primary/10 hover:text-primary transition-colors copy-link-btn">
                        <i class="fas fa-share-alt text-glow-multi sm:mr-2"></i>
                        <span class="hidden sm:inline">Copiar enlace</span>
                    </button>
                
                    <!-- Twitter/X Share -->
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('articles.show', $article)) }}&text={{ urlencode($article->title) }}" 
                       target="_blank" aria-label="Compartir en Twitter"
                       class="social-share-btn inline-flex items-center justify-center rounded-md font-medium h-10 px-3 sm:px-4 py-2 border border-border/50 hover:bg-primary/10 hover:text-primary transition-colors">
                        <i class="fab fa-twitter text-glow-multi sm:mr-2"></i>
                        <span class="hidden sm:inline">Twitter</span>
                    </a>
                    
                    <!-- Facebook Share -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articles.show', $article)) }}"
                       target="_blank" aria-label="Compartir en Facebook"
                       class="social-share-btn inline-flex items-center justify-center rounded-md font-medium h-10 px-3 sm:px-4 py-2 border border-border/50 hover:bg-primary/10 hover:text-primary transition-colors">
                        <i class="fab fa-facebook text-glow-multi sm:mr-2"></i>
                        <span class="hidden sm:inline">Facebook</span>
                    </a>
                    
                    <!-- LinkedIn Share -->
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('articles.show', $article)) }}&title={{ urlencode($article->title) }}"
                       target="_blank" aria-label="Compartir en LinkedIn"
                       class="social-share-btn inline-flex items-center justify-center rounded-md font-medium h-10 px-3 sm:px-4 py-2 border border-border/50 hover:bg-primary/10 hover:text-primary transition-colors">
                        <i class="fab fa-linkedin text-glow-multi sm:mr-2"></i>
                        <span class="hidden sm:inline">LinkedIn</span>
                    </a>
                </div>
            </div>
            
            <!-- Add JavaScript for copy link functionality -->
            <script>
            function copyArticleLink() {
                // Get the current URL
                const url = window.location.href;
                
                // Use the modern Clipboard API if available
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url)
                        .then(() => showCopySuccess())
                        .catch(() => fallbackCopyMethod(url));
                } else {
                    fallbackCopyMethod(url);
                }
            }
            
            function fallbackCopyMethod(text) {
                // Create a temporary input element
                const input = document.createElement('input');
                input.style.position = 'absolute';
                input.style.left = '-9999px';
                input.value = text;
                document.body.appendChild(input);
                
                // Select and copy the link
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);
                
                showCopySuccess();
            }
            
            function showCopySuccess() {
                // Show visual feedback
                const btn = document.querySelector('.copy-link-btn');
                btn.classList.add('copy-success');
                
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check text-green-400 sm:mr-2 animate-pulse"></i><span class="hidden sm:inline">¡Copiado!</span>';
                
                // Reset the button after a delay
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.classList.remove('copy-success');
                }, 2000);
            }
            </script>
            
            <!-- Back Button -->
            <div class="mt-8 pt-4 border-t border-border/30">
                <x-cyber-button
                    href="{{ route('articles.index') }}"
                    variant="link"
                    size="sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Volver a Artículos
                </x-cyber-button>
            </div>
        </div>
        
        <!-- Related Articles -->
        @if ($relatedArticles->count() > 0)
            <div class="mt-8">
                <h3 class="text-xl font-semibold mb-6 text-foreground">
                    También te podría interesar
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($relatedArticles as $relatedArticle)
                        <x-article-card :article="$relatedArticle" />
                    @endforeach
                </div>
            </div>
        @endif
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

/* Prose custom styling for better images */
.prose-custom img:not(.markdown-image) {
  @apply rounded-lg mx-auto my-6 max-w-full;
  max-width: 800px;
  box-shadow: 0 0 15px rgba(var(--primary-rgb), 0.3);
}

/* Article meta information responsive styles */
@media (max-width: 768px) {
  /* Add spacing between date and other meta info on mobile */
  .flex-col.md\:flex-row.md\:items-center.md\:justify-between.gap-3 {
    gap: 0.75rem;
  }
  
  /* Ensure date and author align properly */
  .flex-col.md\:items-end.text-sm {
    margin-bottom: 0.5rem;
  }
  
  /* Improve spacing for wrapped meta items */
  .flex.flex-wrap.items-center.gap-2 {
    margin-bottom: 0.25rem;
  }
}
</style>
@endpush 