<script setup lang="ts">
// Cyberpunk color palette
const colors = [
  { name: 'Neon Purple', variable: '--primary', hex: '#8A2BE2', class: 'bg-primary' },
  { name: 'Neon Blue', variable: '--secondary', hex: '#00BFFF', class: 'bg-secondary' },
  { name: 'Neon Pink', variable: '--accent', hex: '#FF1493', class: 'bg-accent' },
  { name: 'Cyber Green', variable: '--chart-4', hex: '#00CC66', class: 'bg-[hsl(var(--chart-4))]' },
  { name: 'Cyber Yellow', variable: '--chart-5', hex: '#FFB700', class: 'bg-[hsl(var(--chart-5))]' }
];
</script>

<template>
  <section class="relative py-12 sm:py-20">
    <div class="container mx-auto px-4">
      <h2 class="text-glow mb-8 text-center text-2xl font-bold text-primary sm:mb-12 sm:text-3xl md:text-4xl">
        Paleta de Colores
      </h2>

      <div class="glass-effect rounded-lg border border-white/10 bg-background/70 p-4 backdrop-blur-xl sm:p-6">
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 md:grid-cols-5">
          <div v-for="color in colors" :key="color.name"
               class="flex flex-col overflow-hidden rounded-lg border border-white/10">
            <!-- Color display -->
            <div :class="[color.class, 'h-16 w-full sm:h-24']"></div>

            <!-- Color info -->
            <div class="bg-background/80 p-3 sm:p-4">
              <h3 class="text-sm font-semibold text-foreground sm:text-base">{{ color.name }}</h3>
              <p class="text-xs text-foreground/70 sm:text-sm">{{ color.hex }}</p>
            </div>
          </div>
        </div>

        <!-- Gradient examples -->
        <div class="mt-6 sm:mt-8">
          <h3 class="mb-3 text-lg font-semibold text-foreground sm:mb-4 sm:text-xl">Gradientes</h3>
          <div class="grid gap-3 sm:grid-cols-3 sm:gap-4">
            <div class="h-16 rounded-lg bg-gradient-to-r from-primary to-secondary sm:h-24"></div>
            <div class="h-16 rounded-lg bg-gradient-to-r from-secondary to-accent sm:h-24"></div>
            <div class="h-16 rounded-lg bg-gradient-to-r from-accent to-primary sm:h-24"></div>
          </div>
        </div>

        <!-- Glass effect examples -->
        <div class="mt-6 sm:mt-8">
          <h3 class="mb-3 text-lg font-semibold text-foreground sm:mb-4 sm:text-xl">Efectos de Transparencia</h3>
          <div class="grid gap-3 sm:grid-cols-2 sm:gap-4">
            <div class="glass-effect h-16 rounded-lg border border-white/10 bg-primary/20 backdrop-blur-xl sm:h-24"></div>
            <div class="glass-effect h-16 rounded-lg border border-white/10 bg-secondary/20 backdrop-blur-xl sm:h-24"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>