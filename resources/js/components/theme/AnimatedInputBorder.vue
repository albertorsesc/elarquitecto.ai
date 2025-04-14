<script setup lang="ts">
defineProps<{
  id: string;
  modelValue: string;
  type?: string;
  placeholder?: string;
  required?: boolean;
  autofocus?: boolean;
  autocomplete?: string;
  tabindex?: number | string;
}>();

defineEmits(['update:modelValue']);
</script>

<template>
  <div class="group relative">
    <input
      :id="id"
      :type="type || 'text'"
      :required="required"
      :autofocus="autofocus"
      :autocomplete="autocomplete"
      :tabindex="tabindex"
      :value="modelValue"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      :placeholder="placeholder"
      class="peer relative z-10 w-full rounded-xl border border-white/10 bg-background/50 py-2 pl-3 pr-4 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
    />
    <!-- Animated border effect -->
    <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>
  </div>
</template> 