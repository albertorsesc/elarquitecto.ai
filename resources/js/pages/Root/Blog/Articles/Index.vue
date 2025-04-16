<template>
    <Head title="Articles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-background">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-glow animate-text-glow">Articles</h1>
                <CyberLink :href="route('root.blog.articles.create')" variant="primary" size="md">
                    Create New Article
                </CyberLink>
            </div>

            <!-- Grid of Article Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Article Card (repeated for each article) -->
                <ArticleCard 
                    v-for="article in articles" 
                    :key="article.id" 
                    :article="article" 
                />

                <!-- Empty State (shown when no articles) -->
                <div v-if="articles.length === 0" class="col-span-full flex flex-col items-center justify-center gap-4 p-8 text-center">
                    <div class="rounded-full p-3 bg-muted">
                        <div class="h-10 w-10 text-muted-foreground"></div>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-semibold">No articles found</h3>
                        <p class="text-muted-foreground">Get started by creating your first article.</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Article } from '@/types';
import { Head } from '@inertiajs/vue3';
import CyberLink from '@/components/theme/CyberLink.vue';
import ArticleCard from '@/components/Blog/ArticleCard.vue';

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
        title: 'Articles',
        href: route('root.blog.articles.index'),
    },
];

const props = defineProps<{
    articles: Article[];
}>();

const { articles } = props;
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
