<section class="relative py-12 sm:py-20">
    <!-- Background effect -->
    <div class="absolute inset-0 bg-gradient-to-t from-background/50 to-transparent">
      <div class="cyberpunk-grid-bg absolute inset-0 opacity-10"></div>
    </div>

    <div class="mx-auto max-w-6xl">
      <h2 class="text-glow-light mb-8 text-center text-2xl font-bold text-secondary sm:mb-12 sm:text-3xl md:text-4xl">
        Novedades
      </h2>

      <!-- Prompt Timeline Section -->
      @if(isset($prompts) && count($prompts) > 0)
        <div class="mb-12">
          <h3 class="text-center text-xl font-semibold mb-8 text-primary animate-text-glow">
            Prompts Recientes
          </h3>
          @include('landing.components.timeline-container', [
            'items' => $prompts,
            'withScrollbar' => false
          ])
        </div>
      @endif

      <!-- Article Timeline Section (Uncommented) -->
      @if(isset($articles) && count($articles) > 0)
        <div class="mb-12">
          <h3 class="text-center text-xl font-semibold mb-8 text-secondary animate-text-glow">
            Artículos Recientes
          </h3>
          @include('landing.components.timeline-container', [
            'items' => $articles,
            'withScrollbar' => false
          ])
        </div>
      @endif
    </div>
  </section>