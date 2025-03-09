<script setup lang="ts">
// NeonBorders.vue - Reusable neon borders for the application
// This component provides animated neon borders for containers

interface Props {
  position?: 'top' | 'right' | 'bottom' | 'left' | 'all';
  color?: 'primary' | 'secondary' | 'accent' | 'cyan' | 'gradient';
  animation?: 'slide-right' | 'slide-left' | 'slide-up' | 'slide-down' | 'none';
  opacity?: number;
}

const props = withDefaults(defineProps<Props>(), {
  position: 'all',
  color: 'gradient',
  animation: 'slide-right',
  opacity: 0.5
});

// Map colors to their CSS variables
const colorMap = {
  primary: 'var(--primary)',
  secondary: 'var(--secondary)',
  accent: 'var(--accent)',
  cyan: 'var(--cyan-400)',
  gradient: 'linear-gradient(90deg, var(--primary), var(--cyan-400), var(--secondary))'
};

// Map animations to their CSS classes
const animationMap = {
  'slide-right': 'animate-neon-slide-right-slow',
  'slide-left': 'animate-neon-slide-left-slow',
  'slide-up': 'animate-neon-slide-up-slow',
  'slide-down': 'animate-neon-slide-down-slow',
  'none': ''
};

// Get background style based on color prop
const getBackground = () => {
  if (props.color === 'gradient') {
    return colorMap.gradient;
  }
  return colorMap[props.color];
};
</script>

<template>
  <div class="neon-borders-container">
    <!-- Top Border -->
    <div
      v-if="position === 'top' || position === 'all'"
      class="absolute inset-x-0 top-0 h-[2px]"
    >
      <div
        class="absolute inset-0"
        :class="animation !== 'none' ? animationMap[animation] : ''"
        :style="{
          background: getBackground(),
          opacity: opacity
        }"
      ></div>
    </div>

    <!-- Right Border -->
    <div
      v-if="position === 'right' || position === 'all'"
      class="absolute right-0 top-0 h-full w-[2px]"
    >
      <div
        class="absolute inset-0"
        :class="animation !== 'none' ? (animation === 'slide-right' || animation === 'slide-left' ? 'animate-neon-slide-down-slow' : animationMap[animation]) : ''"
        :style="{
          background: color === 'gradient' ? 'linear-gradient(to bottom, var(--primary), var(--cyan-400), var(--secondary))' : getBackground(),
          opacity: opacity
        }"
      ></div>
    </div>

    <!-- Bottom Border -->
    <div
      v-if="position === 'bottom' || position === 'all'"
      class="absolute bottom-0 inset-x-0 h-[2px]"
    >
      <div
        class="absolute inset-0"
        :class="animation !== 'none' ? (animation === 'slide-right' ? 'animate-neon-slide-left-slow' : (animation === 'slide-left' ? 'animate-neon-slide-right-slow' : animationMap[animation])) : ''"
        :style="{
          background: getBackground(),
          opacity: opacity
        }"
      ></div>
    </div>

    <!-- Left Border -->
    <div
      v-if="position === 'left' || position === 'all'"
      class="absolute left-0 top-0 h-full w-[2px]"
    >
      <div
        class="absolute inset-0"
        :class="animation !== 'none' ? (animation === 'slide-right' || animation === 'slide-left' ? 'animate-neon-slide-up-slow' : animationMap[animation]) : ''"
        :style="{
          background: color === 'gradient' ? 'linear-gradient(to bottom, var(--secondary), var(--cyan-400), var(--primary))' : getBackground(),
          opacity: opacity
        }"
      ></div>
    </div>
  </div>
</template>

<style scoped>
.neon-borders-container {
  position: relative;
  width: 100%;
  height: 100%;
  pointer-events: none;
}

/* Import animations from NeonEffects.vue */
@keyframes neonSlideRight {
  from { transform: translateX(-100%); }
  to { transform: translateX(100%); }
}

@keyframes neonSlideLeft {
  from { transform: translateX(100%); }
  to { transform: translateX(-100%); }
}

@keyframes neonSlideDown {
  from { transform: translateY(-100%); }
  to { transform: translateY(100%); }
}

@keyframes neonSlideUp {
  from { transform: translateY(100%); }
  to { transform: translateY(-100%); }
}

.animate-neon-slide-right-slow {
  animation: neonSlideRight 8s linear infinite;
}

.animate-neon-slide-left-slow {
  animation: neonSlideLeft 8s linear infinite;
}

.animate-neon-slide-down-slow {
  animation: neonSlideDown 8s linear infinite;
}

.animate-neon-slide-up-slow {
  animation: neonSlideUp 8s linear infinite;
}
</style>