@props(['article'])

@php
    // Generate URL
    $url = route('articles.show', $article);
    
    // Create excerpt
    $excerpt = $article->excerpt ?? 
        (strlen($article->body) > 150 ? 
        substr(strip_tags($article->body), 0, 150) . '...' : 
        strip_tags($article->body));
@endphp

<div class="article-card-container group">
    <!-- Reading Time Indicator -->
    <div class="article-card-reading-time">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ $article->reading_time ?? 1 }} minutos de lectura</span>
    </div>

    <!-- Card Image -->
    <div class="article-card-image">
        <img src="{{ $article->hero_image_url ?? '/img/logo.png' }}" alt="{{ $article->title ?? 'Article' }}" />
        
        <!-- Status Indicators -->
        <div class="article-card-gradient-overlay">
            <!-- Featured Badge -->
            @if($article->is_featured)
                <div class="article-card-badge article-card-featured-badge">
                    Featured
                </div>
            @else
                <div class="w-8"></div>
            @endif
            
            <div class="article-card-badge article-card-status-badge">
                Nuevo
            </div>
        </div>

        <!-- Pinned Indicator -->
        @if($article->is_pinned)
            <div class="article-card-pin-ribbon">
                <div class="article-card-pin-ribbon-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-1 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Card Content -->
    <div class="article-card-content">
        <h3 class="article-card-title">
            {{ $article->title ?? 'Untitled' }}
        </h3>
        
        <p class="article-card-excerpt">
            {{ $excerpt }}
        </p>
        
        <!-- Categories and Tags -->
        <div class="article-card-tags-container">
            <div class="flex flex-wrap gap-1">
                <span class="article-card-category">
                    {{ $article->category->first()->slug }}
                </span>
            </div>
            <div class="flex flex-wrap gap-1">
                @foreach($article->tags as $tag)
                    <span class="article-card-tag">
                        {{ $tag->slug }}
                    </span>
                @endforeach
            </div>
        </div>
        
        <!-- Footer -->
        <div class="article-card-footer">
            <span class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>{{ $article->view_count ?? 0 }}</span>
            </span>
            <span>{{ $article->published_at->isoFormat('D [de] MMM, YYYY') }}</span>
        </div>

        <!-- View Link -->
        <div class="article-card-action">
            <div class="article-card-button-shine">
                <x-cyber-link 
                    :href="$url" 
                    variant="outline" 
                    size="sm" 
                    :fullWidth="true">
                    Leer Artículo
                </x-cyber-link>
            </div>
        </div>
    </div>
</div>