@props(['articles' => []])

<section class="py-12 sm:py-16">
  <div class="mx-auto max-w-6xl px-4">
    <h2 class="text-glow-light mb-8 text-center text-2xl font-bold text-accent sm:mb-12 sm:text-3xl md:text-4xl">
      Últimos Artículos
    </h2>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      @foreach($articles['data'] as $article)
        <div
          class="group relative overflow-hidden rounded-xl border border-white/10 bg-background/50 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:shadow-[0_0_30px_rgba(0,0,0,0.3)] shadow-[0_4px_20px_rgba(0,0,0,0.2)]"
        >
          <!-- Image container with effects -->
          <div class="relative aspect-[16/9] overflow-hidden">
            <!-- Cyberpunk overlay effect -->
            <x-cyberpunk-overlay />
            <x-color-mix-overlay />

            <!-- Glitch effect lines -->
            <x-glitch-effect />

            <!-- Image with hover zoom -->
            <img
              src="{{ $article['image'] }}"
              alt="{{ $article['title'] }}"
              class="h-full w-full object-cover transition-transform duration-700 will-change-transform group-hover:scale-110"
              onerror="this.src='https://via.placeholder.com/400x200/1a1a2e/ffffff?text=El+Arquitecto+A.I.';"
              loading="lazy"
            />

            <!-- Scanline effect -->
            <x-scanline-effect />

            <!-- Category badge -->
            <span class="absolute bottom-4 left-4 z-20 rounded bg-background/80 px-2 py-1 text-xs font-medium text-primary backdrop-blur-sm transition-colors duration-300 group-hover:bg-primary group-hover:text-white">
              {{ $article['category'] }}
            </span>
          </div>

          <div class="relative p-4">
            <!-- Content -->
            <h3 class="mb-2 text-lg font-semibold text-foreground transition-colors duration-300 group-hover:text-glow-multi">
              {{ $article['title'] }}
            </h3>
            <p class="text-sm text-foreground/70">
              {{ $article['excerpt'] }}
            </p>

            <!-- Hover effect overlay -->
            <x-hover-gradient-overlay />
          </div>

          <!-- Neon border effects -->
          <div class="pointer-events-none absolute inset-0 rounded-xl">
            <!-- Multi-colored sliding neon lights -->
            <x-multi-color-sliding-neon />
          </div>

          <!-- Link overlay -->
          <a href="{{ url('/blog/' . $article['id']) }}" class="absolute inset-0">
            <span class="sr-only">Leer más sobre {{ $article['title'] }}</span>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>