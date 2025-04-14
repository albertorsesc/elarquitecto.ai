<header class="sticky top-0 z-50 w-full">
    <nav class="glass-effect relative border-b border-white/10 bg-background/70 px-2 py-3 backdrop-blur-xl sm:py-4">
        <!-- Animated top border -->
        <div class="absolute inset-x-0 top-0 h-[1px]">
            <div class="absolute inset-0 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-30"></div>
        </div>
        
        <div class="mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-2 px-2">
            <!-- Logo with glow -->
            <a href="/" class="group flex items-center">
                <img src="/img/logo.png" alt="El Arquitecto A.I. Logo" class="h-8 w-auto transition-all duration-300 group-hover:shadow-[0_0_15px_rgba(124,58,237,0.3)] sm:h-10" />
            </a>

            <!-- Search Bar -->
            <div class="order-3 mt-2 w-full sm:order-2 sm:mt-0 sm:w-auto sm:flex-1 sm:px-4 md:px-6">
                <div class="group relative">
                <input
                    type="text"
                    placeholder="Buscar contenido..."
                    class="peer relative z-10 w-full rounded-xl border border-white/10 bg-background/50 py-1.5 pl-8 pr-4 text-sm text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                />
                <div class="absolute inset-y-0 left-0 z-10 flex items-center pl-2.5 text-foreground/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <!-- Animated border effect -->
                <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>

                <!-- Sliding neon lights -->
                <x-sliding-neon topColor="accent" rightColor="primary" bottomColor="secondary" leftColor="cyan-400" />

                <!-- Corner accents -->
                <x-corner-accent leftColor="primary" rightColor="cyan-400" transition="true" />
                </div>
            </div>
            
            <!-- Navigation Links -->
            <div class="order-2 flex items-center gap-1 sm:order-3 sm:gap-3">
                @if(auth()->check())
                <a href="{{ route('dashboard') }}"
                    class="neon-border rounded bg-primary/10 px-2 py-1 text-xs font-semibold text-primary transition-all hover:bg-primary/20 sm:px-3 sm:py-1.5 sm:text-sm"
                >
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="px-1 text-xs text-foreground/80 transition-colors hover:text-primary sm:px-2 sm:text-sm">
                    Entrar
                </a>
                <a href="{{ route('register') }}" class="neon-border rounded bg-primary px-2 py-1 text-xs font-semibold text-white transition-all hover:bg-primary/80 sm:px-3 sm:py-1.5 sm:text-sm">
                    Registrarme
                </a>
            @endif
        </div>
    </div>
</nav>
</div>