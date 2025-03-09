<script setup lang="ts">
// GlassContainer.vue - Provides glass-effect containers

import NeonBorders from './NeonBorders.vue';
import NeonCorners from './NeonCorners.vue';

interface Props {
  variant?: 'default' | 'dark' | 'light';
  withBorders?: boolean;
  withCorners?: boolean;
  rounded?: 'none' | 'sm' | 'md' | 'lg' | 'xl' | 'full';
  padding?: 'none' | 'sm' | 'md' | 'lg' | 'xl';
  borderColor?: 'primary' | 'secondary' | 'accent' | 'cyan' | 'white';
  cornerColor?: 'primary' | 'secondary' | 'accent' | 'cyan';
}

defineProps<Props>();

// Variant classes
const variantClasses = {
  default: 'bg-background/70 border-white/10',
  dark: 'bg-background/90 border-white/5',
  light: 'bg-background/50 border-white/20',
};

// Rounded classes
const roundedClasses = {
  none: '',
  sm: 'rounded-sm',
  md: 'rounded-md',
  lg: 'rounded-lg',
  xl: 'rounded-xl',
  full: 'rounded-full',
};

// Padding classes
const paddingClasses = {
  none: 'p-0',
  sm: 'p-2 sm:p-3',
  md: 'p-3 sm:p-4',
  lg: 'p-4 sm:p-6',
  xl: 'p-6 sm:p-8',
};

// Border color classes
const borderColorClasses = {
  primary: 'border-primary/30',
  secondary: 'border-secondary/30',
  accent: 'border-accent/30',
  cyan: 'border-cyan-400/30',
  white: 'border-white/10',
};
</script>

<template>
  <div
    class="glass-effect relative border backdrop-blur-md"
    :class="[
      variantClasses[variant || 'default'],
      roundedClasses[rounded || 'lg'],
      paddingClasses[padding || 'md'],
      borderColorClasses[borderColor || 'white']
    ]"
  >
    <slot></slot>

    <!-- Optional neon borders -->
    <NeonBorders v-if="withBorders" position="all" color="gradient" :opacity="0.3" />

    <!-- Optional corner accents -->
    <NeonCorners v-if="withCorners" position="all" :color="cornerColor || 'primary'" size="md" />
  </div>
</template>

<style scoped>
.glass-container {
  backdrop-filter: blur(10px);
}
</style>