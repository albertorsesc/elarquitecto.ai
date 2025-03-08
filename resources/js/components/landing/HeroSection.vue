<script setup lang="ts">
defineProps<{
  title: string;
  subtitle: string;
}>();
</script>

<template>
  <section class="relative min-h-[80vh] w-full overflow-hidden pt-20 sm:pt-24">
    <!-- Cyberpunk background with gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary/20 via-secondary/20 to-accent/20">
      <div class="cyberpunk-grid-bg absolute inset-0 opacity-20"></div>
    </div>

    <!-- Glass container -->
    <div class="mx-auto max-w-6xl px-4 py-12 sm:py-20">
      <div class="glass-effect rounded-lg border border-white/10 bg-background/70 p-4 backdrop-blur-xl sm:p-8">
        <div class="max-w-3xl">
          <h1 class="mb-4 text-3xl font-bold sm:mb-6 sm:text-4xl md:text-6xl">
            <span class="mr-2 text-primary">El Arquitecto</span>
            <span class="text-cyan-400">A.I.</span>
          </h1>
          <p class="text-lg text-foreground/90 sm:text-xl md:text-2xl">
            {{ subtitle }}
          </p>

          <!-- Cyberpunk-styled buttons -->
          <div class="mt-6 flex flex-wrap gap-3 sm:mt-8 sm:gap-4">
            <button class="neon-border rounded bg-primary px-4 py-2 text-sm font-semibold text-white transition-all hover:bg-primary/80 sm:px-6 sm:py-3">
              Explorar
            </button>
            <button class="rounded border border-primary/50 bg-transparent px-4 py-2 text-sm font-semibold text-primary transition-all hover:bg-primary/10 sm:px-6 sm:py-3">
              Más Info
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Decorative elements -->
    <div class="absolute bottom-0 left-0 h-32 w-32 rotate-45 bg-accent/20 blur-3xl"></div>
    <div class="absolute right-0 top-0 h-32 w-32 rotate-45 bg-secondary/20 blur-3xl"></div>
  </section>
</template>