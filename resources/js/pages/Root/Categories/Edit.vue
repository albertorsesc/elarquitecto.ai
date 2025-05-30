<template>
    <Head title="Edit Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-background">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-glow animate-text-glow">Edit Category</h1>
                <CyberLink :href="route('root.categories.index')" variant="outline" size="md">
                    Back to Categories
                </CyberLink>
            </div>

            <!-- Edit Category Form -->
            <div class="glass-effect neon-border rounded-xl p-6 max-w-4xl mx-auto w-full">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-foreground/80">Name</label>
                        <AnimatedInputBorder 
                            id="name" 
                            v-model="form.name" 
                            placeholder="Enter category name" 
                            maxlength="255"
                            required
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">{{ form.errors.name }}</p>
                    </div>

                    <!-- Slug -->
                    <div class="space-y-2">
                        <label for="slug" class="block text-sm font-medium text-foreground/80">
                            Slug
                            <span 
                                @click="generateSlug" 
                                class="ml-2 text-primary cursor-pointer text-xs hover:text-primary/80 transition-colors"
                            >
                                (Generate from name)
                            </span>
                        </label>
                        <AnimatedInputBorder 
                            id="slug" 
                            v-model="form.slug" 
                            placeholder="category-slug" 
                            required
                        />
                        <p v-if="form.errors.slug" class="mt-1 text-sm text-red-500">{{ form.errors.slug }}</p>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-foreground/80">Description</label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                            placeholder="Brief description of the category"
                        ></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-500">{{ form.errors.description }}</p>
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
                            <span v-else>Update Category</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Category } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import CyberLink from '@/components/theme/CyberLink.vue';
import AnimatedInputBorder from '@/components/theme/AnimatedInputBorder.vue';

// Define props with category data
const props = defineProps<{
    category: Category;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Categories',
        href: route('root.categories.index'),
    },
    {
        title: 'Edit',
        href: route('root.categories.edit', props.category.slug),
    }
];

// Form state using Inertia's useForm
const form = useForm({
    name: props.category.name,
    slug: props.category.slug,
    description: props.category.description || '',
});

// Generate slug from name
const generateSlug = () => {
    if (form.name) {
        form.slug = form.name
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
    }
};

// Form submission using Inertia
const submit = () => {
    form.put(route('root.categories.update', props.category.slug));
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
</style>
