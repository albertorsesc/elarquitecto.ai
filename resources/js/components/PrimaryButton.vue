<script setup lang="ts">
defineProps<{
    type?: 'submit' | 'button' | 'reset';
    disabled?: boolean;
}>();
</script>

<template>
    <button
        :type="type || 'submit'"
        :disabled="disabled"
        class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
    >
        <slot />
    </button>
</template>