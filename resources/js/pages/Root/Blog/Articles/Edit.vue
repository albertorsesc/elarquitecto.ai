<script setup lang="ts">
import FloatingNeonLines from '@/components/theme/FloatingNeonLines.vue';
import FormButton from '@/components/theme/FormButton.vue';
import FormInput from '@/components/theme/FormInput.vue';
import FormTextarea from '@/components/theme/FormTextarea.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { MdEditor, config } from 'md-editor-v3';
import 'md-editor-v3/lib/style.css';
import { PropType, computed, reactive, ref } from 'vue';
// import theme css
import '@vavt/cm-extension/dist/previewTheme/arknights.css';
// import existing language
import { SharedData } from '@/types';
import es_ES from '@vavt/cm-extension/dist/locale/es-ES';

interface Article {
    id: number;
    title: string;
    content: string;
    excerpt: string;
    image: string | null;
    published_at: string | null;
    slug: string;
    author_id: number;
    created_at: string;
    updated_at: string;
}

config({
    editorConfig: {
        languageUserDefined: {
            'es-ES': es_ES,
        }
    }
})

const props = defineProps({
    article: {
        type: Object as PropType<Article>,
        required: true
    },
    categories: Array,
    tags: Array,
});

const page = usePage<SharedData>();
const errors = computed(() => page.props.errors || {});

const form = reactive({
    title: props.article.title,
    content: props.article.content,
    excerpt: props.article.excerpt,
    image: null as File | null,
    published_at: props.article.published_at,
    processing: false,
});

const preview = ref(props.article.image ? `/storage/${props.article.image}` : null);
const isPublished = ref(!!props.article.published_at);

// Handle the published state
const togglePublished = (event: Event) => {
    const target = event.target as HTMLInputElement;
    isPublished.value = target.checked;
    form.published_at = target.checked ? new Date().toISOString() : null;
};

const handleImageUpload = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        form.image = file;
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
    form.processing = true;

    // Prepare form data with proper typing
    const formData: Record<string, any> = {
        title: form.title,
        content: form.content,
        excerpt: form.excerpt,
        published_at: form.published_at,
        _method: 'put'
    };

    // Only include image if a new one was selected
    if (form.image) {
        formData.image = form.image;
    }

    router.post(route('root.articles.update', props.article.id), formData, {
        onSuccess: () => {
            router.visit(route('root.articles.index'));
        },
        forceFormData: true,
        preserveScroll: true,
    });

    form.processing = false;
};
</script>

<template>
    <Head title="Edit Article" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-glow text-foreground">
                    Edit Article
                </h2>
                <Link
                    :href="route('root.articles.index')"
                    class="rounded-md bg-background/80 px-4 py-2 text-sm font-medium text-foreground shadow-sm hover:bg-background/60 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 border border-white/10"
                >
                    Back to Articles
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
                            :error="errors.title"
                        />

                        <FormTextarea
                            id="excerpt"
                            v-model="form.excerpt"
                            label="Excerpt"
                            placeholder="Brief summary of the post"
                            :rows="3"
                            :error="errors.excerpt"
                        />

                        <FormTextarea
                            id="content"
                            v-model="form.content"
                            label="Content"
                            placeholder="Write your post content here..."
                            :rows="15"
                            :error="errors.content"
                        />
                        <p class="mt-1 text-sm text-foreground/70">
                            You can use HTML for formatting.
                        </p>

                        <MdEditor v-model="form.content" language="es-ES" />

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
                                <div v-if="errors.image" class="mt-1 text-sm text-red-400">
                                    {{ errors.image }}
                                </div>

                                <div v-if="preview" class="mt-4 relative">
                                    <img :src="preview" alt="Preview" class="max-h-64 rounded-md object-cover" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input
                                id="published"
                                type="checkbox"
                                :checked="isPublished"
                                @change="togglePublished($event)"
                                class="rounded border-white/20 bg-background/80 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                            />
                            <label for="published" class="ml-2 block text-sm/6 font-medium text-foreground">
                                Published
                            </label>
                            <div v-if="errors.published_at" class="mt-1 text-sm text-red-400">
                                {{ errors.published_at }}
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
                                Update Article
                            </FormButton>
                        </div>
                    </form>
                </GlassContainer>
            </div>
        </div>
    </AppLayout>
</template>
