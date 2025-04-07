<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles -->
    <style>
        html {
            background-color: oklch(1 0 0);
            color: oklch(0.145 0 0);
        }

        html.dark {
            background-color: oklch(0.145 0 0);
            color: oklch(0.95 0 0);
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1.25rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: oklch(0.5 0.2 250);
            color: white;
            text-decoration: none;
            border-radius: 0.25rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .button:hover {
            background-color: oklch(0.45 0.25 250);
        }

        .button-outline {
            background-color: transparent;
            border: 1px solid oklch(0.5 0.2 250);
            color: oklch(0.5 0.2 250);
        }

        html.dark .button-outline {
            border-color: oklch(0.7 0.2 250);
            color: oklch(0.7 0.2 250);
        }

        .button-outline:hover {
            background-color: oklch(0.5 0.2 250 / 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to {{ config('app.name', 'Laravel') }}</h1>
        <p>Your powerful AI architect assistant ready to help with your projects.</p>
        
        <div class="buttons">
            <a href="{{ route('login') }}" class="button">Login</a>
            <a href="{{ route('register') }}" class="button button-outline">Register</a>
        </div>
    </div>
</body>
</html> 