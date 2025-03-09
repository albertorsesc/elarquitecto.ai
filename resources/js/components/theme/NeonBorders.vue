<script setup lang="ts">
// NeonBorders.vue - Implements animated neon borders

interface Props {
  position?: 'top' | 'right' | 'bottom' | 'left' | 'all';
  color?: 'primary' | 'secondary' | 'accent' | 'cyan' | 'gradient';
  animation?: 'slide-right' | 'slide-left' | 'slide-up' | 'slide-down' | 'none';
  opacity?: number;
  thickness?: number;
}

const props = withDefaults(defineProps<Props>(), {
  position: 'all',
  color: 'primary',
  animation: 'none',
  opacity: 0.5,
  thickness: 1,
});

// Color mapping
const colorMap = {
  primary: 'bg-primary',
  secondary: 'bg-secondary',
  accent: 'bg-accent',
  cyan: 'bg-cyan-400',
  gradient: '',
};

// Animation mapping
const animationMap = {
  'slide-right': 'animate-neon-slide-right',
  'slide-left': 'animate-neon-slide-left',
  'slide-up': 'animate-neon-slide-up',
  'slide-down': 'animate-neon-slide-down',
  'none': '',
};

// Determine which borders to show
const showTop = props.position === 'top' || props.position === 'all';
const showRight = props.position === 'right' || props.position === 'all';
const showBottom = props.position === 'bottom' || props.position === 'all';
const showLeft = props.position === 'left' || props.position === 'all';

// Gradient classes
const gradientClasses = {
  top: 'bg-gradient-to-r from-transparent via-primary to-transparent',
  right: 'bg-gradient-to-b from-transparent via-secondary to-transparent',
  bottom: 'bg-gradient-to-r from-transparent via-accent to-transparent',
  left: 'bg-gradient-to-b from-transparent via-cyan-400 to-transparent',
};

// Get color class based on position and color
const getColorClass = (position: 'top' | 'right' | 'bottom' | 'left') => {
  if (props.color === 'gradient') {
    return gradientClasses[position];
  }
  return colorMap[props.color];
};
</script>

<template>
  <div class="pointer-events-none absolute inset-0">
    <!-- Top border -->
    <div
      v-if="showTop"
      class="absolute left-0 top-0 w-full"
      :class="[animation !== 'none' ? animationMap[animation] : '']"
      :style="{
        height: `${thickness}px`,
        opacity: opacity,
      }"
    >
      <div
        class="absolute inset-0"
        :class="getColorClass('top')"
      ></div>
    </div>

    <!-- Right border -->
    <div
      v-if="showRight"
      class="absolute right-0 top-0 h-full"
      :class="[animation !== 'none' ? animationMap[animation] : '']"
      :style="{
        width: `${thickness}px`,
        opacity: opacity,
      }"
    >
      <div
        class="absolute inset-0"
        :class="getColorClass('right')"
      ></div>
    </div>

    <!-- Bottom border -->
    <div
      v-if="showBottom"
      class="absolute bottom-0 left-0 w-full"
      :class="[animation !== 'none' ? animationMap[animation] : '']"
      :style="{
        height: `${thickness}px`,
        opacity: opacity,
      }"
    >
      <div
        class="absolute inset-0"
        :class="getColorClass('bottom')"
      ></div>
    </div>

    <!-- Left border -->
    <div
      v-if="showLeft"
      class="absolute left-0 top-0 h-full"
      :class="[animation !== 'none' ? animationMap[animation] : '']"
      :style="{
        width: `${thickness}px`,
        opacity: opacity,
      }"
    >
      <div
        class="absolute inset-0"
        :class="getColorClass('left')"
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