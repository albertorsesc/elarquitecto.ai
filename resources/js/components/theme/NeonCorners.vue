<script setup lang="ts">
// NeonCorners.vue - Provides corner accent effects

interface Props {
  position?: 'top-left' | 'top-right' | 'bottom-left' | 'bottom-right' | 'all';
  color?: 'primary' | 'secondary' | 'accent' | 'cyan';
  size?: 'sm' | 'md' | 'lg';
  animated?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  position: 'all',
  color: 'primary',
  size: 'md',
  animated: true,
});

// Size mapping
const sizeMap = {
  sm: '8px',
  md: '12px',
  lg: '16px',
};

// Color mapping
const colorMap = {
  primary: 'from-primary',
  secondary: 'from-secondary',
  accent: 'from-accent',
  cyan: 'from-cyan-400',
};

// Determine which corners to show
const showTopLeft = props.position === 'top-left' || props.position === 'all';
const showTopRight = props.position === 'top-right' || props.position === 'all';
const showBottomLeft = props.position === 'bottom-left' || props.position === 'all';
const showBottomRight = props.position === 'bottom-right' || props.position === 'all';

// Animation class
const animationClass = props.animated ? 'animate-glow' : '';
</script>

<template>
  <div class="pointer-events-none absolute inset-0">
    <!-- Top Left Corner -->
    <div v-if="showTopLeft" class="absolute left-0 top-0" :style="{ height: sizeMap[size], width: sizeMap[size] }">
      <div
        class="absolute left-0 top-0 h-full w-[1px] bg-gradient-to-b via-transparent to-transparent"
        :class="[colorMap[color], animationClass]"
      ></div>
      <div
        class="absolute left-0 top-0 h-[1px] w-full bg-gradient-to-r via-transparent to-transparent"
        :class="[colorMap[color], animationClass]"
      ></div>
    </div>

    <!-- Top Right Corner -->
    <div v-if="showTopRight" class="absolute right-0 top-0" :style="{ height: sizeMap[size], width: sizeMap[size] }">
      <div
        class="absolute right-0 top-0 h-full w-[1px] bg-gradient-to-b via-transparent to-transparent"
        :class="[colorMap[color], animationClass]"
      ></div>
      <div
        class="absolute right-0 top-0 h-[1px] w-full bg-gradient-to-l via-transparent to-transparent"
        :class="[colorMap[color], animationClass]"
      ></div>
    </div>

    <!-- Bottom Left Corner -->
    <div v-if="showBottomLeft" class="absolute bottom-0 left-0" :style="{ height: sizeMap[size], width: sizeMap[size] }">
      <div
        class="absolute bottom-0 left-0 h-full w-[1px] bg-gradient-to-t via-transparent to-transparent"
        :class="[colorMap[color], animationClass]"
      ></div>
      <div
        class="absolute bottom-0 left-0 h-[1px] w-full bg-gradient-to-r via-transparent to-transparent"
        :class="[colorMap[color], animationClass]"
      ></div>
    </div>

    <!-- Bottom Right Corner -->
    <div v-if="showBottomRight" class="absolute bottom-0 right-0" :style="{ height: sizeMap[size], width: sizeMap[size] }">
      <div
        class="absolute bottom-0 right-0 h-full w-[1px] bg-gradient-to-t via-transparent to-transparent"
        :class="[colorMap[color], animationClass]"
      ></div>
      <div
        class="absolute bottom-0 right-0 h-[1px] w-full bg-gradient-to-l via-transparent to-transparent"
        :class="[colorMap[color], animationClass]"
      ></div>
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