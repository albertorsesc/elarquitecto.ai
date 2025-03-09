<script setup lang="ts">
// FormTextarea.vue - Styled form textarea component with dark mode support

interface Props {
  modelValue: string;
  id?: string;
  rows?: number;
  placeholder?: string;
  label?: string;
  error?: string;
  disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  id: '',
  rows: 3,
  placeholder: '',
  label: '',
  error: '',
  disabled: false,
});

const emit = defineEmits(['update:modelValue']);

const updateValue = (event: Event) => {
  const target = event.target as HTMLTextAreaElement;
  emit('update:modelValue', target.value);
};
</script>

<template>
  <div class="w-full">
    <label v-if="label" :for="id" class="block text-sm/6 font-medium text-foreground">
      {{ label }}
    </label>
    <div class="relative mt-2 group">
      <textarea
        :id="id"
        :rows="rows"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        class="block w-full rounded-md bg-background/80 px-3 py-1.5 text-base text-foreground outline outline-1 -outline-offset-1 outline-white/20 placeholder:text-foreground/40 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-primary sm:text-sm/6 backdrop-blur-sm"
        :class="{ 'opacity-70': disabled }"
        @input="updateValue"
      ></textarea>

      <!-- Animated border effect on focus -->
      <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>

      <!-- Glow effect on focus -->
      <div class="pointer-events-none absolute inset-0 rounded-md opacity-0 transition-opacity duration-300 group-focus-within:opacity-100 group-focus-within:shadow-[0_0_8px_rgba(124,58,237,0.4)]"></div>

      <!-- Error message -->
      <div v-if="error" class="mt-1 text-sm text-red-400">{{ error }}</div>
    </div>
  </div>
</template>
<style scoped>
textarea {
  resize: vertical;
  min-height: 80px;
  background-color: rgba(17, 24, 39, 0.8);
  transition: all 0.2s ease;
}

textarea:focus {
  background-color: rgba(17, 24, 39, 0.9);
}

/* Scrollbar styling */
textarea::-webkit-scrollbar {
  width: 8px;
}

textarea::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 4px;
}

textarea::-webkit-scrollbar-thumb {
  background: rgba(124, 58, 237, 0.5);
  border-radius: 4px;
}

textarea::-webkit-scrollbar-thumb:hover {
  background: rgba(124, 58, 237, 0.7);
}
</style>
