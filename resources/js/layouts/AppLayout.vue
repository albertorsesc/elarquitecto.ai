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
    <div class="relative min-h-screen">
        <!-- Background Effects -->
        <FloatingNeonLines variant="dense" :opacity="0.15" />

        <!-- Main Layout -->
        <AppLayout :breadcrumbs="breadcrumbs">
            <slot />
        </AppLayout>
    </div>
</template>
