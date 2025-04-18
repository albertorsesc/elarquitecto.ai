<template>
    <Head title="Edit Article" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-background">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-glow animate-text-glow">Edit Article</h1>
                <CyberLink :href="route('root.blog.articles.index')" variant="outline" size="md">
                    Back to Articles
                </CyberLink>
            </div>

            <!-- Edit Article Form -->
            <div class="glass-effect neon-border rounded-xl p-6 max-w-4xl mx-auto w-full">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label for="title" class="block text-sm font-medium text-foreground/80">Title</label>
                        <AnimatedInputBorder 
                            id="title" 
                            v-model="form.title" 
                            placeholder="Enter article title" 
                            required
                        />
                        <p v-if="form.errors.title" class="mt-1 text-sm text-red-500">{{ form.errors.title }}</p>
                    </div>

                    <!-- Slug (read-only) -->
                    <div class="space-y-2">
                        <label for="slug" class="block text-sm font-medium text-foreground/80">
                            Slug
                        </label>
                        <AnimatedInputBorder 
                            id="slug" 
                            v-model="form.slug" 
                            placeholder="article-slug"
                            disabled
                            class="opacity-70" 
                            required
                        />
                        <p v-if="form.errors.slug" class="mt-1 text-sm text-red-500">{{ form.errors.slug }}</p>
                    </div>

                    <!-- Category and Tags Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Category Dropdown -->
                        <div class="space-y-2">
                            <label for="category" class="block text-sm font-medium text-foreground/80">Category</label>
                            <select
                                id="category"
                                v-model="form.category_id"
                                class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground 
                                       focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 
                                       focus:ring-cyan-400/30 transition-all duration-300"
                                required
                                @change="updateAvailableTags"
                            >
                                <option value="" disabled>Select a category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-500">{{ form.errors.category_id }}</p>
                        </div>

                        <!-- Tags Multi-select -->
                        <div class="space-y-2">
                            <label for="tags" class="block text-sm font-medium text-foreground/80">Tags</label>
                            <div class="relative">
                                <select
                                    id="tags"
                                    v-model="form.tags"
                                    multiple
                                    class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground
                                           focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1
                                           focus:ring-cyan-400/30 transition-all duration-300 min-h-[120px]"
                                    required
                                >
                                    <option v-if="availableTags.length === 0 && form.category_id" disabled>
                                        No tags available for this category
                                    </option>
                                    <option v-else-if="!form.category_id" disabled>
                                        Please select a category first
                                    </option>
                                    <option v-for="tag in availableTags" :key="tag.id" :value="tag.id">
                                        {{ tag.name }}
                                    </option>
                                </select>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <div v-for="tagId in form.tags" :key="tagId" 
                                         class="inline-flex items-center px-2 py-1 rounded-full text-xs
                                                bg-primary/20 text-primary-foreground border border-primary/30">
                                        {{ findTagName(tagId) }}
                                        <button @click.prevent="removeTag(tagId)" type="button" class="ml-1 text-primary-foreground/70 hover:text-primary-foreground">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p v-if="form.errors.tags" class="mt-1 text-sm text-red-500">{{ form.errors.tags }}</p>
                        </div>
                    </div>

                    <!-- Body (Markdown) -->
                    <div class="space-y-2">
                        <label for="body" class="block text-sm font-medium text-foreground/80">Article Content (Markdown)</label>
                        <textarea
                            id="body"
                            v-model="form.body"
                            rows="12"
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300 font-mono"
                            placeholder="# Article Content
## Introduction
- Markdown is supported
- Use headings, lists, etc."
                            required
                        ></textarea>
                        <p v-if="form.errors.body" class="mt-1 text-sm text-red-500">{{ form.errors.body }}</p>
                    </div>

                    <!-- Hero Image Upload -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-foreground/80">Hero Image</label>
                        
                        <div>
                            <Dropzone
                                v-model="heroImages"
                                :maxFiles="1"
                                acceptType="image"
                                fileTypeLabel="image"
                                selectButtonText="Select image"
                                :maxFileSize="5"
                                @error="handleDropzoneError"
                                @file-removed="clearDirectFile"
                                :previews="article.hero_image_url ? [article.hero_image_url] : []"
                            />
                        </div>
                        
                        <p v-if="form.errors.hero_image" class="mt-1 text-sm text-red-500">{{ form.errors.hero_image }}</p>
                    </div>

                    <!-- Original URL (for cross-posting) -->
                    <div class="space-y-2">
                        <label for="original_url" class="block text-sm font-medium text-foreground/80">Original URL (if cross-posted)</label>
                        <AnimatedInputBorder 
                            id="original_url" 
                            v-model="form.original_url" 
                            placeholder="https://example.com/original-post" 
                        />
                        <p v-if="form.errors.original_url" class="mt-1 text-sm text-red-500">{{ form.errors.original_url }}</p>
                    </div>

                    <!-- Article Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Published At -->
                        <div class="space-y-2">
                            <label for="published_at" class="block text-sm font-medium text-foreground/80">Published At</label>
                            <AnimatedInputBorder 
                                id="published_at" 
                                type="datetime-local"
                                v-model="form.published_at" 
                            />
                            <p v-if="form.errors.published_at" class="mt-1 text-sm text-red-500">{{ form.errors.published_at }}</p>
                        </div>

                        <!-- Featured Article -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-foreground/80">Featured Article</label>
                            <div class="flex items-center h-10">
                                <label class="inline-flex items-center space-x-2 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        v-model="form.is_featured"
                                        class="rounded border-white/20 bg-background/50 text-primary focus:ring-primary/30"
                                    />
                                    <span class="text-sm">Mark as featured</span>
                                </label>
                            </div>
                        </div>

                        <!-- Pinned Article -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-foreground/80">Pinned Article</label>
                            <div class="flex items-center h-10">
                                <label class="inline-flex items-center space-x-2 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        v-model="form.is_pinned"
                                        class="rounded border-white/20 bg-background/50 text-primary focus:ring-primary/30"
                                    />
                                    <span class="text-sm">Pin to top</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-border/30">
                        <button 
                            type="submit" 
                            class="neon-border w-full py-2 px-4 rounded-lg font-medium focus:outline-none transition-all duration-300
                                   border border-primary/30 bg-primary/10 text-primary-foreground hover:bg-primary/90 focus:ring-2 focus:ring-primary/50 focus:ring-offset-2"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                            <span v-else>Update Article</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Category, type Tag, type Article } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import CyberLink from '@/components/theme/CyberLink.vue';
import AnimatedInputBorder from '@/components/theme/AnimatedInputBorder.vue';
import Dropzone from '@/components/Dropzone.vue';
import { ref, watch, onMounted } from 'vue';

// Define props with article data
const props = defineProps<{
    article: Article;
    categories: Category[];
    tags: Tag[];
}>();

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
    {
        title: props.article.title,
        href: route('root.blog.articles.show', props.article.slug),
    },
    {
        title: 'Edit',
        href: route('root.blog.articles.edit', props.article.slug),
    }
];

// For Vue3Dropzone
const heroImages = ref<any[]>([]);

// Extract category_id and tag_ids from the loaded article
const currentCategoryId = props.article.category?.[0]?.id || null;
const currentTagIds = props.article.tags?.map(tag => tag.id) || [];

// Available tags based on selected category
const availableTags = ref<Tag[]>([]);

const form = useForm({
    title: props.article.title,
    slug: props.article.slug,
    body: props.article.body,
    hero_image: null as File | null,
    original_url: props.article.original_url || '',
    published_at: props.article.published_at || '',
    is_pinned: props.article.is_pinned || false,
    is_featured: props.article.is_featured || false,
    category_id: currentCategoryId,
    tags: currentTagIds
});

// Handle dropzone errors
const handleDropzoneError = (error: { type: string; files: File[] }) => {
    const { type, files } = error;
    
    if (type === 'file-too-large') {
        alert(`File size exceeds the 5MB limit: ${files.map(f => f.name).join(', ')}`);
    } else if (type === 'invalid-file-format') {
        alert(`Invalid file format: ${files.map(f => f.name).join(', ')}`);
    }
};

// Update available tags when category changes
function updateAvailableTags() {
    if (!form.category_id) {
        availableTags.value = [];
        form.tags = [];
        return;
    }
    
    // Find the selected category
    const selectedCategory = props.categories.find(cat => cat.id === Number(form.category_id));
    
    // If the category has preloaded tags, use them
    if (selectedCategory?.tags) {
        availableTags.value = selectedCategory.tags;
    } else {
        // Otherwise filter from all tags
        availableTags.value = props.tags.filter(tag => tag.category_id === Number(form.category_id));
    }
    
    // Clear selected tags that don't belong to this category
    form.tags = form.tags.filter(tagId => 
        availableTags.value.some(tag => tag.id === tagId)
    );
}

// Find tag name by id
function findTagName(tagId: number): string {
    const tag = props.tags.find(t => t.id === tagId);
    return tag?.name || 'Unknown Tag';
}

// Remove a tag from selection
function removeTag(tagId: number): void {
    form.tags = form.tags.filter(id => id !== tagId);
}

// Initialize available tags based on the current category
onMounted(() => {
    // Ensure form.category_id is properly set before updating available tags
    if (!form.category_id && currentCategoryId) {
        form.category_id = currentCategoryId;
    }
    
    updateAvailableTags();
});

// Watch for category changes
watch(() => form.category_id, updateAvailableTags);

// Clear direct file preview
const clearDirectFile = () => {
    // Reset form fields since we're removing the image
    form.hero_image = null;
    heroImages.value = [];
};

// Form submission using Inertia
const submit = () => {
    // Assign the uploaded file from dropzone to the form
    if (heroImages.value.length > 0) {
        form.hero_image = heroImages.value[0].file;
    }
    
    router.post(route('root.blog.articles.update', props.article.slug), {
        _method: 'put',
        title: form.title,
        body: form.body,
        slug: form.slug,
        hero_image: form.hero_image,
        original_url: form.original_url,
        published_at: form.published_at,
        is_pinned: form.is_pinned,
        is_featured: form.is_featured,
        category_id: form.category_id,
        tags: form.tags
    }, {
        forceFormData: true, // Ensure we're using FormData for file uploads
        onSuccess: () => {
            // Reset the dropzone
            heroImages.value = [];
        },
        onError: (errors) => {
            form.errors = errors;
        }
    });
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

/* Custom styling for checkbox */
input[type="checkbox"] {
  @apply h-4 w-4;
  accent-color: hsl(var(--primary));
  outline: none !important;
}

input[type="checkbox"]:focus {
  @apply ring-1 ring-primary/50;
}

/* Custom styling for select elements */
select {
  @apply rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground;
  appearance: auto;
}

select:focus {
  @apply border-cyan-400/30 bg-background/70 outline-none ring-1 ring-cyan-400/30;
}

select[multiple] {
  height: auto;
  min-height: 120px;
}

select[multiple] option {
  padding: 8px;
  margin-bottom: 2px;
  border-radius: 4px;
}

select[multiple] option:checked {
  background-color: hsl(var(--primary) / 0.2);
  color: hsl(var(--primary-foreground));
}

/* Customize Vue3Dropzone to match app style */
:root {
  --v3-dropzone--primary: var(--primary-rgb);
  --v3-dropzone--border: 214, 216, 220;
  --v3-dropzone--description: 190, 191, 195;
  --v3-dropzone--overlay: 40, 44, 53;
  --v3-dropzone--error: 255, 76, 81;
  --v3-dropzone--success: 36, 179, 100;
}

/* Style improvements for the dropzone */
.drop-zone {
  @apply rounded-xl border border-white/10 bg-background/50 !important;
}

.drop-zone:hover {
  @apply border-primary/30 !important;
}

.drop-zone .drop-zone-title {
  @apply text-foreground/80 !important;
}

.drop-zone .drop-zone-description {
  @apply text-muted-foreground !important;
}
</style>
