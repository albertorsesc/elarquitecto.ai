<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $seo['title'] ?? 'El Arquitecto A.I.' }}</title>
    <meta name="description" content="{{ $seo['description'] ?? 'Democratizando I.A. para el beneficio de Latinoamérica' }}">
    <meta name="keywords" content="{{ $seo['keywords'] ?? 'IA, Inteligencia Artificial, Latinoamérica, AI' }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/theme.css'])
</head>
<body class="min-h-screen bg-background text-foreground antialiased overflow-x-hidden">
    @include('landing.header')

    <div class="w-full">
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
  
            <!-- Blog Carousel -->
            <div class="relative">
              <x-blog.blog-carousel :articles="$articles ?? ['data' => []]" />
  
              <!-- Section corner accents -->
              <x-corner-accent leftColor="secondary" rightColor="accent" />
            </div>
  
            <!-- Section Cards -->
            <div class="relative">
              <x-section-card :sections="$sections ?? []" />
              <!-- Section corner accents -->
              <x-corner-accent leftColor="primary" rightColor="cyan-400" />
            </div>
  
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
</body>
</html>