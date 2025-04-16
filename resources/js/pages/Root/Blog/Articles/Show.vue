<template>
    <Head :title="article.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-background">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-glow animate-text-glow">Article Details</h1>
                <div class="flex space-x-3">
                    <CyberLink :href="route('root.blog.articles.edit', article.slug)" variant="outline" size="md">
                        Edit Article
                    </CyberLink>
                    <CyberLink :href="route('root.blog.articles.index')" variant="outline" size="md">
                        Back to Articles
                    </CyberLink>
                </div>
            </div>

            <!-- Article Header -->
            <div class="glass-effect neon-border rounded-xl overflow-hidden">
                <div class="relative h-64 w-full overflow-hidden">
                    <img :src="article.hero_image_url || '/img/logo.png'" 
                        :alt="article.title"
                        class="h-full w-full object-cover transition-transform" 
                    />
                    
                    <!-- Feature Badges -->
                    <div class="absolute top-4 right-4 flex flex-col gap-2">
                        <div v-if="article.is_featured" class="bg-black/50 backdrop-blur-sm text-sm font-medium px-3 py-1 rounded-full border border-secondary/30 text-secondary animate-pulse-slow">
                            Featured
                        </div>
                        <div v-if="article.category && article.category.length > 0" class="bg-black/50 backdrop-blur-sm text-sm font-medium px-3 py-1 rounded-full border border-primary/30 text-primary animate-pulse-slow">
                            {{ article.category[0].name }}
                        </div>
                    </div>

                    <!-- Publishing Info -->
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6">
                        <h1 class="text-3xl font-bold mb-2 text-white">{{ article.title }}</h1>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-sm text-white/80">
                                <span>{{ article.published_at ? new Date(article.published_at).toLocaleDateString() : 'Draft' }}</span>
                                <span v-if="article.published_at">•</span>
                                <span v-if="article.published_at">{{ readingTime }} min read</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content Area -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Original URL section if available -->
                    <div v-if="article.original_url" class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-glow">Original Source</h2>
                        <a :href="article.original_url" target="_blank" rel="noopener noreferrer" 
                            class="text-primary hover:underline flex items-center gap-2">
                            <span>{{ article.original_url }}</span>
                            <span class="i-lucide-external-link"></span>
                        </a>
                    </div>

                    <!-- Content -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-glow">Content</h2>
                        <div class="prose prose-invert max-w-none mt-4" v-html="renderedContent"></div>
                    </div>

                    <!-- Hero Image Attribution -->
                    <div v-if="article.hero_image_author_name" class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-glow">Image Attribution</h2>
                        <div class="text-muted-foreground">
                            <p>Photo by 
                                <a v-if="article.hero_image_author_url" 
                                   :href="article.hero_image_author_url" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="text-primary hover:underline">
                                    {{ article.hero_image_author_name }}
                                </a>
                                <span v-else>{{ article.hero_image_author_name }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Tags Section -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-lg font-semibold mb-4 text-glow">Tags</h2>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="tag in article.tags" :key="tag.id" 
                                class="px-3 py-1 rounded-full bg-sidebar-accent text-sidebar-accent-foreground text-sm">
                                {{ tag.name }}
                            </span>
                            <span v-if="!article.tags || article.tags.length === 0" class="text-muted-foreground text-sm">
                                No tags available
                            </span>
                        </div>
                    </div>

                    <!-- Meta Information -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-lg font-semibold mb-4 text-glow">Details</h2>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-muted-foreground">ID:</dt>
                                <dd class="font-mono">{{ article.id }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-muted-foreground">UUID:</dt>
                                <dd class="font-mono">{{ article.uuid }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-muted-foreground">Slug:</dt>
                                <dd class="font-mono">{{ article.slug }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-muted-foreground">Published:</dt>
                                <dd>{{ article.published_at ? new Date(article.published_at).toLocaleDateString() : 'Draft' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-muted-foreground">Views:</dt>
                                <dd>{{ article.view_count || 0 }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-muted-foreground">Created:</dt>
                                <dd>{{ new Date(article.created_at).toLocaleDateString() }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-muted-foreground">Updated:</dt>
                                <dd>{{ new Date(article.updated_at).toLocaleDateString() }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Actions -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-lg font-semibold mb-4 text-glow">Actions</h2>
                        <div class="space-y-3">
                            <CyberLink :href="route('root.blog.articles.edit', article.slug)" variant="outline" size="sm" full-width>
                                Edit Article
                            </CyberLink>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { type Article } from '@/types/article';
import { Head } from '@inertiajs/vue3';
import CyberLink from '@/components/theme/CyberLink.vue';
import { computed } from 'vue';

// Define props for the component
const props = defineProps<{
    article: Article;
}>();

// Define breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Articles',
        href: route('root.blog.articles.index'),
    },
    {
        title: props.article.title,
        href: route('root.blog.articles.show', props.article.slug),
    },
];

// For a proper markdown implementation, you would use a library like marked
// This is a placeholder that simply returns the content with newlines converted to <br> tags
const renderedContent = computed(() => {
    return props.article.body ? props.article.body.replace(/\n/g, '<br>') : '';
});

// Calculate reading time based on word count
const readingTime = computed(() => {
    if (!props.article.body) return 1;
    
    // Average reading speed is about 200-250 words per minute
    const wordCount = props.article.body.split(/\s+/).length;
    const readingTimeMinutes = Math.ceil(wordCount / 225);
    
    // Return at least 1 minute
    return Math.max(1, readingTimeMinutes);
});
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

/* Styling for markdown content */
.prose {
  color: hsl(var(--foreground));
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
  color: hsl(var(--primary));
  margin-top: 1.5em;
  margin-bottom: 0.75em;
}

.prose a {
  color: hsl(var(--primary));
  text-decoration: underline;
  text-underline-offset: 2px;
}

.prose ul, .prose ol {
  padding-left: 1.5em;
}

.prose code {
  color: hsl(var(--secondary));
  background: hsl(var(--card));
  padding: 0.2em 0.4em;
  border-radius: 0.25em;
  font-size: 0.9em;
}

.prose pre {
  background: hsl(var(--card));
  padding: 1em;
  border-radius: 0.5em;
  overflow-x: auto;
  border: 1px solid hsl(var(--border));
}

/* Animation styles are now imported from theme.css and tailwind.config.js */
</style>
