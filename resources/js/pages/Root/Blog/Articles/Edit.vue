<template>
    <Head title="Edit Prompt" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-background">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-glow animate-text-glow">Edit Prompt</h1>
                <CyberLink :href="route('root.prompts.index')" variant="outline" size="md">
                    Back to Prompts
                </CyberLink>
            </div>

            <!-- Edit Prompt Form -->
            <div class="glass-effect neon-border rounded-xl p-6 max-w-4xl mx-auto w-full">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label for="title" class="block text-sm font-medium text-foreground/80">Title</label>
                        <AnimatedInputBorder 
                            id="title" 
                            v-model="form.title" 
                            placeholder="Enter prompt title" 
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
                            placeholder="prompt-slug" 
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

                    <!-- Excerpt -->
                    <div class="space-y-2">
                        <label for="excerpt" class="block text-sm font-medium text-foreground/80">Excerpt</label>
                        <textarea
                            id="excerpt"
                            v-model="form.excerpt"
                            rows="3"
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                            placeholder="Brief description of the prompt"
                            required
                        ></textarea>
                        <p v-if="form.errors.excerpt" class="mt-1 text-sm text-red-500">{{ form.errors.excerpt }}</p>
                    </div>

                    <!-- Content (Markdown) -->
                    <div class="space-y-2">
                        <label for="content" class="block text-sm font-medium text-foreground/80">Content (Markdown)</label>
                        <textarea
                            id="content"
                            v-model="form.content"
                            rows="8"
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300 font-mono"
                            placeholder="# Prompt Content
## Instructions
- Markdown is supported
- Use headings, lists, etc."
                            required
                        ></textarea>
                        <p v-if="form.errors.content" class="mt-1 text-sm text-red-500">{{ form.errors.content }}</p>
                    </div>

                    <!-- Image Upload -->
                    <div class="space-y-2">
                        <label for="image" class="block text-sm font-medium text-foreground/80">Featured Image</label>
                        <div class="flex items-center gap-4">
                            <div 
                                class="relative h-24 w-24 border-2 border-dashed border-border rounded-lg overflow-hidden flex items-center justify-center group cursor-pointer"
                                @click="handleFileInputClick"
                            >
                                <img 
                                    v-if="imagePreview" 
                                    :src="imagePreview" 
                                    alt="Preview" 
                                    class="h-full w-full object-cover"
                                />
                                <div v-else class="text-center p-2 text-xs text-muted-foreground">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-1 text-muted-foreground/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Click to upload
                                </div>
                                <div class="absolute inset-0 bg-primary/20 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-xs font-medium text-white">Change</span>
                                </div>
                                <input 
                                    ref="fileInput"
                                    type="file" 
                                    accept="image/*" 
                                    class="hidden" 
                                    @change="handleImageUpload"
                                />
                            </div>
                            <div class="flex-1 text-sm text-muted-foreground">
                                <p>Recommended size: 1200x630 pixels</p>
                                <p>Maximum size: 2MB</p>
                                <p>Formats: JPG, PNG, WebP</p>
                            </div>
                        </div>
                        <p v-if="form.errors.image" class="mt-1 text-sm text-red-500">{{ form.errors.image }}</p>
                    </div>

                    <!-- Two columns layout for remaining fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                        <!-- Word Count -->
                        <div class="space-y-2">
                            <label for="word_count" class="block text-sm font-medium text-foreground/80">Word Count</label>
                            <AnimatedInputBorder 
                                id="word_count" 
                                type="number"
                                v-model="form.word_count" 
                                placeholder="Approximate word count" 
                            />
                            <p v-if="form.errors.word_count" class="mt-1 text-sm text-red-500">{{ form.errors.word_count }}</p>
                        </div>
                    </div>

                    <!-- Target Models -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-foreground/80">Target AI Models</label>
                        <div class="space-y-4">
                            <div v-for="(models, provider) in models" :key="provider" class="rounded-lg border border-white/10 p-4">
                                <h3 class="text-sm font-medium text-primary mb-3 capitalize">{{ provider }}</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div 
                                        v-for="model in models" 
                                        :key="model"
                                        class="flex items-center space-x-2"
                                    >
                                        <input 
                                            type="checkbox" 
                                            :id="`model-${model}`" 
                                            :value="model" 
                                            v-model="form.target_models"
                                            class="rounded border-white/20 bg-background/50 text-primary focus:ring-primary/30"
                                        />
                                        <label :for="`model-${model}`" class="text-sm">{{ model }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p v-if="form.errors.target_models" class="mt-1 text-sm text-red-500">{{ form.errors.target_models }}</p>
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
                            <span v-else>Update Prompt</span>
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
import { type Prompt, type ModelsConfig } from '@/types/prompt';
import { Head, useForm } from '@inertiajs/vue3';
import CyberLink from '@/components/theme/CyberLink.vue';
import AnimatedInputBorder from '@/components/theme/AnimatedInputBorder.vue';
import { ref, watch, onMounted } from 'vue';

// Define props with prompt data
const props = defineProps<{
    prompt: Prompt;
    models: ModelsConfig;
    categories: Category[];
    tags: Tag[];
}>();

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
    {
        title: 'Edit',
        href: route('root.prompts.edit', props.prompt.slug),
    }
];

// Extract category_id and tag_ids from the loaded prompt
const currentCategoryId = props.prompt.category[0]?.id || null;
const currentTagIds = props.prompt.tags.map(tag => tag.id) || [];

// Available tags based on selected category
const availableTags = ref<Tag[]>([]);

// Form state using Inertia's useForm
const form = useForm({
    title: props.prompt.title,
    slug: props.prompt.slug,
    excerpt: props.prompt.excerpt,
    content: props.prompt.content,
    image: null as File | null,
    published_at: props.prompt.published_at,
    word_count: String(props.prompt.word_count),
    target_models: props.prompt.target_models,
    category_id: currentCategoryId,
    tags: currentTagIds
});

const imagePreview = ref<string | null>(props.prompt.image_url || null);
const fileInput = ref<HTMLInputElement | null>(null);

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

// Safe file input click handler
const handleFileInputClick = () => {
    if (fileInput.value) {
        fileInput.value.click();
    }
};

// Handle image upload
const handleImageUpload = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        
        // Set image and create preview
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

// Form submission using Inertia
const submit = () => {
    form.put(route('root.prompts.update', props.prompt.slug));
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
</style>
