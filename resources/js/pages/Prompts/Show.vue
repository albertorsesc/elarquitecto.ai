<script setup lang="ts">
import GlassContainer from '@/components/theme/GlassContainer.vue';
import NeonBorders from '@/components/theme/NeonBorders.vue';
import NeonEffects from '@/components/theme/NeonEffects.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Prompt } from '@/types/prompt';
import { Head, Link } from '@inertiajs/vue3';
import { MdPreview } from 'md-editor-v3';
import 'md-editor-v3/lib/preview.css';
import { nextTick, onBeforeUnmount, onMounted, PropType, ref } from 'vue';

const props = defineProps({
    prompt: Object as PropType<Prompt>,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Prompt',
        href: '/prompts/show',
    },
];

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
        console.log('Trying to scroll to:', targetId);

        // Try different methods to find the target
        let targetElement: HTMLElement | null = null;

        // 1. Direct ID match
        targetElement = document.getElementById(targetId);
        console.log('By ID:', targetElement);

        // 2. Try to find by data-id attribute (md-editor-v3 specific)
        if (!targetElement) {
            targetElement = document.querySelector(`[data-id="${targetId}"]`);
            console.log('By data-id:', targetElement);
        }

        // 3. Try to find by name
        if (!targetElement) {
            targetElement = document.querySelector(`[name="${targetId}"]`);
            console.log('By name:', targetElement);
        }

        // 4. Try to find by heading content
        if (!targetElement && previewContainer.value) {
            const headings = previewContainer.value.querySelectorAll('h1, h2, h3, h4, h5, h6');
            for (let i = 0; i < headings.length; i++) {
                const heading = headings[i];
                const headingText = heading.textContent || '';
                const headingId = headingText.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-');

                console.log('Comparing:', headingId, 'with', targetId);
                if (headingId === targetId) {
                    targetElement = heading as HTMLElement;
                    console.log('Found by heading text:', targetElement);
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
        } else {
            console.log('Target element not found');
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
                console.log('Added class to link:', link);
            });
        }
    });
};
</script>

<template>
    <Head :title="'Prompt: ' + prompt!.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-end items-center pb-2">
            <div class="flex justify-between items-center gap-x-8">
                <Link
                    :href="route('root.prompts.index')"
                    class="neon-border rounded bg-primary/10 px-2 py-1 text-xs font-semibold text-primary transition-all hover:bg-primary/20 sm:px-3 sm:py-1.5 sm:text-sm"
                >
                    Regresar a los prompts
                </Link>
            </div>
        </div>

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

                <div class="absolute left-0 top-0 h-12 w-12 animate-pulse-slow">
                    <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
                    <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
                </div>
                <div class="absolute right-0 top-0 h-12 w-12 animate-pulse-slow">
                    <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
                    <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
                </div>
                <div class="absolute bottom-0 left-0 h-12 w-12 animate-pulse-slow">
                    <div class="absolute bottom-0 left-0 h-full w-[1px] animate-glow bg-gradient-to-t from-secondary via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 h-[1px] w-full animate-glow bg-gradient-to-r from-secondary via-transparent to-transparent"></div>
                </div>
                <div class="absolute bottom-0 right-0 h-12 w-12 animate-pulse-slow">
                    <div class="absolute bottom-0 right-0 h-full w-[1px] animate-glow bg-gradient-to-t from-accent via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 right-0 h-[1px] w-full animate-glow bg-gradient-to-l from-accent via-transparent to-transparent"></div>
                </div>

                <!-- Sliding neon lines inside the container -->
                <div class="pointer-events-none absolute -inset-1">
                    <!-- Top edge -->
                    <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right-slow bg-gradient-to-r from-transparent via-primary to-transparent opacity-30"></div>
                    <!-- Right edge -->
                    <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down-slow bg-gradient-to-b from-transparent via-secondary to-transparent opacity-30"></div>
                    <!-- Bottom edge -->
                    <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left-slow bg-gradient-to-r from-transparent via-accent to-transparent opacity-30"></div>
                    <!-- Left edge -->
                    <div class="absolute left-0 top-0 h-full w-[2px] animate-neon-slide-up-slow bg-gradient-to-b from-transparent via-cyan-400 to-transparent opacity-30"></div>
                </div>

                <div ref="contentContainer" class="flex flex-col gap-4 p-6 h-full overflow-y-auto">
                    <div class="max-w-4xl mx-auto w-full">
                        <MdPreview
                            :id="previewId"
                            :modelValue="prompt!.content"
                            :style="previewStyle"
                            previewTheme="github"
                            theme="light"
                            language="es-ES"
                            @onReadyPreview="onPreviewReady"
                        />
                    </div>
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
