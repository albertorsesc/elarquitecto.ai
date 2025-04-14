@extends('public.layouts.guest')

@section('title', $prompt->title)

@section('content')
    <div class="flex flex-col gap-6 mb-24">
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
            
            <!-- Header with Meta -->
            <div class="mb-6 flex flex-col gap-2">
                <h1 class="text-3xl font-bold text-glow animate-text-glow">{{ $prompt->title }}</h1>
                
                <div class="flex items-center justify-between mt-2">
                    <!-- Left side meta -->
                    <div class="flex items-center gap-3 text-sm text-muted-foreground">
                        <span>{{ $prompt->word_count ?? count(explode(' ', $prompt->content)) }} words</span>
                        
                        @if($prompt->category && count($prompt->category) > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/30">
                                {{ $prompt->category[0]->name }}
                            </span>
                        @endif
                    </div>
                    
                    <!-- Right side date -->
                    <span class="text-sm text-muted-foreground">
                        {{ date('M d, Y', strtotime($prompt->published_at ?? $prompt->created_at)) }}
                    </span>
                </div>
                
                <!-- Tags -->
                @if($prompt->tags && count($prompt->tags) > 0)
                <div class="flex flex-wrap gap-1 mt-2">
                    @foreach($prompt->tags as $tag)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-sidebar-accent text-sidebar-accent-foreground">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
                @endif
            </div>
            
            <!-- Content -->
            <div class="prose prose-invert max-w-none mb-8 border-t border-border/30 pt-6">
                <p>{{ $prompt->content }}</p>
            </div>
            
            <!-- Back Button -->
            <div class="mt-8 pt-4 border-t border-border/30">
                <x-cyber-button
                    href="{{ route('prompts.index') }}"
                    variant="link"
                    size="sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Prompts
                </x-cyber-button>
            </div>
        </div>
        
        <!-- Related Prompts -->
        @if ($relatedPrompts->count() > 0)
            <div class="mt-8">
                <h3 class="text-xl font-semibold mb-6 text-foreground">Related Prompts</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($relatedPrompts as $relatedPrompt)
                        <div class="group relative h-full overflow-hidden rounded-xl border border-border/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_15px_rgba(var(--primary-rgb),0.5)]">
                            <!-- Glass Effect -->
                            <x-glass-effect />
                            
                            <!-- Animation wrapper to isolate animations -->
                            <div class="neon-border-wrapper">
                                <!-- Basic Neon Border -->
                                <x-neon-border />
                                
                                <!-- Multi-colored sliding neon lights -->
                                <x-multi-color-sliding-neon 
                                    topColor="#FF1CF7" 
                                    rightColor="#00FFE1" 
                                    bottomColor="#01FF88" 
                                    leftColor="#5B6EF7" 
                                    opacity="20" 
                                    :hoverEffect="true" 
                                />
                                
                                <!-- Breathing Glow -->
                                <x-breathing-glow />
                            </div>
                            
                            <div class="p-4 relative z-10">
                                <h4 class="font-semibold mb-2 group-hover:text-primary transition-colors">
                                    {{ $relatedPrompt->title }}
                                </h4>
                                <p class="text-sm text-muted-foreground line-clamp-2 mb-4">
                                    {{ $relatedPrompt->excerpt }}
                                </p>
                                <div class="mt-2">
                                    <x-cyber-button
                                        href="{{ route('prompts.show', $relatedPrompt) }}"
                                        variant="outline"
                                        size="sm"
                                        :fullWidth="true">
                                        View Prompt
                                    </x-cyber-button>
                                </div>
                            </div>
                        </div>
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
</style>
@endpush 