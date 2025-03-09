<script setup lang="ts">
// FormButton.vue - Styled form button component

interface Props {
  type?: 'button' | 'submit' | 'reset';
  variant?: 'primary' | 'secondary' | 'outline' | 'text';
  size?: 'sm' | 'md' | 'lg';
  disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  type: 'button',
  variant: 'primary',
  size: 'md',
  disabled: false,
});

// Variant classes
const variantClasses = {
  primary: 'bg-indigo-600 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600',
  secondary: 'bg-secondary text-white shadow-sm hover:bg-secondary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-secondary',
  outline: 'bg-transparent border border-white/20 text-foreground hover:bg-white/5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white/20',
  text: 'bg-transparent text-gray-900 hover:text-indigo-600 focus-visible:outline-none',
};

// Size classes
const sizeClasses = {
  sm: 'px-2 py-1 text-xs',
  md: 'px-3 py-2 text-sm',
  lg: 'px-4 py-2.5 text-base',
};
</script>

<template>
  <button
    :type="type"
    :disabled="disabled"
    class="relative rounded-md font-semibold transition-all duration-200"
    :class="[
      variantClasses[variant],
      sizeClasses[size],
      disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
    ]"
  >
    <slot></slot>

    <!-- Neon glow effect for primary and secondary variants -->
    <div
      v-if="['primary', 'secondary'].includes(variant) && !disabled"
      class="absolute inset-0 rounded-md opacity-0 transition-opacity duration-300 hover:opacity-100"
      :class="variant === 'primary' ? 'shadow-[0_0_15px_rgba(79,70,229,0.5)]' : 'shadow-[0_0_15px_rgba(236,72,153,0.5)]'"
    ></div>
  </button>
</template>