<script setup lang="ts">
// FloatingNeonLines.vue - Creates background decoration with floating neon lines

interface Props {
  variant?: 'default' | 'dense' | 'sparse';
  opacity?: number;
  responsive?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default',
  opacity: 0.2,
  responsive: true,
});

// Determine class based on responsive prop
const responsiveClass = props.responsive ? 'hidden sm:block' : '';
</script>

<template>
  <div :class="[responsiveClass, 'pointer-events-none fixed inset-0']">
    <!-- Default variant -->
    <template v-if="variant === 'default'">
      <!-- Horizontal lines -->
      <div class="absolute left-0 top-1/3 h-[1px] w-1/4 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent" :style="{ opacity }"></div>
      <div class="absolute right-0 top-2/3 h-[1px] w-1/4 animate-neon-slide-left bg-gradient-to-r from-transparent via-primary to-transparent" :style="{ opacity }"></div>

      <!-- Vertical lines -->
      <div class="absolute left-1/3 top-0 h-1/4 w-[1px] animate-neon-slide-down bg-gradient-to-b from-transparent via-secondary to-transparent" :style="{ opacity }"></div>
      <div class="absolute right-2/3 top-0 h-1/4 w-[1px] animate-neon-slide-down-delayed bg-gradient-to-b from-transparent via-accent to-transparent" :style="{ opacity }"></div>
    </template>

    <!-- Dense variant -->
    <template v-else-if="variant === 'dense'">
      <!-- Horizontal lines -->
      <div class="absolute left-0 top-1/4 h-[1px] w-1/3 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent" :style="{ opacity }"></div>
      <div class="absolute right-0 top-1/2 h-[1px] w-1/3 animate-neon-slide-left bg-gradient-to-r from-transparent via-primary to-transparent" :style="{ opacity }"></div>
      <div class="absolute left-0 top-3/4 h-[1px] w-1/3 animate-neon-slide-right bg-gradient-to-r from-transparent via-secondary to-transparent" :style="{ opacity }"></div>

      <!-- Vertical lines -->
      <div class="absolute left-1/4 top-0 h-1/3 w-[1px] animate-neon-slide-down bg-gradient-to-b from-transparent via-accent to-transparent" :style="{ opacity }"></div>
      <div class="absolute left-1/2 top-0 h-1/3 w-[1px] animate-neon-slide-down bg-gradient-to-b from-transparent via-cyan-400 to-transparent" :style="{ opacity }"></div>
      <div class="absolute left-3/4 top-0 h-1/3 w-[1px] animate-neon-slide-down bg-gradient-to-b from-transparent via-primary to-transparent" :style="{ opacity }"></div>
    </template>

    <!-- Sparse variant -->
    <template v-else-if="variant === 'sparse'">
      <!-- Horizontal lines -->
      <div class="absolute left-0 top-1/2 h-[1px] w-1/5 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent" :style="{ opacity }"></div>

      <!-- Vertical lines -->
      <div class="absolute left-1/2 top-0 h-1/5 w-[1px] animate-neon-slide-down bg-gradient-to-b from-transparent via-secondary to-transparent" :style="{ opacity }"></div>
    </template>
  </div>
</template>

<style scoped>
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

.animate-neon-slide-right {
  animation: neonSlideRight 3s linear infinite;
}

.animate-neon-slide-left {
  animation: neonSlideLeft 3s linear infinite;
}

.animate-neon-slide-down {
  animation: neonSlideDown 3s linear infinite;
}

.animate-neon-slide-down-delayed {
  animation: neonSlideDown 3s linear infinite;
  animation-delay: 1.5s;
}
</style>