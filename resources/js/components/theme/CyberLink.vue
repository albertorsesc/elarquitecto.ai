<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed, type PropType } from 'vue';

const props = defineProps({
  href: {
    type: String,
    required: true,
  },
  variant: {
    type: String as PropType<'primary' | 'secondary' | 'accent' | 'outline'>,
    default: 'primary',
  },
  size: {
    type: String as PropType<'sm' | 'md' | 'lg'>,
    default: 'md',
  },
  animated: {
    type: Boolean,
    default: true,
  },
  glow: {
    type: Boolean,
    default: true,
  },
  fullWidth: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  }
});

const classes = computed(() => {
  const baseClasses = 'inline-flex items-center justify-center rounded-lg font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';
  
  // Size classes
  const sizeClasses = {
    sm: 'px-3 py-1 text-xs',
    md: 'px-4 py-2 text-sm',
    lg: 'px-6 py-3 text-base'
  }[props.size];
  
  // Variant classes
  const variantClasses = {
    primary: 'border border-primary/30 bg-primary/10 text-primary-foreground hover:bg-primary/90 focus:ring-primary/50',
    secondary: 'border border-secondary/30 bg-secondary/10 text-secondary-foreground hover:bg-secondary/90 focus:ring-secondary/50',
    accent: 'border border-accent/30 bg-accent/10 text-accent-foreground hover:bg-accent/90 focus:ring-accent/50',
    outline: 'border border-border bg-transparent text-foreground hover:bg-muted hover:text-foreground focus:ring-muted'
  }[props.variant];
  
  // Effect classes
  const effectClasses = [];
  if (props.animated) effectClasses.push('neon-border');
  if (props.glow) effectClasses.push('group');
  if (props.fullWidth) effectClasses.push('w-full');
  
  return [baseClasses, sizeClasses, variantClasses, ...effectClasses].join(' ');
});
</script>

<template>
  <Link :href="href" :class="classes" :disabled="disabled">
    <div class="relative overflow-hidden">
      <slot />
      <!-- Glowing accents that appear on hover -->
      <div v-if="glow" class="absolute -inset-px opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/50 via-cyan-400/50 to-secondary/50 opacity-0 group-hover:opacity-20 backdrop-blur-sm rounded-md"></div>
        <div class="absolute inset-0 flex items-center justify-center">
          <div class="h-[1px] w-[70%] bg-gradient-to-r from-primary via-cyan-400 to-secondary transform translate-y-[8px] blur-[2px] animate-neon-slide-right-slow"></div>
        </div>
      </div>
    </div>
  </Link>
</template>

<style scoped>
.neon-border {
  position: relative;
  overflow: hidden;
  box-shadow: 
    0 0 5px rgba(var(--primary-rgb), 0.3),
    0 0 10px rgba(var(--primary-rgb), 0.1);
  transition: all 0.3s ease;
}

.neon-border:hover {
  box-shadow: 
    0 0 10px rgba(var(--primary-rgb), 0.7),
    0 0 20px rgba(var(--primary-rgb), 0.3);
}

.neon-border::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border: 1px solid transparent;
  border-radius: inherit;
  background: linear-gradient(90deg, 
    hsl(var(--primary)), 
    hsl(var(--cyan)), 
    hsl(var(--secondary)));
  background-size: 200% 100%;
  animation: neonSlideRight 3s linear infinite;
  -webkit-mask:
    linear-gradient(#fff 0 0) content-box,
    linear-gradient(#fff 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;
  pointer-events: none;
}

/* Custom animation for text glow on hover */
@keyframes textPulse {
  0%, 100% { text-shadow: 0 0 4px rgba(var(--primary-rgb), 0.3); }
  50% { text-shadow: 0 0 8px rgba(var(--primary-rgb), 0.6); }
}

.neon-border:hover > * {
  animation: textPulse 2s infinite;
}
</style> 