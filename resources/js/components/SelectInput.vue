<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    modelValue: string | number;
}>();

const emit = defineEmits(['update:modelValue']);

const select = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit('update:modelValue', value);
    },
});
</script>

<template>
    <select
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
        :value="modelValue"
        @change="select = ($event.target as HTMLSelectElement).value"
    >
        <slot></slot>
    </select>
</template>