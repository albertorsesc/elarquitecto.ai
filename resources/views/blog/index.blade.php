@extends('layouts.blog')

@section('title', 'Blog')
@section('meta_description', 'Explora nuestro blog sobre Inteligencia Artificial en español. Artículos, tutoriales y recursos para mantenerte actualizado.')

@section('breadcrumbs')
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('home') }}" class="text-foreground/60 hover:text-primary">Inicio</a>
        <span class="text-foreground/40">/</span>
        <span class="text-foreground/80">Blog</span>
    </div>
@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-glow mb-4 text-3xl font-bold sm:text-4xl">Blog</h1>
        <p class="text-foreground/70">Explora nuestros artículos sobre Inteligencia Artificial, tutoriales y recursos.</p>
    </div>

    @if($articles->count() > 0)
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($articles as $article)
                <article class="glass-effect group relative overflow-hidden rounded-xl transition-all duration-300 hover:shadow-[0_0_15px_rgba(124,58,237,0.2)]">
                    <!-- Corner accents -->
                    <div class="absolute left-0 top-0 h-8 w-8 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                        <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
                        <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
                    </div>
                    <div class="absolute right-0 top-0 h-8 w-8 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                        <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
                        <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
                    </div>

                    <!-- Featured Image -->
                    @if($article->image)
                        <div class="relative aspect-video overflow-hidden">
                            <img
                                src="{{ Storage::url($article->image) }}"
                                alt="{{ $article->title }}"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                            >
                            <!-- Image overlay gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-background/80 to-transparent"></div>

                            <!-- Category badge -->
                            @if($article->category)
                                <a
                                    href="{{ route('blog.category', $article->category->slug) }}"
                                    class="absolute left-3 top-3 rounded-full bg-primary/80 px-2 py-1 text-xs font-medium text-white backdrop-blur-sm transition-all hover:bg-primary"
                                >
                                    {{ $article->category->name }}
                                </a>
                            @endif
                        </div>
                    @endif

                    <div class="p-5">
                        <!-- Post Meta -->
                        <div class="mb-2 flex items-center gap-2 text-xs text-foreground/60">
                            <span class="flex items-center gap-1">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $article->published_at->format('d M, Y') }}
                            </span>
                        </div>

                        <!-- Post Title -->
                        <h2 class="mb-2 text-xl font-bold transition-colors group-hover:text-primary">
                            <a href="{{ route('blog.show', $article) }}" class="block">
                                {{ $article->title }}
                            </a>
                        </h2>

                        <!-- Post Excerpt -->
                        <p class="mb-4 text-sm text-foreground/70">
                            {{ Str::limit($article->excerpt ?? strip_tags($article->content), 120) }}
                        </p>

                        <!-- Read More Link -->
                        <a
                            href="{{ route('blog.show', $article) }}"
                            class="inline-flex items-center gap-1 text-sm font-medium text-primary transition-colors hover:text-primary/80"
                        >
                            Leer más
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    @else
        <div class="glass-effect rounded-xl p-8 text-center">
            <p class="text-foreground/70">No hay artículos publicados aún.</p>
        </div>
    @endif
@endsection
