<header class="sticky top-0 z-50 w-full">
    <nav class="glass-effect relative border-b border-white/10 bg-background/70 px-2 py-3 backdrop-blur-xl sm:py-4">
        <!-- Animated top border -->
        <div class="absolute inset-x-0 top-0 h-[1px]">
            <div class="absolute inset-0 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-30"></div>
        </div>
        
        <div class="mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-2 px-2">
            <!-- Logo with glow -->
            <a href="/" class="group">
                <div class="relative">
                    <img src="/img/logo.webp" alt="El Arquitecto A.I. Logo" class="h-8 w-auto rounded-full z-10 relative sm:h-10" />
                    
                    <!-- Animated border gradient -->
                    <div class="absolute -inset-0.5 rounded-full opacity-90 overflow-hidden border border-transparent" style="box-shadow: 0 0 5px rgba(124, 58, 237, 0.3);">
                        <!-- Top edge - faster animation -->
                        <div class="absolute left-0 top-0 h-[2px] w-[200%] animate-neon-slide-right-color bg-gradient-to-r from-transparent via-[rgba(var(--primary-rgb),0.8)] to-transparent" style="animation-duration: 2s;"></div>
                        <!-- Right edge - faster animation -->
                        <div class="absolute right-0 top-0 h-[200%] w-[2px] animate-neon-slide-down-color bg-gradient-to-b from-transparent via-[rgba(var(--secondary-rgb),0.8)] to-transparent" style="animation-duration: 2s;"></div>
                        <!-- Bottom edge - faster animation -->
                        <div class="absolute bottom-0 left-0 h-[2px] w-[200%] animate-neon-slide-left-color bg-gradient-to-r from-transparent via-[rgba(var(--accent-rgb),0.8)] to-transparent" style="animation-duration: 2s;"></div>
                        <!-- Left edge - faster animation -->
                        <div class="absolute left-0 top-0 h-[200%] w-[2px] animate-neon-slide-up-color bg-gradient-to-b from-transparent via-[rgba(var(--cyan-400-rgb),0.8)] to-transparent" style="animation-duration: 2s;"></div>
                        
                        <!-- Additional left edge with different timing -->
                        <div class="absolute left-0 top-0 h-[200%] w-[2px] animate-neon-slide-down-color bg-gradient-to-b from-transparent via-[rgba(var(--primary-rgb),0.8)] to-transparent" style="animation-duration: 1.5s; animation-delay: 0.5s;"></div>
                    </div>
                </div>
            </a>

            <!-- Navigation Menu - Centered -->
            <div class="order-2 flex-1 flex justify-center items-center">
                <nav class="hidden sm:flex items-center space-x-1 md:space-x-2">
                    <a href="{{ route('tools.index') }}" 
                       class="group relative px-4 py-2 text-sm font-medium transition-all duration-300
                              {{ request()->routeIs('tools.*') ? 'text-primary' : 'text-foreground/80 hover:text-primary' }}">
                        <span class="relative z-10">Herramientas</span>
                        <!-- Active/Hover underline effect -->
                        <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-gradient-to-r from-primary via-cyan-400 to-secondary transform transition-transform duration-300
                                    {{ request()->routeIs('tools.*') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></div>
                        <!-- Active/Hover glow -->
                        <div class="absolute inset-0 rounded-lg transition-all duration-300
                                    {{ request()->routeIs('tools.*') ? 'bg-primary/10' : 'bg-primary/0 group-hover:bg-primary/10' }}"></div>
                    </a>
                    
                    <a href="{{ route('prompts.index') }}" 
                       class="group relative px-4 py-2 text-sm font-medium transition-all duration-300
                              {{ request()->routeIs('prompts.*') ? 'text-primary' : 'text-foreground/80 hover:text-primary' }}">
                        <span class="relative z-10">Prompts</span>
                        <!-- Active/Hover underline effect -->
                        <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-gradient-to-r from-secondary via-accent to-primary transform transition-transform duration-300
                                    {{ request()->routeIs('prompts.*') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></div>
                        <!-- Active/Hover glow -->
                        <div class="absolute inset-0 rounded-lg transition-all duration-300
                                    {{ request()->routeIs('prompts.*') ? 'bg-secondary/10' : 'bg-secondary/0 group-hover:bg-secondary/10' }}"></div>
                    </a>
                    
                    <a href="{{ route('articles.index') }}" 
                       class="group relative px-4 py-2 text-sm font-medium transition-all duration-300
                              {{ request()->routeIs('articles.*') ? 'text-primary' : 'text-foreground/80 hover:text-primary' }}">
                        <span class="relative z-10">Artículos</span>
                        <!-- Active/Hover underline effect -->
                        <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-gradient-to-r from-accent via-primary to-cyan-400 transform transition-transform duration-300
                                    {{ request()->routeIs('articles.*') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></div>
                        <!-- Active/Hover glow -->
                        <div class="absolute inset-0 rounded-lg transition-all duration-300
                                    {{ request()->routeIs('articles.*') ? 'bg-accent/10' : 'bg-accent/0 group-hover:bg-accent/10' }}"></div>
                    </a>
                </nav>
                
                <!-- Mobile Menu Button -->
                <div class="sm:hidden" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="p-2 text-foreground/80 hover:text-primary transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    
                    <!-- Mobile Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         @click.outside="open = false"
                         class="absolute top-full left-0 right-0 mt-2 mx-4 glass-effect rounded-xl border border-white/10 overflow-hidden">
                        <a href="{{ route('tools.index') }}" 
                           class="block px-4 py-3 text-sm font-medium transition-all
                                  {{ request()->routeIs('tools.*') ? 'text-primary bg-primary/10' : 'text-foreground/80 hover:text-primary hover:bg-primary/10' }}">
                            Herramientas
                        </a>
                        <a href="{{ route('prompts.index') }}" 
                           class="block px-4 py-3 text-sm font-medium transition-all
                                  {{ request()->routeIs('prompts.*') ? 'text-primary bg-secondary/10' : 'text-foreground/80 hover:text-primary hover:bg-secondary/10' }}">
                            Prompts
                        </a>
                        <a href="{{ route('articles.index') }}" 
                           class="block px-4 py-3 text-sm font-medium transition-all
                                  {{ request()->routeIs('articles.*') ? 'text-primary bg-accent/10' : 'text-foreground/80 hover:text-primary hover:bg-accent/10' }}">
                            Artículos
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Auth Links -->
            <div class="order-3 flex items-center gap-1 sm:gap-3">
                @if(auth()->check())
                <a href="{{ route('dashboard') }}"
                    class="neon-border rounded bg-primary/10 px-2 py-1 text-xs font-semibold text-primary transition-all hover:bg-primary/20 sm:px-3 sm:py-1.5 sm:text-sm"
                >
                    Dashboard
                </a>
            @else
                {{-- <a href="{{ route('login') }}" class="px-1 text-xs text-foreground/80 transition-colors hover:text-primary sm:px-2 sm:text-sm">
                    Entrar
                </a>
                <a href="{{ route('register') }}" class="neon-border rounded bg-primary px-2 py-1 text-xs font-semibold text-white transition-all hover:bg-primary/80 sm:px-3 sm:py-1.5 sm:text-sm">
                    Registrarme
                </a> --}}
            @endif
        </div>
    </div>
</nav>
</div>