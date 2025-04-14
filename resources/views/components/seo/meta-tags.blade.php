{{-- SEO Meta Tags Component
    This component centralizes all SEO-related meta tags including:
    - Standard meta tags (title, description, keywords)
    - Open Graph tags for social sharing
    - Twitter Card tags
    - Canonical URL
    - Structured data (JSON-LD)
--}}

{{-- Standard Meta Tags --}}
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $canonical }}">

{{-- Open Graph Tags --}}
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:locale" content="es_LA">
<meta property="og:site_name" content="{{ config('app.name') }}">

{{-- Twitter Card Tags --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
@if($twitterCreator ?? false)
<meta name="twitter:creator" content="{{ $twitterCreator }}">
@endif
@if($twitterSite ?? false)
<meta name="twitter:site" content="{{ $twitterSite }}">
@endif

{{-- Additional Meta Tags --}}
<meta name="robots" content="{{ $robots ?? 'index, follow' }}">
<meta name="author" content="{{ $author ?? config('app.author', 'Alberto Rosas') }}">

{{-- Additional Custom Meta Tags --}}
@foreach($additionalMetaTags ?? [] as $name => $content)
<meta name="{{ $name }}" content="{{ $content }}">
@endforeach

{{-- Structured Data (JSON-LD) --}}
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

{{-- Additional Content --}}
{{ $slot ?? '' }} 