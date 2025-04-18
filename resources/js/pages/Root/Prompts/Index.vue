<template>
    <Head title="Prompts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-background">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-glow animate-text-glow">Prompts</h1>
                <CyberLink :href="route('root.prompts.create')" variant="primary" size="md">
                    Create New Prompt
                </CyberLink>
            </div>

            <!-- Grid of Prompt Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Prompt Card (repeated for each prompt) -->
                <div v-for="prompt in prompts" :key="prompt.id" 
                    class="group h-full overflow-hidden rounded-xl border border-border/50 glass-effect neon-border transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_15px_rgba(var(--primary-rgb),0.5)]">
                    
                    <!-- Card Image -->
                    <div class="relative h-40 w-full overflow-hidden">
                        <img :src="prompt.image_url || '/img/logo.webp'" :alt="prompt.title"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                        
                        <!-- Category Badge -->
                        <div class="absolute top-3 right-3 bg-black/50 backdrop-blur-sm text-xs font-medium px-2 py-1 rounded-full border border-primary/30 text-primary animate-pulse-slow">
                            {{ prompt.category && prompt.category.length > 0 ? prompt.category[0].name : 'Uncategorized' }}
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="flex flex-col gap-2 p-4">
                        <h3 class="text-lg font-semibold line-clamp-1 group-hover:text-primary transition-colors">
                            {{ prompt.title }}
                        </h3>
                        
                        <p class="text-sm text-muted-foreground line-clamp-2">
                            {{ prompt.excerpt }}
                        </p>
                        
                        <!-- Tags -->
                        <div class="flex flex-wrap gap-1 mt-1">
                            <span v-for="tag in prompt.tags" :key="tag.id" 
                                class="text-xs px-2 py-0.5 rounded-full bg-sidebar-accent text-sidebar-accent-foreground">
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
                        <div class="mt-3 pt-3 border-t border-border/30">
                            <CyberLink :href="route('root.prompts.show', prompt.slug)" variant="outline" size="sm" full-width>
                                View Details
                            </CyberLink>
                        </div>
                    </div>
                </div>

                <!-- Empty State (shown when no prompts) -->
                <div v-if="! prompts" class="col-span-full flex flex-col items-center justify-center gap-4 p-8 text-center">
                    <div class="rounded-full p-3 bg-muted">
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
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { type Prompt } from '@/types/prompt';
import { Head } from '@inertiajs/vue3';
import CyberLink from '@/components/theme/CyberLink.vue';

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
