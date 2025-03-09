<script setup lang="ts">
import GlassContainer from '@/components/theme/GlassContainer.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <GlassContainer
                    variant="dark"
                    :withBorders="true"
                    :withCorners="true"
                    rounded="xl"
                    padding="none"
                    class="relative aspect-video overflow-hidden"
                >
                    <PlaceholderPattern />
                </GlassContainer>

                <GlassContainer
                    variant="dark"
                    :withBorders="true"
                    :withCorners="true"
                    rounded="xl"
                    padding="none"
                    class="relative aspect-video overflow-hidden"
                >
                    <PlaceholderPattern />
                </GlassContainer>

                <GlassContainer
                    variant="dark"
                    :withBorders="true"
                    :withCorners="true"
                    rounded="xl"
                    padding="none"
                    class="relative aspect-video overflow-hidden"
                >
                    <PlaceholderPattern />
                </GlassContainer>
            </div>

            <GlassContainer
                variant="dark"
                :withBorders="true"
                :withCorners="true"
                rounded="xl"
                padding="none"
                class="relative min-h-[100vh] flex-1 md:min-h-min"
            >
                <PlaceholderPattern />
            </GlassContainer>
        </div>
    </AppLayout>
</template>
