<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    modelValue: string;
    rows?: number;
    placeholder?: string;
}>();

const emit = defineEmits(['update:modelValue']);

const textarea = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit('update:modelValue', value);
    },
});
</script>

<template>
    <textarea
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
        :value="modelValue"
        @input="textarea = ($event.target as HTMLTextAreaElement).value"
        :rows="rows || 4"
        :placeholder="placeholder"
    ></textarea>
</template>