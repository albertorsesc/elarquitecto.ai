@props(['prompt'])

@php
    $isObject = is_object($prompt);
    
    // Helper function to get value in either object or array format
    $get = function($key, $default = null) use ($prompt, $isObject) {
        if ($isObject) {
            if ($key === 'image') {
                return $prompt->image_url ?? $prompt->image ?? $default;
            }
            return $prompt->{$key} ?? $default;
        } else {
            if ($key === 'image_url') {
                return $prompt['image'] ?? $default;
            }
            return $prompt[$key] ?? $default;
        }
    };
    
    // Helper for more complex nested properties
    $hasCategory = $isObject 
        ? ($prompt->category && count($prompt->category) > 0) 
        : (isset($prompt['model']) && isset($prompt['model']['category']) && count($prompt['model']['category']) > 0);
        
    $categoryName = $isObject 
        ? ($hasCategory ? $prompt->category[0]->name : 'Uncategorized') 
        : (isset($prompt['type']) ? ucfirst($prompt['type']) : 'Uncategorized');
        
    $hasTags = $isObject 
        ? ($prompt->tags && count($prompt->tags) > 0) 
        : (
            (isset($prompt['tags']) && count($prompt['tags']) > 0) || 
            (isset($prompt['model']) && isset($prompt['model']['tags']) && count($prompt['model']['tags']) > 0)
        );
        
    $tags = $isObject 
        ? ($prompt->tags ?? []) 
        : ($prompt['tags'] ?? ($prompt['model']['tags'] ?? []));

    $url = $isObject 
        ? route('prompts.show', $prompt) 
        : ($prompt['url'] ?? '/');
        
    $wordCount = $isObject
        ? ($prompt->word_count ?? (isset($prompt->content) ? count(explode(' ', $prompt->content)) : 0))
        : ($prompt['word_count'] ?? 0);
        
    // Use Carbon for date formatting with system locale
    if ($isObject) {
        // For object prompts, we have direct access to the Carbon instance
        $date = $prompt->published_at ?? $prompt->created_at;
    } else {
        if (isset($prompt['date'])) {
            // For array prompts with date string, parse it with Carbon
            $date = $prompt['date'];
        }
    }
@endphp

<div class="group h-full overflow-hidden rounded-xl border border-border/50 glass-effect transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_15px_rgba(var(--primary-rgb),0.3)]">
    <!-- Card Image -->
    <div class="relative h-40 w-full overflow-hidden">
        <img src="{{ $get('image', '/img/logo.png') }}" alt="{{ $get('title', 'Prompt') }}"
            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
        
        <!-- Category Badge -->
        <div class="absolute top-3 right-3 bg-black/50 backdrop-blur-sm text-xs font-medium px-2 py-1 rounded-full border border-primary/30 text-primary animate-pulse-slow">
            {{ $categoryName }}
        </div>
    </div>
    
    <!-- Card Content -->
    <div class="flex flex-col gap-2 p-4">
        <h3 class="text-lg font-semibold line-clamp-1 group-hover:text-primary transition-colors">
            {{ $get('title', 'Untitled') }}
        </h3>
        
        <p class="text-sm text-muted-foreground line-clamp-2">
            {{ $get('excerpt', $get('content', 'No description available')) }}
        </p>
        
        <!-- Tags -->
        @if($hasTags)
        <div class="flex flex-wrap gap-1 mt-1">
            @foreach($tags as $tag)
                <span class="text-xs px-2 py-0.5 rounded-full bg-sidebar-accent text-sidebar-accent-foreground">
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
        
        <!-- Footer -->
        <div class="mt-3 flex items-center justify-between text-xs text-muted-foreground">
            <span class="flex items-center gap-1">
                <span>{{ $wordCount }} palabras</span>
            </span>
            <span>{{ $date }}</span>
        </div>

        <!-- View Link -->
        <div class="mt-3 pt-3 border-t border-border/30">
            @if($isObject)
                <div class="neon-border rounded-md overflow-hidden">
                    <x-cyber-link 
                        :href="$url" 
                        variant="outline" 
                        size="sm" 
                        :fullWidth="true">
                        Ver Detalles
                    </x-cyber-link>
                </div>
            @else
                <div class="neon-border rounded-md overflow-hidden">
                    <a href="{{ $url }}" 
                       class="block w-full text-center py-1.5 px-3 text-sm bg-primary/10 hover:bg-primary/20 text-primary transition-colors">
                      Ver Detalles
                    </a>
                </div>
            @endif
        </div>
    </div>
</div> 