<script setup lang="ts">
import SeoHead from '@/components/SeoHead.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import NeonBorders from '@/components/theme/NeonBorders.vue';
import NeonEffects from '@/components/theme/NeonEffects.vue';
import { useAssetUrl } from '@/composables/useAssetUrl';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Article } from '@/types/article';
import type { PageProps as InertiaPageProps } from '@inertiajs/core';
import { Link, usePage } from '@inertiajs/vue3';
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

// Define the type for page props
interface PageProps extends InertiaPageProps {
    ziggy?: {
        location?: string;
    };
    seo: {
        title: string;
        description: string;
        keywords: string;
        canonicalUrl: string;
        ogType: string;
        ogImage: string;
        twitterCard: string;
    };
}

const page = usePage<PageProps>();

// Compute the article image URL
const articleImageUrl = computed(() => {
    return getAssetUrl(props.article?.image || null);
});

// Compute the current article URL for sharing
const articleUrl = computed(() => {
    return page.props.ziggy?.location || '';
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

// Access SEO data from shared props
const seo = page.props.seo;
</script>

<template>
    <SeoHead v-bind="seo" />

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
                        <div v-if="articleImageUrl" class="relative w-full aspect-[18/5] mb-8 rounded-lg overflow-hidden">
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

                        <!-- Social Sharing -->
                        <div class="mt-8 flex items-center gap-4">
                            <span class="text-sm text-foreground/60">Compartir:</span>
                            <div class="flex gap-2">
                                <!-- Facebook -->
                                <a
                                    :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(articleUrl)}`"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="rounded-full bg-primary/10 p-2 text-primary transition-all hover:bg-primary/20"
                                    title="Compartir en Facebook"
                                >
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                                    </svg>
                                </a>

                                <!-- Twitter/X -->
                                <a
                                    :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(articleUrl)}&text=${encodeURIComponent(props.article?.title || '')}`"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="rounded-full bg-primary/10 p-2 text-primary transition-all hover:bg-primary/20"
                                    title="Compartir en Twitter/X"
                                >
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                                    </svg>
                                </a>

                                <!-- Instagram (Note: Instagram doesn't support direct sharing, so this opens Instagram) -->
                                <a
                                    href="https://www.instagram.com/"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="rounded-full bg-primary/10 p-2 text-primary transition-all hover:bg-primary/20"
                                    title="Visitar Instagram"
                                >
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153.509.5.902 1.105 1.153 1.772.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 01-1.153 1.772c-.5.509-1.105.902-1.772 1.153-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 01-1.772-1.153 4.904 4.904 0 01-1.153-1.772c-.247-.637-.415-1.363-.465-2.428-.047-1.066-.06-1.405-.06-4.122 0-2.717.01-3.056.06-4.122.05-1.065.218-1.79.465-2.428.254-.66.598-1.216 1.153-1.772.5-.509 1.105-.902 1.772-1.153.637-.247 1.363-.415 2.428-.465C8.944 2.013 9.283 2 12 2zm0 1.802c-2.67 0-2.986.01-4.04.059-.976.045-1.505.207-1.858.344-.466.181-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.048 1.055-.058 1.37-.058 4.04 0 2.67.01 2.986.058 4.04.045.976.207 1.505.344 1.858.181.466.398.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.04.058 2.67 0 2.987-.01 4.04-.058.976-.045 1.505-.207 1.858-.344.466-.181.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.04 0-2.67-.01-2.986-.058-4.04-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.055-.048-1.37-.058-4.04-.058zm0 3.063a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 8.468a3.333 3.333 0 100-6.666 3.333 3.333 0 000 6.666zm6.538-8.469a1.2 1.2 0 11-2.4 0 1.2 1.2 0 012.4 0z"></path>
                                    </svg>
                                </a>

                                <!-- LinkedIn -->
                                <a
                                    :href="`https://www.linkedin.com/shareArticle?mini=true&url=${encodeURIComponent(articleUrl)}&title=${encodeURIComponent(props.article?.title || '')}&summary=${encodeURIComponent(props.article?.excerpt || props.meta.description || '')}`"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="rounded-full bg-primary/10 p-2 text-primary transition-all hover:bg-primary/20"
                                    title="Compartir en LinkedIn"
                                >
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                                    </svg>
                                </a>
                            </div>
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
