<script setup lang="ts">
import GlassContainer from '@/components/theme/GlassContainer.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import NeonBorders from '@/components/theme/NeonBorders.vue';
import NeonEffects from '@/components/theme/NeonEffects.vue';
import { PropType } from 'vue';
import { MdPreview } from 'md-editor-v3';
import 'md-editor-v3/lib/preview.css';

defineProps({
    article: Object as PropType<Article>,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Article',
        href: '/root/articles/show',
    },
];

// format image URL for when it's in storage or S3
const getImageUrl = (image: string) => {
    console.log('image', image);
    if (! image) {
        return 'https://images.unsplash.com/photo-1735825764451-d2186b7f4bf9?q=80&w=3270&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
    }

    if (image.includes('http')) {
        return image;
    }

    return `/storage/${image}`;
};

const scrollElement = document.documentElement;
</script>

<template>
    <Head title="Article" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-end items-center pb-2">
            <div class="flex justify-between items-center gap-x-8">
                <Link
                    :href="route('root.articles.edit', article)"
                    class="neon-border rounded bg-primary/10 px-2 py-1 text-xs font-semibold text-primary transition-all hover:bg-primary/20 sm:px-3 sm:py-1.5 sm:text-sm"
                >
                    Editar
                </Link>
                <Link
                    :href="route('root.articles.index')"
                    class="neon-border rounded bg-primary/10 px-2 py-1 text-xs font-semibold text-primary transition-all hover:bg-primary/20 sm:px-3 sm:py-1.5 sm:text-sm"
                >
                    Regresar a los artículos
                </Link>
            </div>
        </div>

        <div class="flex h-full flex-1 flex-col gap-4">
            <GlassContainer
                variant="dark"
                :withBorders="true"
                :withCorners="true"
                rounded="xl"
                padding="none"
                class="relative min-h-[60vh] flex-1 md:min-h-min"
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

                <div class="flex flex-col gap-4 p-6">
                    <MdPreview
                        :modelValue="article!.content"
                        previewTheme="cyanosis"
                        language="es-ES"
                    />
                </div>
            </GlassContainer>
        </div>
    </AppLayout>
</template>
