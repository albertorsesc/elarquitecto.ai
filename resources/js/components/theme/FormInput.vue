<script setup lang="ts">
// FormInput.vue - Styled form input component

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
    parent.classList.add('outline-indigo-600', 'outline-2', '-outline-offset-2');
  }
};

const handleBlur = (event: FocusEvent) => {
  const target = event.target as HTMLInputElement;
  const parent = target.parentElement;
  if (parent) {
    parent.classList.remove('outline-indigo-600', 'outline-2', '-outline-offset-2');
  }
};
</script>

<template>
  <div class="w-full">
    <label v-if="label" :for="id" class="block text-sm/6 font-medium text-gray-900">
      {{ label }}
    </label>
    <div class="relative mt-2">
      <div
        class="flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600"
        :class="{ 'opacity-70': disabled }"
      >
        <input
          :id="id"
          :type="type"
          :value="modelValue"
          :placeholder="placeholder"
          :disabled="disabled"
          class="block min-w-0 grow py-1.5 pl-1 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6"
          @input="updateValue"
          @focus="handleFocus"
          @blur="handleBlur"
        >
      </div>

      <!-- Animated border effect on focus -->
      <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>

      <!-- Error message -->
      <div v-if="error" class="mt-1 text-sm text-red-400">{{ error }}</div>
    </div>
  </div>
</template>