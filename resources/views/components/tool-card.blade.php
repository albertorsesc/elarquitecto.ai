@props(['tool'])

@php
    // Get business model label and color
    $businessModelLabel = $tool->business_model->label();
    $businessModelColor = $tool->business_model->color();
    
    // Generate URL
    $url = route('tools.show', $tool->slug);
    
    // Get first category name if exists
    $categoryName = $tool->categories->first()?->name ?? 'Sin categoría';
    
    // Check if has tags
    $hasTags = $tool->tags && $tool->tags->count() > 0;
    
    // Get date
    $date = $tool->published_at ? $tool->published_at->isoFormat('D [de] MMM, YYYY') : $tool->created_at->isoFormat('D [de] MMM, YYYY');
@endphp

<div class="group relative h-full overflow-hidden rounded-xl border border-cyan-400/20 glass-effect transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_25px_rgba(0,255,225,0.3)] hover:border-cyan-400/40">
    <!-- Card Image -->
    <div class="relative h-44 w-full overflow-hidden">
        <img src="{{ $tool->featured_image_url ?? $tool->featured_image ?? '/img/logo.webp' }}" alt="{{ $tool->title }}"
            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" 
            loading="lazy" />
        
        <!-- Business Model Badge - Top Left -->
        <div class="absolute top-3 left-3 z-10">
            <span class="px-3 py-1.5 text-xs rounded-full font-semibold backdrop-blur-md border
                @if($businessModelColor === 'green') bg-green-500/20 text-green-400 border-green-500/30 shadow-[0_0_10px_rgba(34,197,94,0.3)]
                @elseif($businessModelColor === 'blue') bg-blue-500/20 text-blue-400 border-blue-500/30 shadow-[0_0_10px_rgba(59,130,246,0.3)]
                @elseif($businessModelColor === 'red') bg-red-500/20 text-red-400 border-red-500/30 shadow-[0_0_10px_rgba(239,68,68,0.3)]
                @elseif($businessModelColor === 'purple') bg-purple-500/20 text-purple-400 border-purple-500/30 shadow-[0_0_10px_rgba(168,85,247,0.3)]
                @elseif($businessModelColor === 'orange') bg-orange-500/20 text-orange-400 border-orange-500/30 shadow-[0_0_10px_rgba(251,146,60,0.3)]
                @elseif($businessModelColor === 'cyan') bg-cyan-500/20 text-cyan-400 border-cyan-500/30 shadow-[0_0_10px_rgba(6,182,212,0.3)]
                @endif">
                {{ $businessModelLabel }}
            </span>
        </div>

        <!-- Featured Star - Top Right -->
        @if($tool->is_featured)
        <div class="absolute top-3 right-3 z-10">
            <div class="p-2 bg-yellow-500/20 backdrop-blur-md rounded-full border border-yellow-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
        </div>
        @endif

        <!-- Category Badge - Bottom -->
        <div class="absolute bottom-3 left-3 z-10">
            <span class="px-2.5 py-1 text-xs rounded-full bg-black/60 backdrop-blur-sm text-cyan-400 border border-cyan-400/40 font-medium">
                {{ $categoryName }}
            </span>
        </div>
        
        <!-- Simple gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent pointer-events-none"></div>
    </div>
    
    <!-- Card Content -->
    <div class="flex flex-col gap-2.5 p-4">
        <h3 class="text-lg font-bold line-clamp-1 group-hover:text-cyan-400 transition-colors duration-300">
            {{ $tool->title }}
        </h3>
        
        <p class="text-sm text-muted-foreground line-clamp-2 min-h-[2.5rem]">
            {{ $tool->excerpt ?? 'Sin descripción disponible' }}
        </p>
        
        <!-- Tags with Tech Style -->
        @if($hasTags)
        <div class="flex flex-wrap gap-1.5 mt-1">
            @foreach($tool->tags->take(3) as $tag)
                <span class="text-xs px-2 py-0.5 rounded-md bg-cyan-500/10 text-cyan-400/80 border border-cyan-500/20">
                    #{{ $tag->name }}
                </span>
            @endforeach
            @if($tool->tags->count() > 3)
                <span class="text-xs px-2 py-0.5 rounded-md bg-cyan-500/5 text-cyan-400/60 border border-cyan-500/10">
                    +{{ $tool->tags->count() - 3 }}
                </span>
            @endif
        </div>
        @endif
        
        <!-- Footer with Tech Info -->
        <div class="mt-auto pt-3 flex items-center justify-between text-xs text-muted-foreground border-t border-cyan-400/10">
            <span class="flex items-center gap-2">
                @if($tool->website_url)
                <span class="flex items-center gap-1 text-cyan-500/70">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                    Online
                </span>
                @endif
                @if($tool->documentation_url)
                <span class="flex items-center gap-1 text-purple-500/70">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Docs
                </span>
                @endif
            </span>
            <span class="text-cyan-600/60">{{ $date }}</span>
        </div>

        <!-- Action Button - Simplified and ensuring it's clickable -->
        <a 
            href="{{ $url }}" 
            class="mt-3 block w-full px-3 py-2.5 text-center text-sm rounded-lg font-semibold 
                   bg-gradient-to-r from-cyan-500/10 to-blue-500/10 
                   border border-cyan-400/30 text-cyan-400 
                   hover:from-cyan-500/20 hover:to-blue-500/20 
                   hover:border-cyan-400/50 hover:text-cyan-300
                   hover:shadow-[0_0_15px_rgba(0,255,225,0.3)]
                   focus:outline-none focus:ring-2 focus:ring-cyan-400/30
                   transition-all duration-300"
        >
            <span class="flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Explorar Herramienta
            </span>
        </a>
    </div>
</div>