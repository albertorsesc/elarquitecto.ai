<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
    // Get values from sections or use defaults
    $seoTitle = View::hasSection('title') ? trim(View::getSection('title')) : 'El Arquitecto AI';
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
<body id="app" class="font-sans antialiased bg-background text-foreground min-h-screen flex flex-col">
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