<script setup lang="ts">
import TimelineContainer from '@/components/theme/TimelineContainer.vue';
import { TimelineItem } from '@/types/timeline-item';

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

      <TimelineContainer
        :items="items"
        :withScrollbar="false"
      />
    </div>
  </section>
</template>