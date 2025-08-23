<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" style="background-color: #0a0a0a;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Immediate dark mode to prevent flash -->
    <script>
        document.documentElement.style.backgroundColor = '#0a0a0a';
        document.documentElement.classList.add('dark');
        document.body && (document.body.style.backgroundColor = '#0a0a0a');
    </script>
    
    @php
    // Define schema data for organization
    $schemaData = [
        'sameAs' => [
            'https://instagram.com/elarquitectoai',
            'https://facebook.com/elarquitectoai',
            'https://youtube.com/@elarquitectoai',
            'https://tiktok.com/@elarquitectoai',
        ]
    ];
    @endphp

    {{-- SEO Component - Central place for all SEO meta tags --}}
    <x-seo 
        title="{{ $seo['title'] ?? config('app.name') . ' - Democratizando I.A. para el beneficio de LATAM' }}"
        description="{{ $seo['description'] ?? 'Democratizando I.A. para el beneficio de Latinoamérica' }}"
        keywords="{{ $seo['keywords'] ?? 'IA, Inteligencia Artificial, Latinoamérica, AI' }}"
        image="{{ $seo['image'] ?? url('/img/logo.webp') }}"
        type="{{ $seo['type'] ?? 'website' }}"
        schemaType="Organization"
        :schemaData="$schemaData"
    />

    @production
      <!-- Google tag (gtag.js) -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=G-7NGTTSRYL1"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-7NGTTSRYL1');
      </script>
    @endproduction
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Critical CSS to prevent white flash -->
    <style>
        :root {
            color-scheme: dark;
        }
        html {
            background-color: #0a0a0a !important;
        }
        body {
            background-color: #0a0a0a !important;
            color: #e5e5e5;
        }
        /* Prevent white flash during navigation */
        .page-loading * {
            transition: none !important;
        }
        #loading-overlay {
            background: #0a0a0a !important;
        }
    </style>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/theme.css'])
</head>
<body class="min-h-screen bg-background text-foreground antialiased overflow-x-hidden" style="background-color: #0a0a0a;">
    <!-- Page transition overlay -->
    <div id="page-transition" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: #0a0a0a; z-index: 99999; opacity: 0; pointer-events: none; transition: opacity 0.2s;"></div>
    <script>
        // Smooth page transitions
        document.addEventListener('DOMContentLoaded', function() {
            const transition = document.getElementById('page-transition');
            const links = document.querySelectorAll('a[href^="/"]');
            
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (!link.hasAttribute('download') && !link.hasAttribute('target') && !link.getAttribute('href').startsWith('#')) {
                        transition.style.pointerEvents = 'auto';
                        transition.style.opacity = '1';
                    }
                });
            });
        });
        // Hide transition on page load
        window.addEventListener('load', function() {
            const transition = document.getElementById('page-transition');
            if (transition) {
                transition.style.opacity = '0';
                setTimeout(() => { transition.style.pointerEvents = 'none'; }, 200);
            }
        });
    </script>
    @include('landing.header')

    <!-- Flash Messages -->
    @if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-20 right-4 z-50 max-w-md">
        <div class="glass-effect rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 shadow-[0_0_15px_rgba(34,197,94,0.2)] backdrop-blur-xl">
            <div class="flex items-center">
                <div class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-green-500/20 text-green-500">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-green-500">{{ session('success') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button @click="show = false" class="inline-flex h-6 w-6 items-center justify-center rounded-full text-green-500 hover:bg-green-500/20">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
            <!-- Animated progress bar -->
            <div class="mt-2 h-0.5 w-full rounded bg-green-500/20">
                <div class="h-full w-full rounded bg-green-500" x-init="setTimeout(() => $el.style.width = '0%', 100)" style="width: 100%; transition: width 5s linear;"></div>
            </div>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-20 right-4 z-50 max-w-md">
        <div class="glass-effect rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 shadow-[0_0_15px_rgba(239,68,68,0.2)] backdrop-blur-xl">
            <div class="flex items-center">
                <div class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-red-500/20 text-red-500">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-red-500">{{ session('error') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button @click="show = false" class="inline-flex h-6 w-6 items-center justify-center rounded-full text-red-500 hover:bg-red-500/20">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
            <!-- Animated progress bar -->
            <div class="mt-2 h-0.5 w-full rounded bg-red-500/20">
                <div class="h-full w-full rounded bg-red-500" x-init="setTimeout(() => $el.style.width = '0%', 100)" style="width: 100%; transition: width 5s linear;"></div>
            </div>
        </div>
    </div>
    @endif

    <div class="w-full pb-[70px]">
        <!-- Main Content -->
        <div class="w-full mx-auto">
          <main class="relative w-full">
            <!-- Hero Section -->
            @include('landing.hero')
  
            <!-- Timeline Section -->
            <div class="relative">
              @include('landing.timeline')
  
              <!-- Section corner accents -->
              <x-corner-accent leftColor="primary" rightColor="cyan-400" />
            </div>
  
            <!-- Section Cards -->
            @include('landing.sections')
  
            <!-- Retro Games Section -->
            <div class="relative py-8 hidden">
              <h2 class="mb-8 text-center text-3xl font-bold text-foreground">
                Juegos Retro
                <span class="mt-2 h-1 w-24 bg-gradient-to-r from-primary via-cyan-400 to-secondary mx-auto"></span>
              </h2>
  
              <!-- Section corner accents -->
              <x-corner-accent leftColor="primary" rightColor="cyan-400" />
  
              <!-- Grid container for games -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 max-w-full sm:max-w-4xl mx-auto px-2 sm:px-4 hidden">
                <!-- Ms Pacman -->
                <RetroGame
                  title="Ms. Pac-Man"
                  color="primary"
                  src="https://www.retrogames.cc/embed/9254-ms-pacman-champion-edition-super-zola-pac-gal.html"
                />
  
                <!-- Prince of Persia -->
                <RetroGame
                  title="Prince of Persia"
                  color="secondary"
                  src="https://www.retrogames.cc/embed/21597-prince-of-persia-usa.html"
                />
  
                <!-- Sonic the Hedgehog -->
                <RetroGame
                  title="Sonic the Hedgehog"
                  color="accent"
                  src="https://www.retrogames.cc/embed/30899-sonic-the-hedgehog-usa-europe.html"
                />
  
                <!-- Mario -->
                <RetroGame
                  title="Mario Bros"
                  color="cyan"
                  src="https://www.retrogames.cc/embed/41973-yet-another-smw-hack.html"
                />
              </div>
            </div>
  
            <!-- Color Palette -->
            <div class="relative">
              <ColorPalette />
              <!-- Section corner accents -->
              <x-corner-accent leftColor="secondary" rightColor="accent" />
            </div>
          </main>
        </div>
      </div>
      
    <!-- Footer -->
    @include('landing.components.footer')

    <!-- Cookie Consent Banner -->
    @include('components.cookie-consent')

    <!-- Initialize Cookie Consent -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize cookie consent logic here if needed
            
            // Function to get cookie consent status
            function getCookieConsent() {
                return localStorage.getItem('cookie-consent-choice');
            }
            
            // If cookies are rejected, disable Google Analytics
            if (getCookieConsent() === 'rejected') {
                window['ga-disable-G-7NGTTSRYL1'] = true;
            }
        });
    </script>
</body>
</html>