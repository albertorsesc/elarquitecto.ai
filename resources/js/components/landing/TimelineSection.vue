<script setup lang="ts">
interface TimelineItem {
  date: string;
  title: string;
  description: string;
}

defineProps<{
  items: TimelineItem[];
}>();
</script>

<template>
  <section class="relative py-12 sm:py-20">
    <!-- Background effect -->
    <div class="absolute inset-0 bg-gradient-to-t from-background/50 to-transparent">
      <div class="cyberpunk-grid-bg absolute inset-0 opacity-10"></div>
    </div>

    <div class="mx-auto max-w-6xl px-4">
      <h2 class="text-glow-light mb-8 text-center text-2xl font-bold text-secondary sm:mb-12 sm:text-3xl md:text-4xl">
        Línea de Tiempo
      </h2>

      <div class="relative">
        <!-- Timeline line -->
        <div class="absolute left-3 top-0 h-full w-0.5 bg-gradient-to-b from-secondary via-cyan-400 to-primary sm:left-4 md:left-1/2"></div>

        <!-- Timeline items -->
        <div class="space-y-6 sm:space-y-8">
          <div v-for="(item, index) in items" :key="index"
               class="relative flex flex-col md:flex-row md:even:flex-row-reverse"
               :class="{ 'md:flex-row-reverse': index % 2 !== 0 }">
            <!-- Date -->
            <div class="ml-8 sm:ml-10 md:ml-0 md:w-1/2" :class="{ 'md:pr-8': index % 2 === 0, 'md:pl-8': index % 2 !== 0 }">
              <span class="inline-block text-sm font-semibold text-cyan-400 sm:text-lg">{{ item.date }}</span>
            </div>

            <!-- Content -->
            <div class="ml-8 sm:ml-10 md:ml-0 md:w-1/2" :class="{ 'md:pl-8': index % 2 === 0, 'md:pr-8': index % 2 !== 0 }">
              <div class="glass-effect rounded-lg border border-white/10 bg-background/70 p-4 backdrop-blur-md sm:p-6">
                <h3 class="mb-1 text-base font-semibold text-primary sm:mb-2 sm:text-xl">{{ item.title }}</h3>
                <p class="text-sm text-foreground/80 sm:text-base">{{ item.description }}</p>
              </div>

              <!-- Timeline dot -->
              <div class="absolute left-3 top-0 h-3 w-3 rounded-full border-2 border-cyan-400 bg-background sm:left-4 sm:h-4 sm:w-4 md:left-1/2 md:-ml-2">
                <div class="absolute inset-0.5 rounded-full bg-cyan-400"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>