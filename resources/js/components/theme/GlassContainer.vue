<script setup lang="ts">
// GlassContainer.vue - Glass effect container component
// This component provides a glass effect container with optional neon borders and corners

import NeonBorders from './NeonBorders.vue';
import NeonCorners from './NeonCorners.vue';

interface Props {
  variant?: 'default' | 'dark' | 'light';
  withBorders?: boolean;
  withCorners?: boolean;
  rounded?: 'none' | 'sm' | 'md' | 'lg' | 'xl' | 'full';
  padding?: 'none' | 'sm' | 'md' | 'lg' | 'xl';
  borderOpacity?: number;
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default',
  withBorders: true,
  withCorners: true,
  rounded: 'md',
  padding: 'md',
  borderOpacity: 0.3
});

// Map rounded values to Tailwind classes
const roundedMap = {
  'none': '',
  'sm': 'rounded',
  'md': 'rounded-md',
  'lg': 'rounded-lg',
  'xl': 'rounded-xl',
  'full': 'rounded-full'
};

// Map padding values to Tailwind classes
const paddingMap = {
  'none': '',
  'sm': 'p-2',
  'md': 'p-4',
  'lg': 'p-6',
  'xl': 'p-8'
};

// Get background based on variant
const getBackground = () => {
  switch (props.variant) {
    case 'dark': return 'bg-background/90';
    case 'light': return 'bg-white/10';
    default: return 'bg-background/70';
  }
};
</script>

<template>
  <div
    class="glass-container relative overflow-hidden border border-white/10"
    :class="[roundedMap[rounded], paddingMap[padding], getBackground()]"
  >
    <!-- Glass effect backdrop filter -->
    <div class="absolute inset-0 backdrop-blur-md -z-10"></div>

    <!-- Neon borders -->
    <NeonBorders v-if="withBorders" :opacity="borderOpacity" />

    <!-- Neon corners -->
    <NeonCorners v-if="withCorners" />

    <!-- Content -->
    <div class="relative z-10">
      <slot></slot>
    </div>
  </div>
</template>

<style scoped>
.glass-container {
  backdrop-filter: blur(10px);
}
</style>