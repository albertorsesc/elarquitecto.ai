<script setup lang="ts">
import GlassContainer from '@/components/theme/GlassContainer.vue';
import NeonBorders from '@/components/theme/NeonBorders.vue';
import NeonEffects from '@/components/theme/NeonEffects.vue';
import { useAssetUrl } from '@/composables/useAssetUrl';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Article } from '@/types/article';
import { Head, Link } from '@inertiajs/vue3';
import { MdPreview } from 'md-editor-v3';
import 'md-editor-v3/lib/preview.css';
import { computed, nextTick, onBeforeUnmount, onMounted, PropType, ref } from 'vue';

const props = defineProps({
    article: Object as PropType<Article>,
    breadcrumbs: {
        type: Array as PropType<BreadcrumbItem[]>,
        required: true
    },
    meta: {
        type: Object as PropType<{
            title: string;
            description: string;
            ogImage: string | null;
        }>,
        required: true
    }
});

const { getAssetUrl } = useAssetUrl();

// Compute the article image URL
const articleImageUrl = computed(() => {
    return getAssetUrl(props.article?.image || null);
});

// Create a ref for the scroll element
const scrollElement = ref<HTMLElement | null>(null);
const previewId = 'article-preview';
const contentContainer = ref<HTMLElement | null>(null);
const previewContainer = ref<HTMLElement | null>(null);

// Set the scroll element after the component is mounted
onMounted(() => {
    scrollElement.value = document.documentElement;

    // Wait for the component to fully render
    nextTick(() => {
        // Find the preview container
        previewContainer.value = document.getElementById(previewId);

        // Add a global click handler to capture all clicks
        document.addEventListener('click', handleGlobalClick);

        // Add custom class to all anchor links for easier selection
        if (previewContainer.value) {
            const links = previewContainer.value.querySelectorAll('a[href^="#"]');
            links.forEach(link => {
                link.classList.add('md-anchor-link');
                // Prevent default behavior
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                });
            });
        }
    });
});

onBeforeUnmount(() => {
    // Clean up event listeners
    document.removeEventListener('click', handleGlobalClick);
});

// Global click handler to catch all clicks
const handleGlobalClick = (event: MouseEvent) => {
    const target = event.target as HTMLElement;

    // Find if we clicked on an anchor or its child
    let anchorElement = target;
    while (anchorElement && anchorElement.tagName !== 'A') {
        if (anchorElement.parentElement) {
            anchorElement = anchorElement.parentElement;
        } else {
            return; // Not an anchor or its child
        }
    }

    // Check if it's a hash link
    if (anchorElement.tagName === 'A' && anchorElement.getAttribute('href')?.startsWith('#')) {
        event.preventDefault();

        const href = anchorElement.getAttribute('href');
        if (!href) return;

        const targetId = href.substring(1);

        // Try different methods to find the target
        let targetElement: HTMLElement | null = null;

        // 1. Direct ID match
        targetElement = document.getElementById(targetId);

        // 2. Try to find by data-id attribute (md-editor-v3 specific)
        if (!targetElement) {
            targetElement = document.querySelector(`[data-id="${targetId}"]`);
        }

        // 3. Try to find by name
        if (!targetElement) {
            targetElement = document.querySelector(`[name="${targetId}"]`);
        }

        // 4. Try to find by heading content
        if (!targetElement && previewContainer.value) {
            const headings = previewContainer.value.querySelectorAll('h1, h2, h3, h4, h5, h6');
            for (let i = 0; i < headings.length; i++) {
                const heading = headings[i];
                const headingText = heading.textContent || '';
                const headingId = headingText.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-');

                if (headingId === targetId) {
                    targetElement = heading as HTMLElement;
                    break;
                }
            }
        }

        // If we found the target, scroll to it
        if (targetElement && contentContainer.value) {
            // Calculate the scroll position
            const containerRect = contentContainer.value.getBoundingClientRect();
            const targetRect = targetElement.getBoundingClientRect();

            // Scroll with offset
            const scrollTop = contentContainer.value.scrollTop + (targetRect.top - containerRect.top) - 100;

            // Smooth scroll
            contentContainer.value.scrollTo({
                top: scrollTop,
                behavior: 'smooth'
            });
        }
    }
};

// Custom styles for the preview
const previewStyle = {
    padding: '1rem',
    maxWidth: '100%',
    margin: '0 auto',
};

// Handle when preview is ready
const onPreviewReady = () => {
    nextTick(() => {
        // Re-initialize after preview is ready
        previewContainer.value = document.getElementById(previewId);

        if (previewContainer.value) {
            // Add custom class to all anchor links
            const links = previewContainer.value.querySelectorAll('a[href^="#"]');
            links.forEach(link => {
                link.classList.add('md-anchor-link');
            });
        }
    });
};
</script>

<template>
    <Head>
        <title>{{ meta.title }}</title>
        <meta name="description" :content="meta.description">
        <meta property="og:title" :content="meta.title">
        <meta property="og:description" :content="meta.description">
        <meta v-if="meta.ogImage" property="og:image" :content="meta.ogImage">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" :content="meta.title">
        <meta name="twitter:description" :content="meta.description">
        <meta v-if="meta.ogImage" name="twitter:image" :content="meta.ogImage">
    </Head>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 h-full">
            <GlassContainer
                variant="dark"
                :withBorders="true"
                :withCorners="true"
                rounded="xl"
                padding="none"
                class="relative flex-1 overflow-hidden"
            >
                <!-- Animated corner accents -->
                <NeonBorders/>
                <NeonEffects/>

                <div ref="contentContainer" class="flex flex-col gap-4 p-6 h-full overflow-y-auto">
                    <article class="max-w-4xl mx-auto w-full">
                        <!-- Article Header -->
                        <header class="mb-8">
                            <h1 class="mb-4 text-3xl font-bold text-glow sm:text-4xl">{{ article?.title }}</h1>

                            <!-- Article Meta -->
                            <div class="flex flex-wrap items-center gap-4 text-sm text-foreground/60">
                                <div v-if="article?.category" class="flex items-center gap-1">
                                    <Link :href="route('blog.category', article.category.slug)" class="hover:text-primary">
                                        {{ article.category.name }}
                                    </Link>
                                </div>

                                <div v-if="article?.published_at" class="flex items-center gap-1">
                                    <time :datetime="article.published_at">
                                        {{ new Date(article.published_at).toLocaleDateString('es-ES', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        }) }}
                                    </time>
                                </div>

                                <div v-if="article?.author" class="flex items-center gap-1">
                                    Por {{ article.author.name }}
                                </div>
                            </div>
                        </header>

                        <!-- Article Image -->
                        <div v-if="articleImageUrl" class="relative w-full aspect-[21/9] mb-8 rounded-lg overflow-hidden">
                            <img
                                :src="articleImageUrl"
                                :alt="article?.title || ''"
                                class="absolute inset-0 w-full h-full object-cover"
                            />
                            <!-- Add a subtle gradient overlay -->
                            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-background/20"></div>
                        </div>

                        <!-- Article Content -->
                        <div class="prose prose-invert max-w-none">
                            <MdPreview
                                :id="previewId"
                                :modelValue="article?.content || ''"
                                :style="previewStyle"
                                previewTheme="github"
                                theme="dark"
                                language="es-ES"
                                @onReadyPreview="onPreviewReady"
                            />
                        </div>

                        <!-- Article Tags -->
                        <div v-if="article?.tags?.length" class="mt-8 flex flex-wrap gap-2">
                            <Link
                                v-for="tag in article?.tags || []"
                                :key="tag.id"
                                :href="route('blog.tag', tag.slug)"
                                class="rounded-full bg-primary/10 px-3 py-1 text-sm text-primary hover:bg-primary/20"
                            >
                                {{ tag.name }}
                            </Link>
                        </div>
                    </article>
                </div>
            </GlassContainer>
        </div>
    </AppLayout>
</template>

<style>
/* Override markdown preview styles for dark theme */
.md-editor-dark {
    --md-bk-color: transparent !important;
}

.md-editor-dark .wmde-markdown {
    background-color: transparent;
    color: #e4e4e7;
}

/* Adjust code block styling */
.md-editor-dark .wmde-markdown pre {
    background-color: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Adjust inline code styling */
.md-editor-dark .wmde-markdown code {
    background-color: rgba(0, 0, 0, 0.2);
    color: #e4e4e7;
}

/* Adjust headings */
.md-editor-dark .wmde-markdown h1,
.md-editor-dark .wmde-markdown h2,
.md-editor-dark .wmde-markdown h3,
.md-editor-dark .wmde-markdown h4,
.md-editor-dark .wmde-markdown h5,
.md-editor-dark .wmde-markdown h6 {
    color: #e4e4e7;
    border-bottom-color: rgba(255, 255, 255, 0.1);
}

/* Adjust links */
.md-editor-dark .wmde-markdown a {
    color: #60a5fa;
}

/* Adjust blockquotes */
.md-editor-dark .wmde-markdown blockquote {
    border-left-color: rgba(255, 255, 255, 0.2);
    color: #a1a1aa;
}

/* Adjust tables */
.md-editor-dark .wmde-markdown table th,
.md-editor-dark .wmde-markdown table td {
    border-color: rgba(255, 255, 255, 0.1);
}

/* Add a highlight effect to anchor links */
.md-anchor-link {
    position: relative;
    transition: all 0.2s ease;
}

.md-anchor-link:hover {
    text-decoration: underline;
}
</style>
