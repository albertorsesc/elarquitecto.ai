<section class="relative py-12 sm:py-20">
    <!-- Background effect -->
    <div class="absolute inset-0 bg-gradient-to-t from-background/50 to-transparent">
      <div class="cyberpunk-grid-bg absolute inset-0 opacity-10"></div>
    </div>

    <div class="mx-auto max-w-6xl">
      <h2 class="text-glow-light mb-8 text-center text-2xl font-bold text-secondary sm:mb-12 sm:text-3xl md:text-4xl">
        Novedades
      </h2>

      <!-- Tools Timeline Section -->
      @if(isset($tools) && count($tools) > 0)
        <div class="mb-12">
          <h3 class="text-center text-xl font-semibold mb-8 text-cyan-400 animate-text-glow">
            Herramientas Recientes
          </h3>
          @include('landing.components.timeline-container', [
            'items' => $tools,
            'withScrollbar' => false
          ])
          <div class="text-center mt-6 relative z-10">
            <a href="/herramientas" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-cyan-400 hover:text-cyan-300 transition-colors duration-200 border border-cyan-400/20 hover:border-cyan-400/40 rounded-lg bg-cyan-400/5 hover:bg-cyan-400/10">
              Ver más
              <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
        </div>
      @endif

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
          <div class="text-center mt-6 relative z-10">
            <a href="/prompts" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-primary hover:text-primary-light transition-colors duration-200 border border-primary/20 hover:border-primary/40 rounded-lg bg-primary/5 hover:bg-primary/10">
              Ver más
              <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
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
          <div class="text-center mt-6 relative z-10">
            <a href="/blog" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-secondary hover:text-secondary-light transition-colors duration-200 border border-secondary/20 hover:border-secondary/40 rounded-lg bg-secondary/5 hover:bg-secondary/10">
              Ver más
              <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
        </div>
      @endif
    </div>
  </section>