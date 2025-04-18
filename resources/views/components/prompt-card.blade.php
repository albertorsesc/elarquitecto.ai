@props(['prompt'])

@php
    // Generate URL
    $url = route('prompts.show', $prompt);
    
    // Get category name
    $categoryName = $prompt->category && count($prompt->category) > 0 
        ? $prompt->category[0]->name 
        : 'Uncategorized';
        
    // Check if has tags
    $hasTags = $prompt->tags && count($prompt->tags) > 0;
    
    // Date
    $date = $prompt->published_at->isoFormat('D [de] MMM, YYYY') ?? $prompt->created_at->isoFormat('D [de] MMM, YYYY');
@endphp

<div class="group h-full overflow-hidden items-center rounded-xl border border-border/50 glass-effect transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_15px_rgba(var(--primary-rgb),0.3)]">
    <!-- Card Image -->
    <div class="relative h-40 w-full overflow-hidden items-center">
        <!-- Category Badge - Now in top-left -->
        <div class="absolute top-2 left-2 bg-black/50 backdrop-blur-sm text-xs font-medium px-2 py-1 rounded-full text-primary z-10 flex items-center gap-1">
            {{ $categoryName }}
        </div>

        <img src="{{ $prompt->image_url ?? '/img/logo.webp' }}" alt="{{ $prompt->title ?? 'Prompt' }}"
            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" 
            loading="lazy" />
        
        <!-- Reading Time Indicator - Now centered at bottom of image -->
        <div class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-black/50 backdrop-blur-sm text-xs font-medium px-2 py-1 rounded-full border border-primary/30 text-primary animate-pulse-slow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $prompt->reading_time ?? 1 }} minutos de lectura
        </div>
    </div>
    
    <!-- Card Content -->
    <div class="flex flex-col gap-2 p-4">
        <h3 class="text-lg font-semibold line-clamp-1 group-hover:text-primary transition-colors">
            {{ $prompt->title ?? 'Untitled' }}
        </h3>
        
        <p class="text-sm text-muted-foreground line-clamp-2">
            {{ $prompt->excerpt ?? $prompt->content ?? 'No description available' }}
        </p>
        
        <!-- Tags -->
        @if($hasTags)
        <div class="flex flex-wrap gap-1 mt-1">
            @foreach($prompt->tags as $tag)
                <span class="text-xs px-2 py-0.5 rounded-full bg-sidebar-accent text-sidebar-accent-foreground">
                    {{ $tag->name }}
                </span>
            @endforeach
        </div>
        @endif
        
        <!-- Footer -->
        <div class="mt-3 flex items-center justify-between text-xs text-muted-foreground">
            <span class="flex items-center gap-1">
                <span>{{ $prompt->word_count }} palabras</span>
            </span>
            <span>{{ $date }}</span>
        </div>

        <!-- View Link -->
        <div class="mt-3 pt-3 border-t border-border/30">
            <div class="neon-border rounded-md overflow-hidden">
                <x-cyber-link 
                    :href="$url" 
                    variant="outline" 
                    size="sm" 
                    :fullWidth="true">
                    Ver Detalles
                </x-cyber-link>
            </div>
        </div>
    </div>
</div> 