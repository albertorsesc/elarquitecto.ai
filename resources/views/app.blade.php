<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        @if(isset($page['props']['seo']))
            {{-- Use the SEO data from Inertia props --}}
            @php
                $seo = $page['props']['seo'];
                $title = $seo['title'] ?? config('app.name');
                $description = $seo['description'] ?? 'Democratizando I.A. para el beneficio de Latinoamérica';
                $keywords = $seo['keywords'] ?? 'inteligencia artificial, IA, machine learning, español, Latinoamérica';
                $ogType = $seo['ogType'] ?? 'website';
                $ogImage = $seo['ogImage'] ?? asset('logo.png');
                $ogUrl = $seo['canonicalUrl'] ?? url()->current();
                $twitterCard = $seo['twitterCard'] ?? 'summary_large_image';
            @endphp

            <title>{{ $title }}</title>

            <!-- SEO Meta Tags -->
            <meta name="description" content="{{ $description }}">
            <meta name="keywords" content="{{ $keywords }}">
            <link rel="canonical" href="{{ $ogUrl }}">

            <!-- Open Graph / Facebook -->
            <meta property="og:type" content="{{ $ogType }}">
            <meta property="og:url" content="{{ $ogUrl }}">
            <meta property="og:title" content="{{ $title }}">
            <meta property="og:description" content="{{ $description }}">
            <meta property="og:image" content="{{ $ogImage }}">
            <meta property="og:site_name" content="El Arquitecto A.I.">
            <meta property="og:locale" content="es_ES">

            <!-- Twitter -->
            <meta name="twitter:card" content="{{ $twitterCard }}">
            <meta name="twitter:url" content="{{ $ogUrl }}">
            <meta name="twitter:title" content="{{ $title }}">
            <meta name="twitter:description" content="{{ $description }}">
            <meta name="twitter:image" content="{{ $ogImage }}">
            <meta name="twitter:site" content="@elarquitectoai">
        @else
            {{-- Fallback SEO data --}}
            <title inertia>{{ config('app.name', 'El Arquitecto A.I.') }}</title>
            <meta name="description" content="Democratizando I.A. para el beneficio de Latinoamérica">
            <meta name="keywords" content="inteligencia artificial, IA, machine learning, español, Latinoamérica">

            <!-- Open Graph / Facebook -->
            <meta property="og:type" content="website">
            <meta property="og:url" content="{{ url()->current() }}">
            <meta property="og:title" content="{{ config('app.name', 'El Arquitecto A.I.') }}">
            <meta property="og:description" content="Democratizando I.A. para el beneficio de Latinoamérica">
            <meta property="og:image" content="{{ asset('logo.png') }}">
            <meta property="og:site_name" content="El Arquitecto A.I.">
            <meta property="og:locale" content="es_ES">

            <!-- Twitter -->
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:url" content="{{ url()->current() }}">
            <meta name="twitter:title" content="{{ config('app.name', 'El Arquitecto A.I.') }}">
            <meta name="twitter:description" content="Democratizando I.A. para el beneficio de Latinoamérica">
            <meta name="twitter:image" content="{{ asset('logo.png') }}">
            <meta name="twitter:site" content="@elarquitectoai">
        @endif

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- JSON-LD Structured Data -->
        @if(isset($page['props']['jsonLd']))
            @if(is_array($page['props']['jsonLd']))
                @if(isset($page['props']['jsonLd']['@context']))
                    <!-- Single schema -->
                    <script type="application/ld+json">
                        {!! json_encode($page['props']['jsonLd']) !!}
                    </script>
                @else
                    <!-- Multiple schemas -->
                    @foreach($page['props']['jsonLd'] as $type => $schema)
                        @if(is_array($schema))
                            <script type="application/ld+json">
                                {!! json_encode($schema) !!}
                            </script>
                        @endif
                    @endforeach
                @endif
            @endif
        @endif

        @routes
        @vite(['resources/js/app.ts', 'resources/css/theme.css', 'resources/css/app.css'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        <div class="w-full p-0">
            @inertia
        </div>
    </body>
</html>
