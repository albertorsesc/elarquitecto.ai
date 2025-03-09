<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<style scoped>
.logo {
    width: 120%;
    height: 120%;
    object-fit: contain;
    border-radius: 50%;
}
</style>

<template>
    <img src="/logo.png" :class="['className', 'logo']" v-bind="$attrs" alt="App Logo" />
</template>
