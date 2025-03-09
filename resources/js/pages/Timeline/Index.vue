<script setup lang="ts">
import Button from '@/components/theme/Button.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import NeonBorders from '@/components/theme/NeonBorders.vue';
import NeonCorners from '@/components/theme/NeonCorners.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

interface TimelineItem {
    id: number;
    title: string;
    excerpt: string;
    description: string;
    // Add other properties as needed
}

defineProps<{
    timelines: TimelineItem[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Timeline',
        href: '/timeline',
    },
];
</script>

<template>
    <Head title="Timeline" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4">
            <div class="flex justify-end">
                <Button to="/timeline/create" text="Create new Timeline Post" variant="primary" size="md" />
            </div>
            <ul role="list" class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-3 xl:gap-x-8">
                <li v-for="timeline in timelines" :key="timeline.id" class="relative">
                    <GlassContainer
                        variant="dark"
                        withBorders
                        withCorners
                        rounded="xl"
                        padding="md"
                        class="relative"
                    >
                        <NeonCorners position="all" color="primary" size="md" class="absolute -inset-1" />
                        <NeonBorders position="all" color="primary" :opacity="0.3" class="absolute -inset-1" />
                        <div class="p-4">
                            <h3 class="mb-2 text-lg font-semibold text-white">{{ timeline.title }}</h3>
                            <p class="text-sm text-gray-300">{{ timeline.excerpt }}</p>
                            <p class="mt-2 text-sm text-gray-300">{{ timeline.description }}</p>
                        </div>
                    </GlassContainer>
                </li>
            </ul>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
