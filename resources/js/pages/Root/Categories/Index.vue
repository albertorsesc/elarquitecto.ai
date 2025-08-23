<template>
    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-background">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-glow animate-text-glow">Categories</h1>
                <CyberLink :href="route('root.categories.create')" variant="primary" size="md">
                    Create New Category
                </CyberLink>
            </div>

            <!-- Categories Table -->
            <div class="w-full overflow-hidden rounded-xl border border-border/50 glass-effect ">
                <div class="overflow-x-auto">
                    <table class="w-full" v-if="categories && categories.length > 0">
                        <thead>
                            <tr class="bg-sidebar-accent/30 text-sidebar-accent-foreground border-b border-border/30">
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Slug</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Tags</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Prompts</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/20">
                            <tr v-for="category in categories" :key="category.id" 
                                class="transition-colors hover:bg-muted/30">
                                <td class="px-4 py-3 whitespace-nowrap text-sm">{{ category.id }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">{{ category.name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-muted-foreground">{{ category.slug }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <div class="flex flex-wrap gap-1">
                                        <span v-if="category.tags && category.tags.length > 0" 
                                            class="text-xs px-2 py-0.5 rounded-full bg-primary/20 text-primary">
                                            {{ category.tags.length }} tags
                                        </span>
                                        <span v-else class="text-xs text-muted-foreground">No tags</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <span v-if="category.prompts && category.prompts.length > 0" 
                                        class="text-xs px-2 py-0.5 rounded-full bg-primary/20 text-primary">
                                        {{ category.prompts.length }} prompts
                                    </span>
                                    <span v-else class="text-xs text-muted-foreground">No prompts</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-right">
                                    <div class="flex gap-2">
                                        <CyberLink :href="route('root.categories.edit', category.slug)" 
                                            variant="outline" size="sm">
                                            Edit
                                        </CyberLink>
                                        <CyberLink :href="route('root.categories.destroy', category.slug)" 
                                            variant="primary" size="sm"
                                            method="delete"
                                            as="button">
                                            Delete
                                        </CyberLink>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between border-t border-border/30 bg-sidebar-accent/10 px-4 py-3 sm:px-6">
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground">
                                Showing <span class="font-medium">1</span> to <span class="font-medium">{{ categories?.length || 0 }}</span> of <span class="font-medium">{{ categories?.length || 0 }}</span> categories
                            </p>
                        </div>
                        <div>
                            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                <a href="#" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-muted-foreground ring-1 ring-inset ring-border hover:bg-muted">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="#" aria-current="page" class="relative z-10 inline-flex items-center bg-primary/20 px-4 py-2 text-sm font-semibold text-primary focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">1</a>
                                <a href="#" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-muted-foreground ring-1 ring-inset ring-border hover:bg-muted">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Empty State (shown when no categories) -->
                <div v-if="!categories || categories.length === 0" class="flex flex-col items-center justify-center gap-4 p-8 text-center">
                    <div class="rounded-full p-3 bg-muted">
                        <div class="h-10 w-10 text-muted-foreground"></div>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-semibold">No categories found</h3>
                        <p class="text-muted-foreground">Get started by creating your first category.</p>
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
        title: 'Categories',
        href: route('root.categories.index'),
    },
];

const props = defineProps<{
    categories: Array<{
        id: number;
        name: string;
        slug: string;
        description?: string;
        tags?: Array<{ id: number; name: string }>;
        prompts?: Array<any>;
        created_at?: string;
        updated_at?: string;
    }>;
}>();

const { categories } = props;
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
