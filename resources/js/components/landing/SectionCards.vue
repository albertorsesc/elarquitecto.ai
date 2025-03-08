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
          class="group relative overflow-hidden rounded-xl border border-white/10 bg-background/50 p-6 backdrop-blur-sm transition-all duration-300 hover:border-primary/30 hover:shadow-[0_0_20px_rgba(124,58,237,0.2)]"
        >
          <!-- Animated corner accents -->
          <div class="absolute left-0 top-0 h-8 w-8 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
            <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
            <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
          </div>
          <div class="absolute right-0 top-0 h-8 w-8 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
            <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
            <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
          </div>

          <!-- Icon -->
          <div class="mb-4 inline-block rounded-lg bg-primary/10 p-3 text-primary transition-all duration-300 group-hover:shadow-[0_0_15px_rgba(124,58,237,0.3)]">
            <Icon :icon="section.icon" class="h-6 w-6" />
          </div>

          <!-- Content -->
          <h3 class="mb-2 text-lg font-semibold text-foreground transition-colors group-hover:text-primary">
            {{ section.title }}
          </h3>
          <p class="text-sm text-foreground/70">
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

.animate-glow {
  animation: glow 3s infinite;
}
</style>