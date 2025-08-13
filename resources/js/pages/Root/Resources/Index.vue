<template>
    <Head title="Resources" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-background">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-glow animate-text-glow">Resources</h1>
                <CyberLink :href="route('root.blog.resources.create')" variant="primary" size="md">
                    Create New Resource
                </CyberLink>
            </div>

            <!-- Grid of Resource Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Resource Card (repeated for each resource) -->
                <ResourceCard 
                    v-for="resource in resources" 
                    :key="resource.id" 
                    :resource="resource" 
                />

                <!-- Empty State (shown when no resources) -->
                <div v-if="resources.length === 0" class="col-span-full flex flex-col items-center justify-center gap-4 p-8 text-center">
                    <div class="rounded-full p-3 bg-muted">
                        <div class="h-10 w-10 text-muted-foreground"></div>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-semibold">No resources found</h3>
                        <p class="text-muted-foreground">Get started by creating your first resource.</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import CyberLink from '@/components/theme/CyberLink.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Blog',
        href: '#',
    },
    {
        title: 'Resources',
        href: '#',
    },
];

interface Resource {
    id: number;
    name: string;
    description?: string;
    [key: string]: any;
}

const props = defineProps<{
    resources: Resource[];
}>();

const { resources } = props;
</script>

<style>
/* Ensure the glass effect maintains transparency in dark mode */
.glass-effect {
  background-color: hsl(var(--card)) !important;
}

/* Force dark mode for this component if needed */
html:not(.dark) .force-dark {
  --background: 226 30% 12%;
  --foreground: 213 10% 95%;
  --card: 226 30% 15% / 0.7;
  --card-foreground: 213 10% 95%;
  color-scheme: dark;
}
</style>
