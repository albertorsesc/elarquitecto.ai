<script setup lang="ts">
// FormTextarea.vue - Styled form textarea component

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
    <label v-if="label" :for="id" class="block text-sm/6 font-medium text-gray-900">
      {{ label }}
    </label>
    <div class="relative mt-2">
      <textarea
        :id="id"
        :rows="rows"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
        :class="{ 'opacity-70': disabled }"
        @input="updateValue"
      ></textarea>

      <!-- Animated border effect on focus -->
      <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>

      <!-- Error message -->
      <div v-if="error" class="mt-1 text-sm text-red-400">{{ error }}</div>
    </div>
  </div>
</template>