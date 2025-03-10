<script setup lang="ts">
defineProps<{
    type?: 'submit' | 'button' | 'reset';
    disabled?: boolean;
    href?: string;
    as?: string;
}>();
</script>

<template>
    <component
        :is="as || (href ? 'a' : 'button')"
        :type="!href && !as ? type || 'button' : undefined"
        :href="href"
        :disabled="disabled"
        class="inline-flex items-center rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
    >
        <slot />
    </component>
</template>