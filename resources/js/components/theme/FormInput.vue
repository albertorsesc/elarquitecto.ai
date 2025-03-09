<script setup lang="ts">
// FormInput.vue - Styled form input component with dark mode support

interface Props {
  modelValue: string;
  id?: string;
  type?: string;
  placeholder?: string;
  label?: string;
  error?: string;
  disabled?: boolean;
}

defineProps<Props>();

const emit = defineEmits(['update:modelValue']);

const updateValue = (event: Event) => {
  const target = event.target as HTMLInputElement;
  emit('update:modelValue', target.value);
};

const handleFocus = (event: FocusEvent) => {
  const target = event.target as HTMLInputElement;
  const parent = target.parentElement;
  if (parent) {
    parent.classList.add('outline-primary', 'outline-2', '-outline-offset-2');
  }
};

const handleBlur = (event: FocusEvent) => {
  const target = event.target as HTMLInputElement;
  const parent = target.parentElement;
  if (parent) {
    parent.classList.remove('outline-primary', 'outline-2', '-outline-offset-2');
  }
};
</script>

<template>
  <div class="w-full">
    <label v-if="label" :for="id" class="block text-sm/6 font-medium text-foreground">
      {{ label }}
    </label>
    <div class="relative mt-2">
      <div
        class="flex items-center rounded-md bg-background/80 pl-3 outline outline-1 -outline-offset-1 outline-white/20 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-primary"
        :class="{ 'opacity-70': disabled }"
      >
        <input
          :id="id"
          :type="type"
          :value="modelValue"
          :placeholder="placeholder"
          :disabled="disabled"
          class="block min-w-0 grow bg-transparent py-1.5 pl-1 pr-3 text-base text-foreground placeholder:text-foreground/40 focus:outline focus:outline-0 sm:text-sm/6"
          @input="updateValue"
          @focus="handleFocus"
          @blur="handleBlur"
        >
      </div>

      <!-- Animated border effect on focus -->
      <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>

      <!-- Glow effect on focus -->
      <div class="pointer-events-none absolute inset-0 rounded-md opacity-0 transition-opacity duration-300 focus-within:opacity-100 focus-within:shadow-[0_0_8px_rgba(124,58,237,0.4)]"></div>

      <!-- Error message -->
      <div v-if="error" class="mt-1 text-sm text-red-400">{{ error }}</div>
    </div>
  </div>
</template>
<style scoped>
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus {
  -webkit-text-fill-color: var(--color-foreground);
  -webkit-box-shadow: 0 0 0px 1000px rgba(17, 24, 39, 0.8) inset;
  transition: background-color 5000s ease-in-out 0s;
}
</style>
