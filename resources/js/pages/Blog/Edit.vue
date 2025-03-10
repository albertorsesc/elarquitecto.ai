<script setup lang="ts">
import FloatingNeonLines from '@/components/theme/FloatingNeonLines.vue';
import FormButton from '@/components/theme/FormButton.vue';
import FormInput from '@/components/theme/FormInput.vue';
import FormTextarea from '@/components/theme/FormTextarea.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    post: Object,
    categories: Array,
    tags: Array,
});

const form = useForm({
    title: props.post?.title || '',
    content: props.post?.content || '',
    excerpt: props.post?.excerpt || '',
    category_id: props.post?.category_id || '',
    tags: props.post?.tags?.map(tag => tag.id) || [],
    featured_image: null,
    published: props.post?.published || false,
    _method: 'PUT',
});

const preview = ref(props.post?.featured_image ? `/storage/${props.post.featured_image}` : null);

const handleImageUpload = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        const file = target.files[0];
        form.featured_image = file;

        const reader = new FileReader();
        reader.onload = (e) => {
            if (e.target?.result) {
                preview.value = e.target.result as string;
            }
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    if (props.post?.id) {
        form.post(route('blog.update', props.post.id), {
            onSuccess: () => {
                form.featured_image = null;
            },
        });
    }
};
</script>

<template>
    <Head title="Edit Blog Post" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-glow text-foreground">
                    Edit Blog Post
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
                <GlassContainer
                    variant="dark"
                    :withBorders="true"
                    :withCorners="true"
                    rounded="xl"
                    padding="lg"
                >
                    <form @submit.prevent="submit" class="space-y-6">
                        <FormInput
                            id="title"
                            v-model="form.title"
                            label="Title"
                            placeholder="Enter post title"
                            :error="form.errors.title"
                        />

                        <FormTextarea
                            id="excerpt"
                            v-model="form.excerpt"
                            label="Excerpt"
                            placeholder="Brief summary of the post"
                            :rows="3"
                            :error="form.errors.excerpt"
                        />

                        <FormTextarea
                            id="content"
                            v-model="form.content"
                            label="Content"
                            placeholder="Write your post content here..."
                            :rows="15"
                            :error="form.errors.content"
                        />
                        <p class="mt-1 text-sm text-foreground/70">
                            You can use HTML for formatting.
                        </p>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label class="block text-sm/6 font-medium text-foreground">Category</label>
                                <div class="relative mt-2">
                                    <select
                                        id="category"
                                        v-model="form.category_id"
                                        class="block w-full rounded-md bg-background/80 px-3 py-1.5 text-base text-foreground outline outline-1 -outline-offset-1 outline-white/20 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-primary sm:text-sm/6 backdrop-blur-sm"
                                    >
                                        <option value="">Select Category</option>
                                        <option
                                            v-for="category in categories"
                                            :key="category.id"
                                            :value="category.id"
                                        >
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.category_id" class="mt-1 text-sm text-red-400">
                                        {{ form.errors.category_id }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm/6 font-medium text-foreground">Tags</label>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <div
                                        v-for="tag in tags"
                                        :key="tag.id"
                                        class="flex items-center space-x-2 rounded-md border border-white/20 bg-background/80 px-3 py-2 backdrop-blur-sm"
                                    >
                                        <input
                                            type="checkbox"
                                            :id="`tag-${tag.id}`"
                                            :value="tag.id"
                                            v-model="form.tags"
                                            class="rounded border-white/20 bg-background/80 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                        />
                                        <label :for="`tag-${tag.id}`" class="text-sm text-foreground">
                                            {{ tag.name }}
                                        </label>
                                    </div>
                                </div>
                                <div v-if="form.errors.tags" class="mt-1 text-sm text-red-400">
                                    {{ form.errors.tags }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm/6 font-medium text-foreground">Featured Image</label>
                            <div class="relative mt-2">
                                <div class="flex items-center rounded-md bg-background/80 px-3 py-2 outline outline-1 -outline-offset-1 outline-white/20 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-primary">
                                    <input
                                        type="file"
                                        id="featured_image"
                                        @change="handleImageUpload"
                                        accept="image/*"
                                        class="block w-full bg-transparent text-foreground file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary/90"
                                    />
                                </div>
                                <div v-if="form.errors.featured_image" class="mt-1 text-sm text-red-400">
                                    {{ form.errors.featured_image }}
                                </div>

                                <div v-if="preview" class="mt-4">
                                    <img :src="preview" alt="Preview" class="max-h-64 rounded-md object-cover" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input
                                id="published"
                                type="checkbox"
                                v-model="form.published"
                                class="rounded border-white/20 bg-background/80 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                            />
                            <label for="published" class="ml-2 block text-sm/6 font-medium text-foreground">
                                Published
                            </label>
                            <div v-if="form.errors.published" class="mt-1 text-sm text-red-400">
                                {{ form.errors.published }}
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <FormButton
                                type="button"
                                variant="outline"
                                @click="router.visit(route('root.articles.index'))"
                            >
                                Cancel
                            </FormButton>
                            <FormButton
                                type="submit"
                                variant="primary"
                                :disabled="form.processing"
                            >
                                Update Post
                            </FormButton>
                        </div>
                    </form>
                </GlassContainer>
            </div>
        </div>
    </AppLayout>
</template>
