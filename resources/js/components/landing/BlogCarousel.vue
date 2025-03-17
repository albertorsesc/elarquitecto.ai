<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Article } from '@/types/article';

defineProps<{
  articles: {
      data: Article[];
      links: {
          first: string;
          last: string;
          prev: string | null;
          next: string | null;
      };
  }
}>();

// Function to handle image loading errors
function handleImageError(event: Event) {
  const target = event.target as HTMLImageElement;
  target.src = 'https://via.placeholder.com/400x200/1a1a2e/ffffff?text=El+Arquitecto+A.I.';
}
</script>

<template>
  <section class="py-12 sm:py-16">
    <div class="mx-auto max-w-6xl px-4">
      <h2 class="text-glow-light mb-8 text-center text-2xl font-bold text-accent sm:mb-12 sm:text-3xl md:text-4xl">
        Últimos Artículos
      </h2>

      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="article in articles.data"
          :key="article.id"
          class="group relative overflow-hidden rounded-xl border border-white/10 bg-background/50 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:shadow-[0_0_30px_rgba(0,0,0,0.3)] shadow-[0_4px_20px_rgba(0,0,0,0.2)]"
        >
          <!-- Image container with effects -->
          <div class="relative aspect-[16/9] overflow-hidden">
            <!-- Cyberpunk overlay effect -->
            <div class="absolute inset-0 z-10 bg-gradient-to-t from-background via-transparent to-transparent opacity-60"></div>
            <div class="absolute inset-0 z-10 bg-gradient-to-l from-primary/20 via-transparent to-secondary/20 opacity-30 mix-blend-overlay"></div>

            <!-- Glitch effect lines -->
            <div class="absolute inset-0 z-20 overflow-hidden opacity-0 mix-blend-screen transition-opacity duration-300 group-hover:opacity-30">
              <div class="absolute inset-x-0 top-1/4 h-[1px] animate-glitch-line-1 bg-cyan-400"></div>
              <div class="absolute inset-x-0 top-1/3 h-[1px] animate-glitch-line-2 bg-primary"></div>
              <div class="absolute inset-x-0 top-1/2 h-[1px] animate-glitch-line-3 bg-accent"></div>
            </div>

            <!-- Image with hover zoom -->
            <img
              :src="article.image"
              :alt="article.title"
              class="h-full w-full object-cover transition-transform duration-700 will-change-transform group-hover:scale-110"
              @error="handleImageError"
            />

            <!-- Scanline effect -->
            <div class="absolute inset-0 z-30 bg-scanline opacity-10"></div>

            <!-- Category badge -->
            <span class="absolute bottom-4 left-4 z-20 rounded bg-background/80 px-2 py-1 text-xs font-medium text-primary backdrop-blur-sm transition-colors duration-300 group-hover:bg-primary group-hover:text-white">
              {{ article.category }}
            </span>
          </div>

          <div class="relative p-4">
            <!-- Content -->
            <h3 class="mb-2 text-lg font-semibold text-foreground transition-colors duration-300 group-hover:text-glow-multi">
              {{ article.title }}
            </h3>
            <p class="text-sm text-foreground/70">
              {{ article.excerpt }}
            </p>

            <!-- Hover effect overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-background/50 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
          </div>

          <!-- Neon border effects -->
          <div class="pointer-events-none absolute inset-0 rounded-xl">
            <!-- Multi-colored sliding neon lights -->
            <div class="absolute -inset-1 opacity-30 group-hover:opacity-100">
              <!-- Top edge -->
              <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right-color bg-gradient-to-r from-transparent via-[#FF1CF7] to-transparent"></div>
              <!-- Right edge -->
              <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down-color bg-gradient-to-b from-transparent via-[#00FFE1] to-transparent"></div>
              <!-- Bottom edge -->
              <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left-color bg-gradient-to-r from-transparent via-[#01FF88] to-transparent"></div>
              <!-- Left edge -->
              <div class="absolute left-0 top-0 h-full w-[2px] animate-neon-slide-up-color bg-gradient-to-b from-transparent via-[#5B6EF7] to-transparent"></div>
            </div>
          </div>

          <!-- Link overlay -->
          <Link :href="'/blog/' + article.id" class="absolute inset-0">
            <span class="sr-only">Leer más sobre {{ article.title }}</span>
          </Link>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
/* Existing animations */
@keyframes glowColor {
  0%, 100% {
    opacity: 0.3;
    --tw-gradient-from: #7C3AED;
    --tw-gradient-via: #22D3EE;
  }
  25% {
    opacity: 0.8;
    --tw-gradient-from: #22D3EE;
    --tw-gradient-via: #F471B5;
  }
  50% {
    opacity: 0.5;
    --tw-gradient-from: #F471B5;
    --tw-gradient-via: #818CF8;
  }
  75% {
    opacity: 0.8;
    --tw-gradient-from: #818CF8;
    --tw-gradient-via: #7C3AED;
  }
}

/* Glitch line animations */
@keyframes glitchLine1 {
  0%, 100% { transform: translateX(-100%); }
  50% { transform: translateX(100%); }
}

@keyframes glitchLine2 {
  0%, 100% { transform: translateX(100%); }
  50% { transform: translateX(-100%); }
}

@keyframes glitchLine3 {
  0%, 100% { transform: translateX(-50%); }
  50% { transform: translateX(50%); }
}

.animate-glitch-line-1 {
  animation: glitchLine1 3s linear infinite;
}

.animate-glitch-line-2 {
  animation: glitchLine2 4s linear infinite;
}

.animate-glitch-line-3 {
  animation: glitchLine3 2.5s linear infinite;
}

/* Scanline effect */
.bg-scanline {
  background: repeating-linear-gradient(
    to bottom,
    transparent 0%,
    rgba(255, 255, 255, 0.05) 0.5%,
    transparent 1%
  );
}

/* Existing animations... */
.animate-neon-slide-right-color {
  animation: neonSlideRightColor 4s linear infinite;
}

.animate-neon-slide-left-color {
  animation: neonSlideLeftColor 4s linear infinite;
}

.animate-neon-slide-down-color {
  animation: neonSlideDownColor 4s linear infinite;
}

.animate-neon-slide-up-color {
  animation: neonSlideUpColor 4s linear infinite;
}

.text-glow-multi {
  color: #F471B5;
  text-shadow: 0 0 10px rgba(244, 113, 181, 0.5);
  animation: iconColor 6s infinite;
}
</style>
