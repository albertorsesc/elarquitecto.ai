<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Blog') | El Arquitecto A.I.</title>

    <!-- Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'El Arquitecto A.I. - Conocimiento de IA en Español')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'El Arquitecto A.I. - Blog')">
    <meta property="og:description" content="@yield('og_description', 'El Arquitecto A.I. - Conocimiento de IA en Español')">
    <meta property="og:image" content="@yield('og_image', asset('images/default-og-image.jpg'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('twitter_title', 'El Arquitecto A.I. - Blog')">
    <meta property="twitter:description" content="@yield('twitter_description', 'El Arquitecto A.I. - Conocimiento de IA en Español')">
    <meta property="twitter:image" content="@yield('twitter_image', asset('images/default-twitter-image.jpg'))">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    <style>
        @keyframes glow {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.8; }
        }

        .animate-glow {
            animation: glow 3s infinite;
        }

        .glass-effect {
            background-color: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .neon-border {
            position: relative;
            border: 1px solid rgba(124, 58, 237, 0.3);
            box-shadow: 0 0 5px rgba(124, 58, 237, 0.3);
        }

        .text-glow {
            text-shadow: 0 0 8px rgba(124, 58, 237, 0.5);
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen bg-background text-foreground antialiased">
    <div class="relative min-h-screen overflow-x-hidden">
        <!-- Background Effects -->
        <div class="pointer-events-none fixed inset-0 hidden sm:block">
            <!-- Horizontal lines -->
            <div class="absolute left-0 top-1/3 h-[1px] w-1/4 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-20"></div>
            <div class="absolute right-0 top-2/3 h-[1px] w-1/4 animate-neon-slide-left bg-gradient-to-r from-transparent via-primary to-transparent opacity-20"></div>
            <!-- Vertical lines -->
            <div class="absolute left-1/3 top-0 h-1/4 w-[1px] animate-neon-slide-down bg-gradient-to-b from-transparent via-secondary to-transparent opacity-20"></div>
            <div class="absolute right-2/3 top-0 h-1/4 w-[1px] animate-neon-slide-down-delayed bg-gradient-to-b from-transparent via-accent to-transparent opacity-20"></div>
        </div>

        <!-- Navigation -->
        <header class="fixed top-0 z-50 w-full">
            <nav class="glass-effect relative border-b border-white/10 bg-background/70 px-2 py-3 backdrop-blur-xl sm:py-4">
                <!-- Animated top border -->
                <div class="absolute inset-x-0 top-0 h-[1px]">
                    <div class="absolute inset-0 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-30"></div>
                </div>

                <div class="mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-2 px-2">
                    <!-- Logo with glow -->
                    <a href="{{ route('home') }}" class="group flex items-center">
                        <img src="{{ asset('logo.png') }}" alt="El Arquitecto A.I. Logo" class="h-8 w-auto transition-all duration-300 group-hover:shadow-[0_0_15px_rgba(124,58,237,0.3)] sm:h-10" />
                    </a>

                    <!-- Navigation Links -->
                    <div class="order-2 flex items-center gap-1 sm:order-3 sm:gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="neon-border rounded bg-primary/10 px-2 py-1 text-xs font-semibold text-primary transition-all hover:bg-primary/20 sm:px-3 sm:py-1.5 sm:text-sm">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-1 text-xs text-foreground/80 transition-colors hover:text-primary sm:px-2 sm:text-sm">
                                Iniciar
                            </a>
                            <a href="{{ route('register') }}" class="neon-border rounded bg-primary px-2 py-1 text-xs font-semibold text-white transition-all hover:bg-primary/80 sm:px-3 sm:py-1.5 sm:text-sm">
                                Registrarse
                            </a>
                        @endauth
                    </div>

                    <!-- Search Bar -->
                    <div class="order-3 mt-2 w-full sm:order-2 sm:mt-0 sm:w-auto sm:flex-1 sm:px-4 md:px-6">
                        <form action="{{ route('blog.search') }}" method="GET" class="group relative">
                            <input
                                type="text"
                                name="q"
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

                            <!-- Sliding neon lights (visible on focus) -->
                            <div class="pointer-events-none absolute -inset-1 opacity-0 group-focus-within:opacity-100">
                                <!-- Top edge -->
                                <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right-slow bg-gradient-to-r from-transparent via-accent to-transparent"></div>
                                <!-- Right edge -->
                                <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down-slow bg-gradient-to-b from-transparent via-primary to-transparent"></div>
                                <!-- Bottom edge -->
                                <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left-slow bg-gradient-to-r from-transparent via-secondary to-transparent"></div>
                                <!-- Left edge -->
                                <div class="absolute left-0 top-0 h-full w-[2px] animate-neon-slide-up-slow bg-gradient-to-b from-transparent via-cyan-400 to-transparent"></div>
                            </div>

                            <!-- Corner accents -->
                            <div class="absolute left-0 top-0 h-8 w-8 opacity-0 transition-opacity duration-300 group-focus-within:opacity-100">
                                <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
                                <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
                            </div>
                            <div class="absolute right-0 top-0 h-8 w-8 opacity-0 transition-opacity duration-300 group-focus-within:opacity-100">
                                <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
                                <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="relative pt-20">
            <!-- Breadcrumbs -->
            @hasSection('breadcrumbs')
                <div class="mx-auto max-w-6xl px-4 py-4">
                    <div class="glass-effect rounded-lg p-2">
                        @yield('breadcrumbs')
                    </div>
                </div>
            @endif

            <!-- Content -->
            <div class="mx-auto max-w-6xl px-4 py-4">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative border-t border-white/10 bg-background/90 py-8">
            <!-- Animated bottom border -->
            <div class="absolute inset-x-0 bottom-0 h-[1px]">
                <div class="absolute inset-0 animate-neon-slide-left bg-gradient-to-r from-transparent via-primary to-transparent opacity-30"></div>
            </div>
            <div class="mx-auto max-w-6xl px-4 text-center text-foreground/60">
                <p>&copy; {{ date('Y') }} El Arquitecto A.I. Todos los derechos reservados.</p>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>