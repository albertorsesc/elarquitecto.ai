<script setup lang="ts">
import FloatingNeonLines from '@/components/theme/FloatingNeonLines.vue';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import '../../css/theme.css';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <div class="relative min-h-screen overflow-x-hidden">
        <!-- Background Effects -->
        <FloatingNeonLines variant="dense" :opacity="0.15" />

        <!-- Main Layout -->
        <AppLayout :breadcrumbs="breadcrumbs">
            <slot />
        </AppLayout>
    </div>
</template>

<style scoped>
.side-panel {
    position: sticky;
    top: 0;
    height: 100vh;
    overflow-y: auto;
    background-color: rgba(18, 18, 18, 0.8);
    backdrop-filter: blur(8px);
    border-radius: 0.5rem;
    margin: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

@media (max-width: 1280px) {
    .side-panel {
        display: none;
    }
}
</style>
