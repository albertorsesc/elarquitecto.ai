<template>
    <Head :title="prompt.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-background">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-glow animate-text-glow">Prompt Details</h1>
                <div class="flex space-x-3">
                    <CyberLink :href="route('root.prompts.edit', prompt.slug)" variant="outline" size="md">
                        Edit Prompt
                    </CyberLink>
                    <CyberLink :href="route('root.prompts.index')" variant="outline" size="md">
                        Back to Prompts
                    </CyberLink>
                </div>
            </div>

            <!-- Prompt Header -->
            <div class="glass-effect neon-border rounded-xl overflow-hidden">
                <div class="relative h-64 w-full overflow-hidden">
                    <img :src="prompt.image_url || '/img/logo.png'" :alt="prompt.title"
                        class="h-full w-full object-cover" />
                    
                    <!-- Category Badge -->
                    <div class="absolute top-4 right-4 bg-black/50 backdrop-blur-sm text-sm font-medium px-3 py-1 rounded-full border border-primary/30 text-primary animate-pulse-slow">
                        {{ prompt.category && prompt.category.length > 0 ? prompt.category[0].name : 'Uncategorized' }}
                    </div>

                    <!-- Publishing Info -->
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6">
                        <h1 class="text-3xl font-bold mb-2 text-white">{{ prompt.title }}</h1>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-sm text-white/80">
                                <span>{{ new Date(prompt.published_at).toLocaleDateString() }}</span>
                                <span>•</span>
                                <span>{{ prompt.word_count }} words</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content Area -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Excerpt -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-glow">Description</h2>
                        <p class="text-lg italic text-foreground/90">{{ prompt.excerpt }}</p>
                    </div>

                    <!-- Content -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-glow">Content</h2>
                        <div class="prose prose-invert max-w-none mt-4" v-html="renderedContent"></div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Tags Section -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-lg font-semibold mb-4 text-glow">Tags</h2>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="tag in prompt.tags" :key="tag.id" 
                                class="px-3 py-1 rounded-full bg-sidebar-accent text-sidebar-accent-foreground text-sm">
                                {{ tag.name }}
                            </span>
                            <span v-if="!prompt.tags || prompt.tags.length === 0" class="text-muted-foreground text-sm">
                                No tags available
                            </span>
                        </div>
                    </div>

                    <!-- Target Models Section -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-lg font-semibold mb-4 text-glow">Compatible Models</h2>
                        <div class="space-y-2">
                            <div v-if="prompt.target_models && prompt.target_models.length > 0">
                                <div v-for="model in prompt.target_models" :key="model" class="flex items-center gap-2 mb-2">
                                    <div class="h-2 w-2 rounded-full bg-primary animate-pulse"></div>
                                    <span>{{ model }}</span>
                                </div>
                            </div>
                            <div v-else class="text-muted-foreground text-sm">
                                No specific models defined
                            </div>
                        </div>
                    </div>

                    <!-- Meta Information -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-lg font-semibold mb-4 text-glow">Details</h2>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-muted-foreground">ID:</dt>
                                <dd class="font-mono">{{ prompt.id }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-muted-foreground">Slug:</dt>
                                <dd class="font-mono">{{ prompt.slug }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-muted-foreground">Published:</dt>
                                <dd>{{ prompt.published_at ? new Date(prompt.published_at).toLocaleDateString() : 'Draft' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Actions -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-lg font-semibold mb-4 text-glow">Actions</h2>
                        <div class="space-y-3">
                            <CyberLink :href="route('root.prompts.edit', prompt.id)" variant="outline" size="sm" full-width>
                                Edit Prompt
                            </CyberLink>
                            <button 
                                @click="copyPrompt"
                                data-copy-button
                                class="cyber-button w-full py-2 px-4 rounded-lg font-medium border border-white/10 bg-white/5 text-foreground hover:bg-white/10 transition-all flex items-center justify-center gap-2"
                            >
                                <span>Copy to Clipboard</span>
                            </button>
                            <button 
                                @click="confirmDelete"
                                class="w-full py-2 px-4 rounded-lg font-medium border border-red-500/30 bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-all flex items-center justify-center gap-2"
                            >
                                <span>Delete Prompt</span>
                            </button>
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
import { type Prompt } from '@/types/prompt';
import { Head } from '@inertiajs/vue3';
import CyberLink from '@/components/theme/CyberLink.vue';
import { computed } from 'vue';

// Define props for the component
const props = defineProps<{
    prompt: Prompt;
}>();

// Define breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Prompts',
        href: route('root.prompts.index'),
    },
    {
        title: props.prompt.title,
        href: route('root.prompts.show', props.prompt.slug),
    },
];

// For a proper markdown implementation, you would use a library like marked
// This is a placeholder that simply returns the content with newlines converted to <br> tags
const renderedContent = computed(() => {
    return props.prompt.content ? props.prompt.content.replace(/\n/g, '<br>') : '';
});

// Copy prompt to clipboard
const copyPrompt = () => {
    try {
        // Create a temporary textarea element
        const textArea = document.createElement('textarea');
        // Set its value to the prompt content
        textArea.value = props.prompt.content;
        // Make it invisible
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        // Select and copy the content
        textArea.focus();
        textArea.select();
        const success = document.execCommand('copy');
        // Remove the temporary element
        document.body.removeChild(textArea);

        // Show success feedback
        const button = document.querySelector('[data-copy-button]');
        if (button) {
            const originalText = button.innerHTML;
            button.classList.add('animate-pulse');
            button.innerHTML = '<span class="text-glow">Copied!</span>';
            
            // Reset button text after 2 seconds
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('animate-pulse');
            }, 2000);
        }

        if (!success) {
            console.error('Failed to copy text');
            alert('Failed to copy to clipboard. Please try again.');
        }
    } catch (error) {
        console.error('Failed to copy content: ', error);
        alert('Failed to copy to clipboard. Please try again.');
    }
};

// Confirm deletion modal
const confirmDelete = () => {
    if (confirm('Are you sure you want to delete this prompt? This action cannot be undone.')) {
        // Implement delete functionality here
    }
};
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
