@props(['article'])

@php
    $isObject = is_object($article);
    
    // Helper function to get value in either object or array format
    $get = function($key, $default = null) use ($article, $isObject) {
        if ($isObject) {
            if ($key === 'image') {
                return $article->hero_image_url ?? $default;
            }
            return $article->{$key} ?? $default;
        } else {
            if ($key === 'hero_image_url') {
                return $article['image'] ?? $default;
            }
            return $article[$key] ?? $default;
        }
    };
    
    // Helper for more complex nested properties
    $hasCategory = $isObject 
        ? ($article->category && count($article->category) > 0) 
        : (isset($article['category']) && count($article['category']) > 0);
        
    $hasTags = $isObject 
        ? ($article->tags && count($article->tags) > 0) 
        : (isset($article['tags']) && count($article['tags']) > 0);
        
    $tags = $isObject 
        ? ($article->tags ?? []) 
        : ($article['tags'] ?? []);

    $categories = $isObject 
        ? ($article->category ?? []) 
        : ($article['category'] ?? []);

    $url = $isObject 
        ? route('articles.show', $article->slug) 
        : ($article['url'] ?? route('articles.show', $article['slug'] ?? 'unknown'));
        
    // Reading time calculation
    $body = $isObject ? ($article->body ?? '') : ($article['body'] ?? '');
    $wordCount = str_word_count(strip_tags($body));
    $readingTime = max(1, ceil($wordCount / 225));
    
    // Format date
    if ($isObject) {
        $publishedAt = $article->published_at ?? null;
        $formattedDate = $publishedAt ? $publishedAt->format('M j, Y') : 'Draft';
    } else {
        $publishedAt = $article['published_at'] ?? null;
        $formattedDate = $publishedAt ? date('M j, Y', strtotime($publishedAt)) : 'Draft';
    }
    
    // Create excerpt
    $excerpt = $body ? (strlen($body) > 150 ? substr(strip_tags($body), 0, 150) . '...' : strip_tags($body)) : 'No content available';
    
    // Get boolean values
    $isPinned = $isObject ? ($article->is_pinned ?? false) : ($article['is_pinned'] ?? false);
    $isFeatured = $isObject ? ($article->is_featured ?? false) : ($article['is_featured'] ?? false);
    $viewCount = $isObject ? ($article->view_count ?? 0) : ($article['view_count'] ?? 0);
@endphp

<div class="article-card-container group">
    <!-- Reading Time Indicator -->
    <div class="article-card-reading-time">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ $readingTime }} min read</span>
    </div>

    <!-- Card Image -->
    <div class="article-card-image">
        <img src="{{ $get('hero_image_url', '/img/logo.png') }}" alt="{{ $get('title', 'Article') }}" />
        
        <!-- Status Indicators -->
        <div class="article-card-gradient-overlay">
            <!-- Featured Badge -->
            @if($isFeatured)
                <div class="article-card-badge article-card-featured-badge">
                    Featured
                </div>
            @else
                <div class="w-8"></div>
            @endif
            
            <!-- Publish Status -->
            <div class="article-card-badge article-card-status-badge">
                {{ $formattedDate }}
            </div>
        </div>

        <!-- Pinned Indicator -->
        @if($isPinned)
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
            {{ $get('title', 'Untitled') }}
        </h3>
        
        <p class="article-card-excerpt">
            {{ $excerpt }}
        </p>
        
        <!-- Categories and Tags -->
        <div class="article-card-tags-container">
            @if($hasCategory)
                <div class="flex flex-wrap gap-1">
                    @foreach($categories as $category)
                        <span class="article-card-category">
                            @if($isObject)
                                {{ $category->name }}
                            @elseif(is_array($category) && isset($category['name']))
                                {{ $category['name'] }}
                            @else
                                {{ $category }}
                            @endif
                        </span>
                    @endforeach
                </div>
            @endif
            
            @if($hasTags)
                <div class="flex flex-wrap gap-1">
                    @foreach($tags as $tag)
                        <span class="article-card-tag">
                            @if($isObject)
                                {{ $tag->name }}
                            @elseif(is_array($tag) && isset($tag['name']))
                                {{ $tag['name'] }}
                            @else
                                {{ $tag }}
                            @endif
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="article-card-footer">
            <span class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>{{ $viewCount }}</span>
            </span>
            <span>{{ $formattedDate }}</span>
        </div>

        <!-- View Link -->
        <div class="article-card-action">
            <div class="article-card-button-shine">
                <x-cyber-link 
                    :href="$url" 
                    variant="outline" 
                    size="sm" 
                    :fullWidth="true">
                    Read Article
                </x-cyber-link>
            </div>
        </div>
    </div>
</div> 