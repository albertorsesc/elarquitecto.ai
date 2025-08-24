@extends('public.layouts.guest')

@section('title', $prompt->title)
@section('description', $prompt->excerpt)
@section('keywords')
{{ $prompt->tags->pluck('name')->join(', ') }}, prompts, inteligencia artificial, AI, IA
@endsection
@section('content-for-seo', $prompt->content)
@section('og-type', 'article')
@section('schema-type', 'HowTo')
@section('og-image', $prompt->image ? $prompt->image : url('/img/logo.webp'))

@php
// Define comprehensive schema data for SEO
$schemaData = [
    '@context' => 'https://schema.org',
    '@type' => 'HowTo',
    'name' => $prompt->title,
    'description' => $prompt->excerpt ?? substr(strip_tags($prompt->content), 0, 160),
    'datePublished' => ($prompt->published_at ?? $prompt->created_at)->toIso8601String(),
    'dateModified' => $prompt->updated_at->toIso8601String(),
    'author' => [
        '@type' => 'Person',
        'name' => 'Alberto Rosas',
        'url' => url('/')
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => config('app.name'),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => url('/img/logo.webp')
        ]
    ],
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => url()->current()
    ],
    'step' => [
        [
            '@type' => 'HowToStep',
            'name' => 'Copiar el prompt',
            'text' => 'Haz clic en el botón "Copiar" para copiar el prompt al portapapeles'
        ],
        [
            '@type' => 'HowToStep',
            'name' => 'Usar en tu IA favorita',
            'text' => 'Pega el prompt en ChatGPT, Claude, Gemini o cualquier otra IA'
        ]
    ],
    'totalTime' => 'PT1M',
    'keywords' => implode(', ', array_merge(
        $prompt->tags->pluck('name')->toArray(),
        ['prompts', 'inteligencia artificial', 'AI', 'IA', 'ChatGPT', 'Claude', 'Gemini']
    ))
];
@endphp

@push('structured-data')
<script type="application/ld+json">
{!! json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
    <div class="flex flex-col gap-6 mb-24 max-w-5xl mx-auto">
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
                        {{ ($prompt->published_at ?? $prompt->created_at)->isoFormat('D [de] MMM, YYYY') }}
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
            
            <!-- Alpine.js Prompt Copy Component -->
            <div x-data="{
                promptContent: {{ json_encode($prompt->content) }},
                showToast: false,
                
                async copyPrompt() {
                    try {
                        // Try using the Clipboard API first
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            await navigator.clipboard.writeText(this.promptContent);
                        } else {
                            // Fallback method
                            const textarea = document.createElement('textarea');
                            textarea.value = this.promptContent;
                            textarea.style.position = 'fixed';
                            textarea.style.opacity = '0';
                            document.body.appendChild(textarea);
                            textarea.select();
                            document.execCommand('copy');
                            document.body.removeChild(textarea);
                        }
                        
                        // Show toast
                        this.showToast = true;
                        
                        // Hide toast after 2 seconds
                        setTimeout(() => {
                            this.showToast = false;
                        }, 2000);
                    } catch (err) {
                        console.error('Failed to copy: ', err);
                    }
                }
            }">
                <!-- Copy button at the beginning -->
                <div class="flex justify-end mb-4">
                    <button 
                        type="button" 
                        class="inline-flex items-center justify-center rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-8 px-3 text-xs border border-border/50 hover:bg-primary/10 hover:text-primary" 
                        title="Copiar Prompt"
                        @click="copyPrompt()">
                        <i class="fas fa-copy text-glow-multi"></i>
                    </button>
                </div>
                
                <!-- Content -->
                <div class="prose prose-invert max-w-none mb-8 border-t border-border/30 pt-6">
                    {!! md_to_html($prompt->content) !!}
                </div>
                
                <!-- Copy button at the end -->
                <div class="flex justify-center mb-6">
                    <button 
                        type="button" 
                        class="inline-flex items-center justify-center rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-10 px-4 py-2 border border-border/50 hover:bg-primary/10 hover:text-primary"
                        @click="copyPrompt()">
                        <i class="fas fa-copy mr-2 text-glow-multi"></i>
                        Copiar Prompt
                    </button>
                </div>
                
                <!-- Toast notification - Fixed at top center -->
                <template x-teleport="body">
                    <div 
                        x-show="showToast" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4"
                        class="fixed top-6 left-1/2 transform -translate-x-1/2 backdrop-blur-sm bg-black/90 border border-primary px-6 py-3 rounded-md shadow-[0_0_20px_rgba(var(--primary-rgb),0.7)] z-50">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-primary mr-2 text-xl"></i>
                            <span class="text-white font-semibold">Prompt Copiado!</span>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Social Sharing -->
            <div class="mb-6 pt-4 border-t border-border/30">
                <div class="text-center mb-2 text-sm text-muted-foreground">Compartir este prompt</div>
                <div class="flex flex-wrap justify-center gap-3">
                    <!-- Copy Link Button -->
                    <button
                       onclick="copyPromptLink()"
                       aria-label="Copiar enlace"
                       class="social-share-btn inline-flex items-center justify-center rounded-md font-medium h-10 px-3 sm:px-4 py-2 border border-border/50 hover:bg-primary/10 hover:text-primary transition-colors copy-link-btn">
                        <i class="fas fa-share-alt text-glow-multi sm:mr-2"></i>
                        <span class="hidden sm:inline">Copiar enlace</span>
                    </button>
                
                    <!-- Twitter/X Share -->
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('prompts.show', $prompt)) }}&text={{ urlencode($prompt->title) }}" 
                       target="_blank" aria-label="Compartir en Twitter"
                       class="social-share-btn inline-flex items-center justify-center rounded-md font-medium h-10 px-3 sm:px-4 py-2 border border-border/50 hover:bg-primary/10 hover:text-primary transition-colors">
                        <i class="fa-brands fa-x-twitter text-glow-multi sm:mr-2"></i>
                        <span class="hidden sm:inline">X</span>
                    </a>
                    
                    <!-- Facebook Share -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('prompts.show', $prompt)) }}"
                       target="_blank" aria-label="Compartir en Facebook"
                       class="social-share-btn inline-flex items-center justify-center rounded-md font-medium h-10 px-3 sm:px-4 py-2 border border-border/50 hover:bg-primary/10 hover:text-primary transition-colors">
                        <i class="fab fa-facebook text-glow-multi sm:mr-2"></i>
                        <span class="hidden sm:inline">Facebook</span>
                    </a>
                    
                    <!-- LinkedIn Share -->
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('prompts.show', $prompt)) }}&title={{ urlencode($prompt->title) }}"
                       target="_blank" aria-label="Compartir en LinkedIn"
                       class="social-share-btn inline-flex items-center justify-center rounded-md font-medium h-10 px-3 sm:px-4 py-2 border border-border/50 hover:bg-primary/10 hover:text-primary transition-colors">
                        <i class="fab fa-linkedin text-glow-multi sm:mr-2"></i>
                        <span class="hidden sm:inline">LinkedIn</span>
                    </a>
                </div>
            </div>
            
            <!-- Add JavaScript for copy link functionality -->
            <script>
            function copyPromptLink() {
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
                <h3 class="text-xl font-semibold mb-6 text-foreground">
                    También te podría interesar
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($relatedPrompts as $relatedPrompt)
                        <x-prompt-card :prompt="$relatedPrompt" />
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