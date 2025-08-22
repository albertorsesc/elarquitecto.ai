@props(['prompt'])

@php
    // Generate URL
    $url = route('prompts.show', $prompt);
    
    // Get category name safely
    $categoryName = null;
    if ($prompt->category) {
        $category = $prompt->category->first();
        if ($category) {
            $categoryName = $category->name;
        }
    }
    $categoryName = $categoryName ?: 'General';
        
    // Check if has tags
    $hasTags = $prompt->tags && count($prompt->tags) > 0;
    
    // Get target models
    $targetModels = $prompt->target_models ?? [];
    
    // Date
    $date = $prompt->published_at ? $prompt->published_at->isoFormat('D [de] MMM, YYYY') : $prompt->created_at->isoFormat('D [de] MMM, YYYY');
    
    // Model icons/colors
    $modelStyles = [
        'chatgpt' => ['icon' => '🤖', 'color' => 'text-green-400'],
        'claude' => ['icon' => '🎭', 'color' => 'text-purple-400'],
        'gemini' => ['icon' => '✨', 'color' => 'text-blue-400'],
        'gpt-4' => ['icon' => '🧠', 'color' => 'text-emerald-400'],
        'llama' => ['icon' => '🦙', 'color' => 'text-orange-400'],
        'mistral' => ['icon' => '🌪️', 'color' => 'text-indigo-400'],
    ];
@endphp

<a href="{{ $url }}" class="group block h-full">
    <div class="relative h-full overflow-hidden rounded-xl border border-primary/20 bg-gradient-to-br from-primary/5 via-background to-primary/5 transition-all duration-300 hover:border-primary/40 hover:shadow-[0_0_20px_rgba(var(--primary-rgb),0.4)] hover:scale-[1.02]">
        <!-- Animated border effect -->
        <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
            <div class="absolute inset-[-2px] rounded-xl bg-gradient-to-r from-primary via-purple-500 to-primary animate-gradient-border"></div>
            <div class="absolute inset-0 rounded-xl bg-background"></div>
        </div>
        
        <!-- Card Content -->
        <div class="relative flex flex-col h-full p-4 gap-3">
            <!-- Header with Category and Models -->
            <div class="flex items-start justify-between gap-2">
                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-primary/10 text-primary border border-primary/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    {{ $categoryName }}
                </span>
                
                @if(!empty($targetModels))
                <div class="flex gap-1">
                    @foreach($targetModels as $model)
                        @if(isset($modelStyles[$model]))
                        <span class="text-lg {{ $modelStyles[$model]['color'] }}" title="{{ ucfirst($model) }}">
                            {{ $modelStyles[$model]['icon'] }}
                        </span>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
            
            <!-- Title with typewriter effect on hover -->
            <h3 class="text-lg font-bold text-foreground group-hover:text-primary transition-colors duration-300 line-clamp-2">
                {{ $prompt->title ?? 'Untitled' }}
            </h3>
            
            <!-- Excerpt -->
            <p class="text-sm text-muted-foreground line-clamp-3 flex-grow">
                {{ $prompt->excerpt ?? Str::limit($prompt->content, 120) ?? 'No description available' }}
            </p>
            
            <!-- Tags with neon effect -->
            @if($hasTags)
            <div class="flex flex-wrap gap-1">
                @foreach($prompt->tags->take(3) as $tag)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full bg-primary/5 text-primary-foreground border border-primary/10 group-hover:border-primary/30 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                        {{ $tag->name }}
                    </span>
                @endforeach
                @if($prompt->tags->count() > 3)
                    <span class="text-xs text-muted-foreground">+{{ $prompt->tags->count() - 3 }}</span>
                @endif
            </div>
            @endif
            
            <!-- Footer Stats -->
            <div class="flex items-center justify-between pt-3 mt-auto border-t border-primary/10">
                <div class="flex items-center gap-3 text-xs text-muted-foreground">
                    <!-- Word count -->
                    <span class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ $prompt->word_count ?? 0 }} palabras
                    </span>
                    
                    <!-- Reading time -->
                    <span class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $prompt->reading_time ?? 1 }} min
                    </span>
                </div>
                
                <!-- Arrow indicator -->
                <div class="text-primary opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-0 group-hover:translate-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
            
            <!-- Hover glow effect -->
            <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-20 transition-opacity duration-500 pointer-events-none">
                <div class="absolute inset-0 bg-gradient-radial from-primary/20 to-transparent"></div>
            </div>
        </div>
    </div>
</a>

<style>
@keyframes gradient-border {
    0%, 100% {
        opacity: 0.5;
        transform: rotate(0deg);
    }
    50% {
        opacity: 1;
        transform: rotate(180deg);
    }
}

.animate-gradient-border {
    animation: gradient-border 3s linear infinite;
}

.bg-gradient-radial {
    background: radial-gradient(circle at center, var(--tw-gradient-from), var(--tw-gradient-to));
}
</style>