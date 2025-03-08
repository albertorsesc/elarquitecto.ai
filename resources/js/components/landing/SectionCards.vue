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
  <section class="relative py-12 sm:py-20">
    <div class="mx-auto max-w-6xl px-4">
      <h2 class="text-glow-light mb-8 text-center text-2xl font-bold text-secondary sm:mb-12 sm:text-3xl md:text-4xl">
        Explora
      </h2>

      <div class="grid gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Section cards -->
        <a v-for="section in sections" :key="section.id" :href="section.link"
           class="group relative overflow-hidden rounded-lg transition-transform hover:-translate-y-1">
          <!-- Card background with glass effect -->
          <div class="glass-effect relative h-full border border-white/10 bg-background/70 p-4 backdrop-blur-xl sm:p-6">
            <!-- Icon -->
            <div class="mb-3 text-3xl text-primary sm:mb-4 sm:text-4xl">
              <Icon :icon="section.icon" width="36" height="36" class="sm:h-12 sm:w-12" />
            </div>

            <!-- Content -->
            <h3 class="mb-1 text-lg font-bold text-primary group-hover:text-cyan-400 sm:mb-2 sm:text-xl">
              {{ section.title }}
            </h3>
            <p class="text-sm text-foreground/80 sm:text-base">{{ section.description }}</p>

            <!-- Hover effect -->
            <div class="absolute inset-0 border-2 border-transparent bg-gradient-to-r from-primary via-cyan-400 to-secondary opacity-0 transition-opacity group-hover:opacity-10"></div>
          </div>

          <!-- Decorative corner -->
          <div class="absolute right-0 top-0 h-12 w-12 overflow-hidden sm:h-16 sm:w-16">
            <div class="absolute right-0 top-0 h-3 w-3 translate-x-1.5 -translate-y-1.5 rotate-45 bg-cyan-400/50 sm:h-4 sm:w-4 sm:translate-x-2 sm:-translate-y-2"></div>
          </div>
        </a>
      </div>
    </div>
  </section>
</template>