@extends('layouts.blog')

@section('title', $article->title)
@section('meta_description', $article->excerpt)

@section('og_type', 'article')
@section('og_title', $article->title)
@section('og_description', $article->excerpt)
@section('og_image', $article->image ? Storage::url($article->image) : asset('images/default-og-image.jpg'))

@section('twitter_title', $article->title)
@section('twitter_description', $article->excerpt)
@section('twitter_image', $article->image ? Storage::url($article->image) : asset('images/default-twitter-image.jpg'))
@section('canonical_url', route('blog.show', $article))

@section('breadcrumbs')
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('home') }}" class="text-foreground/60 hover:text-primary">Inicio</a>
        <span class="text-foreground/40">/</span>
        <a href="{{ route('blog.index') }}" class="text-foreground/60 hover:text-primary">Blog</a>
        <span class="text-foreground/40">/</span>
        <span class="text-foreground/80">{{ $article->title }}</span>
    </div>
@endsection

@section('content')
    <article class="glass-effect relative overflow-hidden rounded-xl p-6">
        <!-- Featured Image -->
        @if($article->image)
            <div class="relative mb-8 aspect-video overflow-hidden rounded-lg">
                <img
                        src="{{ Storage::url($article->image) }}"
                        alt="{{ $article->title }}"
                        class="h-full w-full object-cover"
                >
                <!-- Image overlay gradient -->
                <div class="absolute inset-0 bg-gradient-to-t from-background/80 to-transparent"></div>
            </div>
        @endif

        <!-- Corner accents -->
        <div class="absolute left-0 top-0 h-12 w-12">
            <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
            <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
        </div>
        <div class="absolute right-0 top-0 h-12 w-12">
            <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
            <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
        </div>

        <!-- Post Header -->
        <header class="mb-8">
            <h1 class="mb-4 text-3xl font-bold text-glow sm:text-4xl">{{ $article->title }}</h1>

            <!-- Post Meta -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-foreground/60">
                @if($article->category)
                    <a href="{{ route('blog.category', $article->category->slug) }}" class="flex items-center gap-1 hover:text-primary">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        {{ $article->category->name }}
                    </a>
                @endif

                <span class="flex items-center gap-1">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $article->published_at->format('d M, Y') }}
                </span>
            </div>
        </header>

        <!-- Post Content -->
        <div class="prose prose-invert max-w-none prose-headings:text-glow prose-a:text-primary prose-a:no-underline hover:prose-a:text-primary/80 prose-strong:text-primary prose-code:text-cyan-400 prose-pre:glass-effect">
            {{ (new \App\Classes\Markdown($article->content))->setImageHeight(350)->render() }}
        </div>

        <!-- Tags -->
        @if(false)
            {{-- || $article->tags->count() > 0--}}
            <div class="mt-8 flex flex-wrap gap-2">
                @foreach($article->tags as $tag)
                    <a
                        href="{{ route('blog.tag', $tag->slug) }}"
                        class="neon-border rounded-full bg-primary/5 px-3 py-1 text-sm text-primary transition-all hover:bg-primary/10"
                    >
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Social Share -->
        <div class="mt-8 flex items-center gap-4">
            <span class="text-sm text-foreground/60">Compartir:</span>
            <div class="flex gap-2">
                <!-- Twitter -->
                <a
                    href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $article)) }}&text={{ urlencode($article->title) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="rounded-full bg-primary/10 p-2 text-primary transition-all hover:bg-primary/20"
                >
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                    </svg>
                </a>

                <!-- Facebook -->
                <a
                    href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $article)) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="rounded-full bg-primary/10 p-2 text-primary transition-all hover:bg-primary/20"
                >
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                    </svg>
                </a>

                <!-- WhatsApp -->
                <a
                    href="https://wa.me/?text={{ urlencode($article->title . ' ' . route('blog.show', $article)) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="rounded-full bg-primary/10 p-2 text-primary transition-all hover:bg-primary/20"
                >
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                    </svg>
                </a>

                <!-- Copy Link -->
                <button
                    onclick="navigator.clipboard.writeText('{{ route('blog.show', $article) }}').then(() => alert('Enlace copiado!'))"
                    class="rounded-full bg-primary/10 p-2 text-primary transition-all hover:bg-primary/20"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                    </svg>
                </button>
            </div>
        </div>
    </article>
@endsection
