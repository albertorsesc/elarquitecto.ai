<script setup lang="ts">
defineProps<{
    message?: string;
}>();
</script>

<template>
    <div v-if="message" class="text-sm text-red-600 dark:text-red-400">
        {{ message }}
    </div>
</template>
