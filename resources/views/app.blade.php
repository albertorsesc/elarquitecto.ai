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

        <title inertia>{{ config('app.name', 'El Arquitecto A.I.') }}</title>

        <!-- Fallback SEO meta tags - these will only be visible if Inertia Head hasn't loaded yet -->
        <meta name="description" content="Democratizando I.A. para el beneficio de Latinoamérica">

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
