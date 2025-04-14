@props(['sections' => []])

@php
    $enabledSections = ['Prompts'];
@endphp

<section class="py-12 sm:py-16">
  <div class="mx-auto max-w-6xl px-4">
    <h2 class="text-glow-light mb-8 text-center text-2xl font-bold text-accent sm:mb-12 sm:text-3xl md:text-4xl">
      Explora Nuestras Secciones
    </h2>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      @foreach($sections as $section)
        <div
          class="group relative overflow-hidden rounded-xl border border-white/10 bg-background/50 p-6 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:shadow-[0_0_30px_rgba(0,0,0,0.3)] shadow-[0_4px_20px_rgba(0,0,0,0.2)] {{ in_array($section['title'], $enabledSections) ? 'cursor-pointer hover:-translate-y-1' : '' }}"
        >
          <!-- Animated corner accents -->
          <x-color-animated-corners />

          <!-- Neon border glow with breathing effect -->
          <div class="pointer-events-none absolute inset-0 rounded-xl">
            <x-white-border />

            <!-- Multi-colored sliding neon lights -->
            <x-multi-color-sliding-neon />

            <!-- Breathing glow effect -->
            <x-breathing-glow />
          </div>

          <!-- Icon with dynamic color -->
          <div class="relative z-10 mb-4 inline-block rounded-lg bg-white/5 p-3 transition-all duration-300 group-hover:shadow-[0_0_15px_rgba(255,255,255,0.2)] group-hover:bg-white/10">
            {{-- Replace Iconify with appropriate SVG icons based on section.icon value --}}
            @switch($section['icon'])
                @case('carbon:blog')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-icon-color" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    @break
                @case('carbon:chat')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-icon-color" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    @break
                @case('carbon:tools')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-icon-color" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    @break
                @case('carbon:forum')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-icon-color" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    @break
                @case('carbon:document')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-icon-color" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    @break
                @case('carbon:group')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-icon-color" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @break
                @default
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-icon-color" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
            @endswitch
          </div>

          <!-- Content -->
          <h3 class="relative z-10 mb-2 text-lg font-semibold text-foreground transition-colors duration-300 group-hover:text-glow-multi">
            {{ $section['title'] }}
            @if(!in_array($section['title'], $enabledSections))
              <span class="text-xs text-gray-400 ml-3">Próximamente</span>
            @endif
          </h3>
          <p class="relative z-10 text-sm text-foreground/70">
            {{ $section['description'] }}
          </p>

          <!-- Hover effect overlay -->
          <x-hover-gradient-overlay />

          <!-- Link overlay -->
          @if(in_array($section['title'], $enabledSections))
            <a href="{{ $section['link'] }}" class="absolute inset-0">
              <span class="sr-only">Ir a {{ $section['title'] }}</span>
            </a>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</section>