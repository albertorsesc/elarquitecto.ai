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
            <!-- Card Grid -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <!-- Card 1 -->
                <GlassContainer
                    variant="dark"
                    :withBorders="true"
                    :withCorners="true"
                    rounded="xl"
                    padding="none"
                    class="relative aspect-video overflow-hidden"
                >
                    <!-- Animated corner accents -->
                    <div class="absolute left-0 top-0 h-12 w-12 animate-pulse-slow">
                        <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
                        <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
                    </div>
                    <div class="absolute right-0 top-0 h-12 w-12 animate-pulse-slow">
                        <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
                        <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
                    </div>

                    <PlaceholderPattern />
                </GlassContainer>

                <!-- Card 2 -->
                <GlassContainer
                    variant="dark"
                    :withBorders="true"
                    :withCorners="true"
                    rounded="xl"
                    padding="none"
                    class="relative aspect-video overflow-hidden"
                >
                    <!-- Animated corner accents -->
                    <div class="absolute left-0 top-0 h-12 w-12 animate-pulse-slow">
                        <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-secondary via-transparent to-transparent"></div>
                        <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-secondary via-transparent to-transparent"></div>
                    </div>
                    <div class="absolute right-0 top-0 h-12 w-12 animate-pulse-slow">
                        <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-accent via-transparent to-transparent"></div>
                        <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-accent via-transparent to-transparent"></div>
                    </div>

                    <PlaceholderPattern />
                </GlassContainer>

                <!-- Card 3 -->
                <GlassContainer
                    variant="dark"
                    :withBorders="true"
                    :withCorners="true"
                    rounded="xl"
                    padding="none"
                    class="relative aspect-video overflow-hidden"
                >
                    <!-- Animated corner accents -->
                    <div class="absolute left-0 top-0 h-12 w-12 animate-pulse-slow">
                        <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
                        <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-cyan-400 via-transparent to-transparent"></div>
                    </div>
                    <div class="absolute right-0 top-0 h-12 w-12 animate-pulse-slow">
                        <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
                        <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-primary via-transparent to-transparent"></div>
                    </div>

                    <PlaceholderPattern />
                </GlassContainer>
            </div>

            <!-- Main Content Area -->
            <GlassContainer
                variant="dark"
                :withBorders="true"
                :withCorners="true"
                rounded="xl"
                padding="none"
                class="relative min-h-[60vh] flex-1 md:min-h-min"
            >
                <!-- Animated corner accents -->
                <div class="absolute left-0 top-0 h-12 w-12 animate-pulse-slow">
                    <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
                    <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
                </div>
                <div class="absolute right-0 top-0 h-12 w-12 animate-pulse-slow">
                    <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
                    <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
                </div>
                <div class="absolute bottom-0 left-0 h-12 w-12 animate-pulse-slow">
                    <div class="absolute bottom-0 left-0 h-full w-[1px] animate-glow bg-gradient-to-t from-secondary via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 h-[1px] w-full animate-glow bg-gradient-to-r from-secondary via-transparent to-transparent"></div>
                </div>
                <div class="absolute bottom-0 right-0 h-12 w-12 animate-pulse-slow">
                    <div class="absolute bottom-0 right-0 h-full w-[1px] animate-glow bg-gradient-to-t from-accent via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 right-0 h-[1px] w-full animate-glow bg-gradient-to-l from-accent via-transparent to-transparent"></div>
                </div>

                <!-- Sliding neon lines inside the container -->
                <div class="pointer-events-none absolute -inset-1">
                    <!-- Top edge -->
                    <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right-slow bg-gradient-to-r from-transparent via-primary to-transparent opacity-30"></div>
                    <!-- Right edge -->
                    <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down-slow bg-gradient-to-b from-transparent via-secondary to-transparent opacity-30"></div>
                    <!-- Bottom edge -->
                    <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left-slow bg-gradient-to-r from-transparent via-accent to-transparent opacity-30"></div>
                    <!-- Left edge -->
                    <div class="absolute left-0 top-0 h-full w-[2px] animate-neon-slide-up-slow bg-gradient-to-b from-transparent via-cyan-400 to-transparent opacity-30"></div>
                </div>

                <PlaceholderPattern />
            </GlassContainer>
        </div>
    </AppLayout>
</template>
