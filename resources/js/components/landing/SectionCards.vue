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
          class="group relative overflow-hidden rounded-xl border border-white/10 bg-background/50 p-6 backdrop-blur-sm transition-all duration-300 hover:border-primary/30 hover:shadow-[0_0_30px_rgba(124,58,237,0.3)] shadow-[0_4px_20px_rgba(0,0,0,0.2)]"
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

          <!-- Neon border glow -->
          <div class="pointer-events-none absolute inset-0 rounded-xl">
            <div class="absolute inset-0 rounded-xl border border-white/10 transition-all duration-300 group-hover:border-primary/30 group-hover:shadow-[0_0_15px_rgba(124,58,237,0.3),inset_0_0_10px_rgba(124,58,237,0.1)]"></div>

            <!-- Permanent sliding neon lights with lower opacity -->
            <div class="absolute -inset-1 opacity-30 group-hover:opacity-100">
              <!-- Top edge -->
              <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right-slow bg-gradient-to-r from-transparent via-cyan-400 to-transparent"></div>
              <!-- Right edge -->
              <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down-slow bg-gradient-to-b from-transparent via-secondary to-transparent"></div>
              <!-- Bottom edge -->
              <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left-slow bg-gradient-to-r from-transparent via-accent to-transparent"></div>
              <!-- Left edge -->
              <div class="absolute left-0 top-0 h-full w-[2px] animate-neon-slide-up-slow bg-gradient-to-b from-transparent via-primary to-transparent"></div>
            </div>
          </div>

          <!-- Icon -->
          <div class="relative z-10 mb-4 inline-block rounded-lg bg-primary/10 p-3 text-primary transition-all duration-300 group-hover:shadow-[0_0_15px_rgba(124,58,237,0.3)] group-hover:bg-primary/20">
            <Icon :icon="section.icon" class="h-6 w-6" />
          </div>

          <!-- Content -->
          <h3 class="relative z-10 mb-2 text-lg font-semibold text-foreground transition-colors duration-300 group-hover:text-primary group-hover:text-shadow-sm">
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

.animate-glow {
  animation: glow 3s infinite;
}

@keyframes neonSlideRight {
  0% { transform: translateX(-100%); opacity: 0.6; }
  50% { opacity: 1; }
  100% { transform: translateX(100%); opacity: 0.6; }
}

@keyframes neonSlideLeft {
  0% { transform: translateX(100%); opacity: 0.6; }
  50% { opacity: 1; }
  100% { transform: translateX(-100%); opacity: 0.6; }
}

@keyframes neonSlideDown {
  0% { transform: translateY(-100%); opacity: 0.6; }
  50% { opacity: 1; }
  100% { transform: translateY(100%); opacity: 0.6; }
}

@keyframes neonSlideUp {
  0% { transform: translateY(100%); opacity: 0.6; }
  50% { opacity: 1; }
  100% { transform: translateY(-100%); opacity: 0.6; }
}

.animate-neon-slide-right-slow {
  animation: neonSlideRight 4s linear infinite;
}

.animate-neon-slide-left-slow {
  animation: neonSlideLeft 4s linear infinite;
}

.animate-neon-slide-down-slow {
  animation: neonSlideDown 4s linear infinite;
}

.animate-neon-slide-up-slow {
  animation: neonSlideUp 4s linear infinite;
}
</style>