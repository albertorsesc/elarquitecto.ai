<script setup lang="ts">
import FloatingNeonLines from '@/components/theme/FloatingNeonLines.vue';
import NeonBorders from '@/components/theme/NeonBorders.vue';
import NeonCorners from '@/components/theme/NeonCorners.vue';

// Cyberpunk color palette
const colors = [
  { name: 'Neon Purple', variable: '--primary', hex: '#8A2BE2', class: 'bg-primary' },
  { name: 'Neon Blue', variable: '--secondary', hex: '#00BFFF', class: 'bg-secondary' },
  { name: 'Cyber Cyan', variable: '--cyan', hex: '#22D3EE', class: 'bg-cyan-400' },
  { name: 'Neon Pink', variable: '--accent', hex: '#FF1493', class: 'bg-accent' },
  { name: 'Cyber Green', variable: '--chart-4', hex: '#00CC66', class: 'bg-[hsl(var(--chart-4))]' }
];

// Animation examples
const animations = [
  { name: 'Neon Slide Right', class: 'animate-neon-slide-right' },
  { name: 'Neon Slide Left', class: 'animate-neon-slide-left' },
  { name: 'Neon Slide Up', class: 'animate-neon-slide-up' },
  { name: 'Neon Slide Down', class: 'animate-neon-slide-down' },
  { name: 'Glow', class: 'animate-glow' },
  { name: 'Pulse Slow', class: 'animate-pulse-slow' }
];

// Text effects
const textEffects = [
  { name: 'Light Glow', class: 'text-glow-light' },
  { name: 'Medium Glow', class: 'text-glow-medium' },
  { name: 'Strong Glow', class: 'text-glow-strong' }
];

// Button variants
const buttonVariants = [
  { name: 'Primary', class: 'bg-primary' },
  { name: 'Secondary', class: 'bg-secondary' },
  { name: 'Accent', class: 'bg-accent' },
  { name: 'Outline', class: 'border border-white/20' }
];
</script>

<template>
  <section class="relative py-12 sm:py-20">
    <div class="mx-auto max-w-6xl px-4">
      <h2 class="text-glow-light mb-8 text-center text-2xl font-bold text-primary sm:mb-12 sm:text-3xl md:text-4xl">
        Cyberpunk Style Guide
      </h2>

      <!-- Color Palette -->
      <div class="glass-effect rounded-lg border border-white/10 bg-background/70 p-4 backdrop-blur-xl sm:p-6 mb-8">
        <h3 class="text-lg font-semibold text-foreground mb-4">Color Palette</h3>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 md:grid-cols-5">
          <div v-for="color in colors" :key="color.name"
               class="flex flex-col overflow-hidden rounded-lg border border-white/10">
            <div :class="[color.class, 'h-16 w-full sm:h-24']"></div>
            <div class="bg-background/80 p-3 sm:p-4">
              <h3 class="text-sm font-semibold text-foreground sm:text-base">{{ color.name }}</h3>
              <p class="text-xs text-foreground/70 sm:text-sm">{{ color.hex }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Animations -->
      <div class="glass-effect rounded-lg border border-white/10 bg-background/70 p-4 backdrop-blur-xl sm:p-6 mb-8">
        <h3 class="text-lg font-semibold text-foreground mb-4">Animations</h3>
        <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3">
          <div v-for="animation in animations" :key="animation.name"
               class="relative overflow-hidden rounded-lg border border-white/10 p-4">
            <div :class="[animation.class, 'h-2 w-full bg-gradient-to-r from-primary via-secondary to-accent']"></div>
            <p class="mt-2 text-sm text-foreground">{{ animation.name }}</p>
          </div>
        </div>
      </div>

      <!-- Text Effects -->
      <div class="glass-effect rounded-lg border border-white/10 bg-background/70 p-4 backdrop-blur-xl sm:p-6 mb-8">
        <h3 class="text-lg font-semibold text-foreground mb-4">Text Effects</h3>
        <div class="grid gap-3 sm:grid-cols-3">
          <div v-for="effect in textEffects" :key="effect.name"
               class="rounded-lg border border-white/10 p-4">
            <p :class="[effect.class, 'text-lg text-primary']">{{ effect.name }}</p>
          </div>
        </div>
      </div>

      <!-- Glass Effects -->
      <div class="glass-effect rounded-lg border border-white/10 bg-background/70 p-4 backdrop-blur-xl sm:p-6 mb-8">
        <h3 class="text-lg font-semibold text-foreground mb-4">Glass Effects</h3>
        <div class="grid gap-3 sm:grid-cols-2">
          <div class="glass-effect h-24 rounded-lg border border-white/10 bg-primary/20 backdrop-blur-xl relative group">
            <div class="absolute inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
              <NeonBorders color="primary" :opacity="0.3" />
            </div>
            <div class="flex items-center justify-center h-full">
              <p class="text-sm text-foreground">Hover me</p>
            </div>
          </div>
          <div class="glass-effect h-24 rounded-lg border border-white/10 bg-secondary/20 backdrop-blur-xl relative group">
            <div class="absolute inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
              <NeonCorners color="secondary" />
            </div>
            <div class="flex items-center justify-center h-full">
              <p class="text-sm text-foreground">Hover me</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Buttons -->
      <div class="glass-effect rounded-lg border border-white/10 bg-background/70 p-4 backdrop-blur-xl sm:p-6 mb-8">
        <h3 class="text-lg font-semibold text-foreground mb-4">Buttons</h3>
        <div class="flex flex-wrap gap-3">
          <button v-for="variant in buttonVariants" :key="variant.name"
                  class="relative px-4 py-2 rounded-md font-semibold transition-all duration-200 group">
            <div :class="[variant.class, 'absolute inset-0 rounded-md opacity-80 group-hover:opacity-100 transition-opacity']"></div>
            <span class="relative z-10 text-white">{{ variant.name }}</span>
            <div class="absolute inset-0 rounded-md opacity-0 transition-opacity duration-300 group-hover:opacity-100 group-hover:shadow-[0_0_15px_rgba(124,58,237,0.5)]"></div>
          </button>
        </div>
      </div>

      <!-- Gradients -->
      <div class="glass-effect rounded-lg border border-white/10 bg-background/70 p-4 backdrop-blur-xl sm:p-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">Gradients</h3>
        <div class="grid gap-3 sm:grid-cols-3">
          <div class="h-16 rounded-lg bg-gradient-to-r from-primary to-secondary sm:h-24"></div>
          <div class="h-16 rounded-lg bg-gradient-to-r from-secondary to-accent sm:h-24"></div>
          <div class="h-16 rounded-lg bg-gradient-to-r from-accent to-primary sm:h-24"></div>
        </div>
      </div>
    </div>

    <!-- Floating neon lines -->
    <FloatingNeonLines variant="default" :opacity="0.2" />

    <!-- Corner accents -->
    <div class="absolute left-0 top-0 h-8 w-8">
      <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-secondary via-transparent to-transparent"></div>
      <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-secondary via-transparent to-transparent"></div>
    </div>
    <div class="absolute right-0 top-0 h-8 w-8">
      <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-accent via-transparent to-transparent"></div>
      <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-accent via-transparent to-transparent"></div>
    </div>
  </section>
</template>

<style scoped>
/* Ensure all animations are smooth */
* {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* Enhance glass effect */
.glass-effect {
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.glass-effect:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(124, 58, 237, 0.2);
}

/* Button hover effects */
button {
  overflow: hidden;
}

button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.2),
    transparent
  );
  transition: 0.5s;
}

button:hover::before {
  left: 100%;
}

/* Code syntax highlighting */
code {
  color: #F1F5F9;
}

code .keyword {
  color: #C792EA;
}

code .string {
  color: #C3E88D;
}

code .comment {
  color: #9CA3AF;
}

/* Enhanced glass effect for code blocks */
pre {
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}
</style>