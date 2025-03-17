<script setup lang="ts">
import SeoHead from '@/components/SeoHead.vue';
import FloatingNeonLines from '@/components/theme/FloatingNeonLines.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import NeonBorders from '@/components/theme/NeonBorders.vue';
import NeonEffects from '@/components/theme/NeonEffects.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Article } from '@/types/article';
import { Link, router, usePage } from '@inertiajs/vue3';
import { PropType, ref, watch } from 'vue';

const props = defineProps({
    articles: Object as PropType<Article[]>,
    filters: Object,
});

const search = ref(props.filters?.search || '');

watch(search, (value) => {
    if (value === null) {
        value = '';
    }

    if (value.length === 0 || value.length >= 3) {
        router.get(
            route('root.articles.index'),
            { search: value },
            { preserveState: true, replace: true }
        );
    }
});

const getImageUrl = (image: string) => {
    if (! image) {
        return 'https://images.unsplash.com/photo-1735825764451-d2186b7f4bf9?q=80&w=3270&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
    }

    if (image.includes('http')) {
        return image;
    }

    return `/storage/${image}`;
};

// Access SEO data from shared props
const page = usePage<{
  seo: {
    title: string;
    description: string;
    keywords: string;
    canonicalUrl: string;
    ogType: string;
    ogImage: string;
    twitterCard: string;
  };
}>();
const seo = page.props.seo;
</script>

<template>
    <SeoHead v-bind="seo" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-glow text-foreground">
                    Blog
                </h2>
            </div>
        </template>

        <!-- Background Effects -->
        <FloatingNeonLines variant="sparse" :opacity="0.2" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
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

                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 p-4">
                            <div
                                v-for="article in props.articles.data"
                                :key="article.id"
                                class="group relative overflow-hidden rounded-xl border border-white/10 bg-background/50 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:shadow-[0_0_30px_rgba(0,0,0,0.3)] shadow-[0_4px_20px_rgba(0,0,0,0.2)]"
                            >
                                <!-- Image container with effects -->
                                <div class="relative aspect-[16/9] overflow-hidden">
                                    <!-- Cyberpunk overlay effect -->
                                    <div class="absolute inset-0 z-10 bg-gradient-to-t from-background via-transparent to-transparent opacity-60"></div>
                                    <div class="absolute inset-0 z-10 bg-gradient-to-l from-primary/20 via-transparent to-secondary/20 opacity-30 mix-blend-overlay"></div>

                                    <!-- Glitch effect lines -->
                                    <div class="absolute inset-0 z-20 overflow-hidden opacity-0 mix-blend-screen transition-opacity duration-300 group-hover:opacity-30">
                                        <div class="absolute inset-x-0 top-1/4 h-[1px] animate-glitch-line-1 bg-cyan-400"></div>
                                        <div class="absolute inset-x-0 top-1/3 h-[1px] animate-glitch-line-2 bg-primary"></div>
                                        <div class="absolute inset-x-0 top-1/2 h-[1px] animate-glitch-line-3 bg-accent"></div>
                                    </div>

                                    <!-- Image with hover zoom -->
                                    <img
                                        :src="getImageUrl(article.image)"
                                        :alt="article.title"
                                        class="h-full w-full object-cover transition-transform duration-700 will-change-transform group-hover:scale-110"
                                    />

                                    <!-- Scanline effect -->
                                    <div class="absolute inset-0 z-30 bg-scanline opacity-10"></div>

                                    <!-- Category badge -->
                                    <!--                            <span class="absolute bottom-4 left-4 z-20 rounded bg-background/80 px-2 py-1 text-xs font-medium text-primary backdrop-blur-sm transition-colors duration-300 group-hover:bg-primary group-hover:text-white">
                                                                  {{ post.category }}
                                                                </span>-->
                                </div>

                                <div class="relative p-4">
                                    <!-- Content -->
                                    <h3 class="mb-2 text-lg font-semibold text-foreground transition-colors duration-300 group-hover:text-glow-multi">
                                        {{ article.title }}
                                    </h3>
                                    <p class="text-sm text-foreground/70">
                                        {{ article.excerpt }}
                                    </p>

                                    <!-- Hover effect overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-background/50 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                                </div>

                                <!-- Neon border effects -->
                                <div class="pointer-events-none absolute inset-0 rounded-xl">
                                    <!-- Multi-colored sliding neon lights -->
                                    <div class="absolute -inset-1 opacity-30 group-hover:opacity-100">
                                        <!-- Top edge -->
                                        <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right-color bg-gradient-to-r from-transparent via-[#FF1CF7] to-transparent"></div>
                                        <!-- Right edge -->
                                        <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down-color bg-gradient-to-b from-transparent via-[#00FFE1] to-transparent"></div>
                                        <!-- Bottom edge -->
                                        <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left-color bg-gradient-to-r from-transparent via-[#01FF88] to-transparent"></div>
                                        <!-- Left edge -->
                                        <div class="absolute left-0 top-0 h-full w-[2px] animate-neon-slide-up-color bg-gradient-to-b from-transparent via-[#5B6EF7] to-transparent"></div>
                                    </div>
                                </div>

                                <!-- Link overlay -->
                                <Link :href="route('blog.show', article)" class="absolute inset-0">
                                    <span class="sr-only">Leer más sobre {{ article.title }}</span>
                                </Link>
                            </div>
                        </div>
                    </GlassContainer>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
