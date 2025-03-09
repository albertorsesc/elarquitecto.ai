<script setup lang="ts">
import { Icon } from '@iconify/vue';

interface Section {
  id: number;
  title: string;
  description: string;
  icon: string;
  link: string;
}

defineProps<{
  sections: Section[];
}>();
</script>

<template>
  <section class="py-12 sm:py-16">
    <div class="mx-auto max-w-6xl px-4">
      <h2 class="text-glow-light mb-8 text-center text-2xl font-bold text-accent sm:mb-12 sm:text-3xl md:text-4xl">
        Explora Nuestras Secciones
      </h2>

      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="section in sections"
          :key="section.id"
          class="group relative overflow-hidden rounded-xl border border-white/10 bg-background/50 p-6 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:shadow-[0_0_30px_rgba(0,0,0,0.3)] shadow-[0_4px_20px_rgba(0,0,0,0.2)]"
        >
          <!-- Animated corner accents -->
          <div class="absolute left-0 top-0 h-8 w-8 opacity-30 group-hover:opacity-100">
            <div class="absolute left-0 top-0 h-full w-[1px] animate-glow-color bg-gradient-to-b from-primary via-cyan-400 to-transparent"></div>
            <div class="absolute left-0 top-0 h-[1px] w-full animate-glow-color bg-gradient-to-r from-primary via-cyan-400 to-transparent"></div>
          </div>
          <div class="absolute right-0 top-0 h-8 w-8 opacity-30 group-hover:opacity-100">
            <div class="absolute right-0 top-0 h-full w-[1px] animate-glow-color-delayed bg-gradient-to-b from-secondary via-accent to-transparent"></div>
            <div class="absolute right-0 top-0 h-[1px] w-full animate-glow-color-delayed bg-gradient-to-l from-secondary via-accent to-transparent"></div>
          </div>

          <!-- Neon border glow with breathing effect -->
          <div class="pointer-events-none absolute inset-0 rounded-xl">
            <div class="absolute inset-0 rounded-xl border border-white/10 transition-all duration-300 group-hover:border-white/20"></div>

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

            <!-- Breathing glow effect -->
            <div class="absolute inset-0 animate-breath rounded-xl bg-gradient-to-br from-primary/5 via-cyan-400/5 to-accent/5 opacity-0 group-hover:opacity-100"></div>
          </div>

          <!-- Icon with dynamic color -->
          <div class="relative z-10 mb-4 inline-block rounded-lg bg-white/5 p-3 transition-all duration-300 group-hover:shadow-[0_0_15px_rgba(255,255,255,0.2)] group-hover:bg-white/10">
            <Icon :icon="section.icon" class="h-6 w-6 animate-icon-color" />
          </div>

          <!-- Content -->
          <h3 class="relative z-10 mb-2 text-lg font-semibold text-foreground transition-colors duration-300 group-hover:text-glow-multi">
            {{ section.title }}
          </h3>
          <p class="relative z-10 text-sm text-foreground/70">
            {{ section.description }}
          </p>

          <!-- Hover effect overlay -->
          <div class="absolute inset-0 bg-gradient-to-t from-background/50 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>

          <!-- Link overlay -->
          <Link :href="section.link" class="absolute inset-0">
            <span class="sr-only">Ir a {{ section.title }}</span>
          </Link>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
@keyframes glow {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.8; }
}

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

@keyframes breath {
  0%, 100% {
    opacity: 0.1;
    transform: scale(0.99);
  }
  50% {
    opacity: 0.2;
    transform: scale(1.01);
  }
}

@keyframes iconColor {
  0%, 100% { color: #7C3AED; }
  25% { color: #22D3EE; }
  50% { color: #F471B5; }
  75% { color: #818CF8; }
}

@keyframes neonSlideRightColor {
  0% {
    transform: translateX(-100%);
    opacity: 0.6;
    --tw-gradient-via: #FF1CF7;
  }
  33% {
    --tw-gradient-via: #00FFE1;
  }
  66% {
    --tw-gradient-via: #01FF88;
  }
  100% {
    transform: translateX(100%);
    opacity: 0.6;
    --tw-gradient-via: #5B6EF7;
  }
}

@keyframes neonSlideLeftColor {
  0% {
    transform: translateX(100%);
    opacity: 0.6;
    --tw-gradient-via: #00FFE1;
  }
  33% {
    --tw-gradient-via: #01FF88;
  }
  66% {
    --tw-gradient-via: #5B6EF7;
  }
  100% {
    transform: translateX(-100%);
    opacity: 0.6;
    --tw-gradient-via: #FF1CF7;
  }
}

@keyframes neonSlideDownColor {
  0% {
    transform: translateY(-100%);
    opacity: 0.6;
    --tw-gradient-via: #01FF88;
  }
  33% {
    --tw-gradient-via: #5B6EF7;
  }
  66% {
    --tw-gradient-via: #FF1CF7;
  }
  100% {
    transform: translateY(100%);
    opacity: 0.6;
    --tw-gradient-via: #00FFE1;
  }
}

@keyframes neonSlideUpColor {
  0% {
    transform: translateY(100%);
    opacity: 0.6;
    --tw-gradient-via: #5B6EF7;
  }
  33% {
    --tw-gradient-via: #FF1CF7;
  }
  66% {
    --tw-gradient-via: #00FFE1;
  }
  100% {
    transform: translateY(-100%);
    opacity: 0.6;
    --tw-gradient-via: #01FF88;
  }
}

.animate-glow-color {
  animation: glowColor 6s infinite;
}

.animate-glow-color-delayed {
  animation: glowColor 6s infinite 3s;
}

.animate-breath {
  animation: breath 4s ease-in-out infinite;
}

.animate-icon-color {
  animation: iconColor 6s infinite;
}

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