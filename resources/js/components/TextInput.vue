<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    modelValue: string;
    type?: string;
}>();

const emit = defineEmits(['update:modelValue']);

const input = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit('update:modelValue', value);
    },
});
</script>

<template>
    <input
        :type="type || 'text'"
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
        :value="modelValue"
        @input="input = ($event.target as HTMLInputElement).value"
    />
</template>