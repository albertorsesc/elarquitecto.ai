<script setup lang="ts">
// NeonCorners.vue - Reusable neon corner accents for the application
// This component provides corner accents with glowing effects

interface Props {
  position?: 'top-left' | 'top-right' | 'bottom-left' | 'bottom-right' | 'all';
  color?: 'primary' | 'secondary' | 'accent' | 'cyan';
  size?: 'sm' | 'md' | 'lg';
}

const props = withDefaults(defineProps<Props>(), {
  position: 'all',
  color: 'primary',
  size: 'md'
});

// Map colors to their CSS variables
const colorMap = {
  primary: 'var(--primary)',
  secondary: 'var(--secondary)',
  accent: 'var(--accent)',
  cyan: 'var(--cyan-400)'
};

// Map sizes to pixel values
const sizeMap = {
  sm: '8px',
  md: '12px',
  lg: '16px'
};

// Get the color based on position and prop
const getColor = (pos: string) => {
  if (props.position === 'all') {
    switch (pos) {
      case 'top-left': return colorMap.primary;
      case 'top-right': return colorMap.cyan;
      case 'bottom-left': return colorMap.secondary;
      case 'bottom-right': return colorMap.accent;
      default: return colorMap[props.color];
    }
  }
  return colorMap[props.color];
};
</script>

<template>
  <div class="neon-corners-container">
    <!-- Top Left Corner -->
    <div v-if="position === 'top-left' || position === 'all'" class="absolute left-0 top-0" :style="{ height: sizeMap[size], width: sizeMap[size] }">
      <div class="absolute left-0 top-0 h-full w-[1px] animate-glow" :style="{ background: `linear-gradient(to bottom, ${getColor('top-left')}, transparent)` }"></div>
      <div class="absolute left-0 top-0 h-[1px] w-full animate-glow" :style="{ background: `linear-gradient(to right, ${getColor('top-left')}, transparent)` }"></div>
    </div>

    <!-- Top Right Corner -->
    <div v-if="position === 'top-right' || position === 'all'" class="absolute right-0 top-0" :style="{ height: sizeMap[size], width: sizeMap[size] }">
      <div class="absolute right-0 top-0 h-full w-[1px] animate-glow" :style="{ background: `linear-gradient(to bottom, ${getColor('top-right')}, transparent)` }"></div>
      <div class="absolute right-0 top-0 h-[1px] w-full animate-glow" :style="{ background: `linear-gradient(to left, ${getColor('top-right')}, transparent)` }"></div>
    </div>

    <!-- Bottom Left Corner -->
    <div v-if="position === 'bottom-left' || position === 'all'" class="absolute bottom-0 left-0" :style="{ height: sizeMap[size], width: sizeMap[size] }">
      <div class="absolute bottom-0 left-0 h-full w-[1px] animate-glow" :style="{ background: `linear-gradient(to top, ${getColor('bottom-left')}, transparent)` }"></div>
      <div class="absolute bottom-0 left-0 h-[1px] w-full animate-glow" :style="{ background: `linear-gradient(to right, ${getColor('bottom-left')}, transparent)` }"></div>
    </div>

    <!-- Bottom Right Corner -->
    <div v-if="position === 'bottom-right' || position === 'all'" class="absolute bottom-0 right-0" :style="{ height: sizeMap[size], width: sizeMap[size] }">
      <div class="absolute bottom-0 right-0 h-full w-[1px] animate-glow" :style="{ background: `linear-gradient(to top, ${getColor('bottom-right')}, transparent)` }"></div>
      <div class="absolute bottom-0 right-0 h-[1px] w-full animate-glow" :style="{ background: `linear-gradient(to left, ${getColor('bottom-right')}, transparent)` }"></div>
    </div>
  </div>
</template>

<style scoped>
.neon-corners-container {
  position: relative;
  width: 100%;
  height: 100%;
  pointer-events: none;
}

@keyframes glow {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.8; }
}

.animate-glow {
  animation: glow 3s infinite;
}
</style>