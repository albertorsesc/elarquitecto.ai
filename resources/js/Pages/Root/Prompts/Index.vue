<template>
    <Head title="Prompts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 bg-background p-4">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="animate-text-glow text-2xl font-bold text-glow">Prompts</h1>
                <CyberLink :href="route('root.prompts.create')" variant="primary" size="md"> Create New Prompt </CyberLink>
            </div>

            <!-- Grid of Prompt Cards -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <!-- Prompt Card (repeated for each prompt) -->
                <div
                    v-for="prompt in prompts"
                    :key="prompt.id"
                    class="glass-effect neon-border group h-full overflow-hidden rounded-xl border border-border/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_15px_rgba(var(--primary-rgb),0.5)]"
                >
                    <!-- Card Image -->
                    <div class="relative h-40 w-full overflow-hidden">
                        <img
                            :src="prompt.image_url || '/img/logo.webp'"
                            :alt="prompt.title"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                        />

                        <!-- Category Badge -->
                        <div
                            class="absolute right-3 top-3 animate-pulse-slow rounded-full border border-primary/30 bg-black/50 px-2 py-1 text-xs font-medium text-primary backdrop-blur-sm"
                        >
                            {{ prompt.category && prompt.category.length > 0 ? prompt.category[0].name : 'Uncategorized' }}
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="flex flex-col gap-2 p-4">
                        <h3 class="line-clamp-1 text-lg font-semibold transition-colors group-hover:text-primary">
                            {{ prompt.title }}
                        </h3>

                        <p class="line-clamp-2 text-sm text-muted-foreground">
                            {{ prompt.excerpt }}
                        </p>

                        <!-- Tags -->
                        <div class="mt-1 flex flex-wrap gap-1">
                            <span
                                v-for="tag in prompt.tags"
                                :key="tag.id"
                                class="rounded-full bg-sidebar-accent px-2 py-0.5 text-xs text-sidebar-accent-foreground"
                            >
                                {{ tag.name }}
                            </span>
                        </div>

                        <!-- Footer -->
                        <div class="mt-3 flex items-center justify-between text-xs text-muted-foreground">
                            <span class="flex items-center gap-1">
                                <span>{{ prompt.word_count }} words</span>
                            </span>
                            <span>{{ new Date(prompt.published_at).toLocaleDateString() }}</span>
                        </div>

                        <!-- Edit Link -->
                        <div class="mt-3 border-t border-border/30 pt-3">
                            <CyberLink :href="route('root.prompts.show', prompt.slug)" variant="outline" size="sm" full-width>
                                View Details
                            </CyberLink>
                        </div>
                    </div>
                </div>

                <!-- Empty State (shown when no prompts) -->
                <div v-if="!prompts" class="col-span-full flex flex-col items-center justify-center gap-4 p-8 text-center">
                    <div class="rounded-full bg-muted p-3">
                        <div class="h-10 w-10 text-muted-foreground"></div>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-semibold">No prompts found</h3>
                        <p class="text-muted-foreground">Get started by creating your first prompt.</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import CyberLink from '@/components/theme/CyberLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { type Prompt } from '@/types/prompt';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Prompts',
        href: route('root.prompts.index'),
    },
];

const props = defineProps<{
    prompts: Prompt[];
}>();

const { prompts } = props;
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
