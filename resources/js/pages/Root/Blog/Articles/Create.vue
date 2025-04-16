<template>
    <Head title="Create Article" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-background">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-glow animate-text-glow">Create Article</h1>
                <CyberLink :href="route('root.blog.articles.index')" variant="outline" size="md">
                    Back to Articles
                </CyberLink>
            </div>

            <!-- Create Article Form -->
            <div class="glass-effect neon-border rounded-xl p-6 max-w-4xl mx-auto w-full">
                <form @submit.prevent="submit" method="POST" class="space-y-6">
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

                    <!-- Slug -->
                    <div class="space-y-2">
                        <label for="slug" class="block text-sm font-medium text-foreground/80">
                            Slug
                            <span 
                                @click="generateSlug" 
                                class="ml-2 text-primary cursor-pointer text-xs hover:text-primary/80 transition-colors"
                            >
                                (Generate from title)
                            </span>
                        </label>
                        <AnimatedInputBorder 
                            id="slug" 
                            v-model="form.slug" 
                            placeholder="article-slug" 
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
                                selectButtonText="Seleccionar imagen"
                                :maxFileSize="5"
                                @error="handleDropzoneError"
                                @file-uploaded="handleFileUploaded"
                                @file-removed="clearDirectFile"
                            />
                        </div>
                        
                        <p v-if="form.errors.hero_image" class="mt-1 text-sm text-red-500">{{ form.errors.hero_image }}</p>
                    </div>

                    <!-- Alternative Image URL Section -->
                    <div v-if="!hasHeroImage" class="space-y-4 p-4 border border-dashed border-border/50 rounded-xl">
                        <div class="flex items-center">
                            <div class="h-px flex-grow bg-border/30"></div>
                            <span class="px-3 text-xs text-muted-foreground">OR provide image URLs</span>
                            <div class="h-px flex-grow bg-border/30"></div>
                        </div>
                        
                        <!-- Hero Image URL -->
                        <div class="space-y-2">
                            <label for="hero_image_url" class="block text-sm font-medium text-foreground/80">Hero Image URL</label>
                            <AnimatedInputBorder 
                                id="hero_image_url" 
                                v-model="form.hero_image_url" 
                                placeholder="https://example.com/image.jpg" 
                            />
                            <p v-if="form.errors.hero_image_url" class="mt-1 text-sm text-red-500">{{ form.errors.hero_image_url }}</p>
                        </div>

                        <!-- Two columns layout for image attribution -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Hero Image Author Name -->
                            <div class="space-y-2">
                                <label for="hero_image_author_name" class="block text-sm font-medium text-foreground/80">Image Author Name</label>
                                <AnimatedInputBorder 
                                    id="hero_image_author_name" 
                                    v-model="form.hero_image_author_name" 
                                    placeholder="Photographer or Artist Name" 
                                />
                                <p v-if="form.errors.hero_image_author_name" class="mt-1 text-sm text-red-500">{{ form.errors.hero_image_author_name }}</p>
                            </div>

                            <!-- Hero Image Author URL -->
                            <div class="space-y-2">
                                <label for="hero_image_author_url" class="block text-sm font-medium text-foreground/80">Image Author URL</label>
                                <AnimatedInputBorder 
                                    id="hero_image_author_url" 
                                    v-model="form.hero_image_author_url" 
                                    placeholder="https://example.com/author" 
                                />
                                <p v-if="form.errors.hero_image_author_url" class="mt-1 text-sm text-red-500">{{ form.errors.hero_image_author_url }}</p>
                            </div>
                        </div>
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
                            <span v-else>Create Article</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Category, type Tag } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import CyberLink from '@/components/theme/CyberLink.vue';
import AnimatedInputBorder from '@/components/theme/AnimatedInputBorder.vue';
import Dropzone from '@/components/Dropzone.vue';
import { ref, watch, computed } from 'vue';

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
        title: 'Create',
        href: route('root.blog.articles.create'),
    }
];

// For Vue3Dropzone
const heroImages = ref<any[]>([]);

// Computed property to check if hero image exists
const hasHeroImage = computed(() => heroImages.value.length > 0);

// Form state using Inertia's useForm
const form = useForm({
    title: '',
    slug: '',
    body: '',
    hero_image: null as File | null,
    hero_image_url: '',
    hero_image_author_name: '',
    hero_image_author_url: '',
    original_url: '',
    published_at: '',
    is_pinned: false,
    is_featured: false,
    category_id: '' as number | string,
    tags: [] as number[]
});

// Available tags based on selected category
const availableTags = ref<Tag[]>([]);

// Props type definition
const props = defineProps<{
    categories: Category[];
    tags: Tag[];
}>();

// Handle dropzone errors
const handleDropzoneError = (error: { type: string; files: File[] }) => {
    const { type, files } = error;
    
    if (type === 'file-too-large') {
        alert(`File size exceeds the 2MB limit: ${files.map(f => f.name).join(', ')}`);
    } else if (type === 'invalid-file-format') {
        alert(`Invalid file format: ${files.map(f => f.name).join(', ')}`);
    }
};

// Handle file uploaded from dropzone
const handleFileUploaded = (fileData: any) => {
    if (fileData && fileData.file) {
        // When a file is uploaded via dropzone, we need to clear the URL fields
        form.hero_image_url = '';
        form.hero_image_author_name = '';
        form.hero_image_author_url = '';
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

// Watch for category changes
watch(() => form.category_id, updateAvailableTags);

// Find tag name by id
function findTagName(tagId: number): string {
    const tag = props.tags.find(t => t.id === tagId);
    return tag?.name || 'Unknown Tag';
}

// Remove a tag from selection
function removeTag(tagId: number): void {
    form.tags = form.tags.filter(id => id !== tagId);
}

// Generate slug from title
const generateSlug = () => {
    if (form.title) {
        form.slug = form.title
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
    }
};

// Form submission using Inertia
const submit = (e?: Event) => {
    // Explicitly prevent default form submission behavior
    if (e) e.preventDefault();
    
    // Assign the uploaded file from dropzone to the form
    if (heroImages.value.length > 0) {
        form.hero_image = heroImages.value[0].file;
    }

    // Submit to the server
    form.post(route('root.blog.articles.store'), {
        forceFormData: true,
        preserveScroll: true,
        onProgress: (progress) => {
            console.log(`Upload Progress: ${progress}%`);
        },
        onSuccess: () => {
            // Reset the form and dropzone
            heroImages.value = [];
        },
    });
};

// Clear direct file preview
const clearDirectFile = () => {
    // Reset form fields since we're removing the image
    form.hero_image = null;
    heroImages.value = [];
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

