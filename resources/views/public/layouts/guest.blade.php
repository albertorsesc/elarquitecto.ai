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
    </script>
    
    @php
    // Get values from sections or use defaults
    $seoTitle = View::hasSection('title') ? trim(View::getSection('title')) : config('app.name') . ' - Democratizando I.A. para el beneficio de LATAM';
    $seoDescription = View::hasSection('description') ? trim(View::getSection('description')) : '';
    $seoKeywords = View::hasSection('keywords') ? trim(View::getSection('keywords')) : '';
    $seoContent = View::hasSection('content-for-seo') ? trim(View::getSection('content-for-seo')) : '';
    $seoImage = View::hasSection('og-image') ? trim(View::getSection('og-image')) : '';
    $seoType = View::hasSection('og-type') ? trim(View::getSection('og-type')) : 'website';
    $seoSchemaType = View::hasSection('schema-type') ? trim(View::getSection('schema-type')) : 'WebPage';
    // schemaData is handled separately via $schemaData variable
    @endphp
    
    {{-- SEO Component - Central place for all SEO meta tags --}}
    <x-seo 
        title="{{ $seoTitle }}"
        description="{{ $seoDescription }}"
        keywords="{{ $seoKeywords }}"
        content="{{ $seoContent }}"
        image="{{ $seoImage }}"
        type="{{ $seoType }}"
        schemaType="{{ $seoSchemaType }}"
        :schemaData="$schemaData ?? []"
    />

    <link rel="apple-touch-icon" href="/favicon/apple-touch-icon-iphone-60x60-precomposed.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-touch-icon-ipad-76x76-precomposed.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-touch-icon-iphone-retina-120x120-precomposed.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-touch-icon-ipad-retina-152x152-precomposed.png">
    
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
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/theme.css'])
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body id="app" class="font-sans antialiased bg-background text-foreground min-h-screen flex flex-col" style="background-color: #0a0a0a;">
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
    <!-- Background with gradient -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-background opacity-90"></div>
        <div class="absolute inset-0">
            <div class="absolute left-[15%] top-[50%] h-96 w-96 -translate-y-1/2 rounded-full bg-primary/10 blur-[100px]"></div>
            <div class="absolute right-[15%] top-[30%] h-96 w-96 rounded-full bg-secondary/10 blur-[100px]"></div>
            <div class="absolute bottom-[20%] left-[40%] h-96 w-96 rounded-full bg-accent/10 blur-[100px]"></div>
        </div>
    </div>
    
    <!-- Global scanline effect with reduced opacity -->
    {{-- <x-scanline-effect opacity="01" /> --}}

    <!-- Include the landing header -->
    @include('landing.header')
    
    <main class="container mx-auto px-4 py-8 relative z-10 flex-grow">
        @yield('content')
    </main>
    
    @include('landing.components.footer')
    
    <!-- Custom scrollbar to prevent animation issues -->
    <x-neon-scrollbar />
</body>
</html> 