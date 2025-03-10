<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import AppFooter from '@/components/theme/AppFooter.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import NeonBorders from '@/components/theme/NeonBorders.vue';
import type { BreadcrumbItemType } from '@/types';
import { provide, ref } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

// Create a reactive reference for the footer state
const isFooterExpanded = ref(false);

// Provide the footer state to child components
provide('isFooterExpanded', isFooterExpanded);
</script>

<template>
    <AppShell variant="sidebar" class="h-full">
        <!-- Sidebar with glass effect -->
        <div class="relative h-full">
            <GlassContainer
                variant="dark"
                :withBorders="true"
                :withCorners="true"
                rounded="none"
                padding="none"
                class="h-full w-full"
            >
                <AppSidebar />
            </GlassContainer>

            <!-- Animated border for sidebar -->
            <NeonBorders position="right" color="gradient" :opacity="0.2" />
        </div>

        <!-- Main content -->
        <AppContent variant="sidebar" class="h-full">
            <!-- Header with glass effect -->
            <GlassContainer
                variant="dark"
                :withBorders="true"
                :withCorners="false"
                rounded="none"
                padding="none"
                class="border-b border-white/10 w-full"
            >
                <AppSidebarHeader :breadcrumbs="breadcrumbs" />

                <!-- Animated border for header -->
                <NeonBorders position="bottom" color="gradient" :opacity="0.2" />
            </GlassContainer>

            <!-- Content area - adjust height based on footer state -->
            <div class="flex w-full flex-1 flex-col gap-4 p-4 md:p-6 lg:p-8 transition-all duration-300"
                 :class="{ 'h-[calc(100%-40px)]': isFooterExpanded, 'h-[calc(100%-48px)]': !isFooterExpanded }">
                <slot />
            </div>

            <!-- Footer -->
            <AppFooter @toggle="isFooterExpanded = !isFooterExpanded" />
        </AppContent>
    </AppShell>
</template>
