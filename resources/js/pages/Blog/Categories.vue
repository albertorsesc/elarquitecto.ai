<script setup lang="ts">
import FloatingNeonLines from '@/components/theme/FloatingNeonLines.vue';
import FormButton from '@/components/theme/FormButton.vue';
import FormInput from '@/components/theme/FormInput.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    categories: Array,
    tags: Array,
});

const categoryForm = useForm({
    name: '',
});

const tagForm = useForm({
    name: '',
});

const submitCategory = () => {
    categoryForm.post(route('blog.categories.store'), {
        onSuccess: () => {
            categoryForm.reset();
        },
    });
};

const submitTag = () => {
    tagForm.post(route('blog.tags.store'), {
        onSuccess: () => {
            tagForm.reset();
        },
    });
};

const deleteCategory = (id) => {
    if (confirm('Are you sure you want to delete this category? This will remove the category from all associated posts.')) {
        router.delete(route('blog.categories.destroy', id));
    }
};

const deleteTag = (id) => {
    if (confirm('Are you sure you want to delete this tag? This will remove the tag from all associated posts.')) {
        router.delete(route('blog.tags.destroy', id));
    }
};
</script>

<template>
    <Head title="Manage Categories & Tags" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-glow text-foreground">
                    Manage Categories & Tags
                </h2>
                <Link
                    :href="route('root.articles.index')"
                    class="rounded-md bg-background/80 px-4 py-2 text-sm font-medium text-foreground shadow-sm hover:bg-background/60 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 border border-white/10"
                >
                    Back to Posts
                </Link>
            </div>
        </template>

        <!-- Background Effects -->
        <FloatingNeonLines variant="sparse" :opacity="0.2" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Categories Section -->
                    <GlassContainer
                        variant="dark"
                        :withBorders="true"
                        :withCorners="true"
                        rounded="xl"
                        padding="lg"
                    >
                        <h3 class="text-lg font-semibold text-glow text-foreground mb-4">Categories</h3>

                        <!-- Add Category Form -->
                        <form @submit.prevent="submitCategory" class="mb-6">
                            <div class="flex space-x-2">
                                <FormInput
                                    v-model="categoryForm.name"
                                    placeholder="New category name"
                                    :error="categoryForm.errors.name"
                                    class="flex-1"
                                />
                                <FormButton
                                    type="submit"
                                    variant="primary"
                                    :disabled="categoryForm.processing"
                                >
                                    Add
                                </FormButton>
                            </div>
                        </form>

                        <!-- Categories List -->
                        <div class="space-y-2">
                            <div v-if="!categories?.length" class="text-foreground/60 text-center py-4">
                                No categories found. Add your first category above.
                            </div>
                            <div
                                v-for="category in categories"
                                :key="category.id"
                                class="flex items-center justify-between rounded-md bg-background/50 px-4 py-3 border border-white/10"
                            >
                                <div>
                                    <div class="font-medium text-foreground">{{ category.name }}</div>
                                    <div class="text-sm text-foreground/60">{{ category.posts_count }} posts</div>
                                </div>
                                <div class="flex space-x-2">
                                    <button
                                        type="button"
                                        class="text-red-400 hover:text-red-300"
                                        @click="deleteCategory(category.id)"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </GlassContainer>

                    <!-- Tags Section -->
                    <GlassContainer
                        variant="dark"
                        :withBorders="true"
                        :withCorners="true"
                        rounded="xl"
                        padding="lg"
                    >
                        <h3 class="text-lg font-semibold text-glow text-foreground mb-4">Tags</h3>

                        <!-- Add Tag Form -->
                        <form @submit.prevent="submitTag" class="mb-6">
                            <div class="flex space-x-2">
                                <FormInput
                                    v-model="tagForm.name"
                                    placeholder="New tag name"
                                    :error="tagForm.errors.name"
                                    class="flex-1"
                                />
                                <FormButton
                                    type="submit"
                                    variant="primary"
                                    :disabled="tagForm.processing"
                                >
                                    Add
                                </FormButton>
                            </div>
                        </form>

                        <!-- Tags List -->
                        <div class="flex flex-wrap gap-2">
                            <div v-if="!tags?.length" class="text-foreground/60 text-center py-4 w-full">
                                No tags found. Add your first tag above.
                            </div>
                            <div
                                v-for="tag in tags"
                                :key="tag.id"
                                class="flex items-center space-x-2 rounded-md bg-background/50 px-3 py-2 border border-white/10"
                            >
                                <span class="font-medium text-foreground">{{ tag.name }}</span>
                                <span class="text-xs text-foreground/60">({{ tag.posts_count }})</span>
                                <button
                                    type="button"
                                    class="text-red-400 hover:text-red-300 ml-2"
                                    @click="deleteTag(tag.id)"
                                >
                                    &times;
                                </button>
                            </div>
                        </div>
                    </GlassContainer>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
