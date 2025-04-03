<script setup lang="ts">
import SeoHead from '@/components/SeoHead.vue';
import FloatingNeonLines from '@/components/theme/FloatingNeonLines.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import NeonBorders from '@/components/theme/NeonBorders.vue';
import NeonEffects from '@/components/theme/NeonEffects.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Prompt } from '@/types/prompt';
import { Link, router, usePage } from '@inertiajs/vue3';
import { PropType, ref, watch } from 'vue';

const props = defineProps({
    prompts: Object as PropType<Prompt[]>,
    filters: Object,
});

const search = ref(props.filters?.search || '');

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
  darkMode: boolean;
}>();
const seo = page.props.seo;

watch(search, (value) => {
    if (value === null) {
        value = '';
    }

    if (value.length === 0 || value.length >= 3) {
        router.get(
            route('root.prompts.index'),
            { search: value },
            { preserveState: true, replace: true }
        );
    }
});

// Function to truncate description text to prevent overflow
const truncateText = (text: string, maxLength: number = 120): string => {
  if (!text) return '';
  if (text.length <= maxLength) return text;
  return text.substring(0, maxLength) + '...';
};

// Function to safely get usage count (handles missing property)
const getUsageCount = (prompt: Prompt): number => {
  // @ts-expect-error - usage_count might not be defined in the type yet
  return prompt.usage_count || 0;
};
</script>

<template>
    <SeoHead v-bind="seo" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-glow dark:text-foreground text-gray-900">
                    Prompts
                </h2>
            </div>
        </template>

        <!-- Background Effects -->
        <FloatingNeonLines variant="sparse" :opacity="0.2" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex h-full flex-1 flex-col gap-4">
                    <GlassContainer
                        :variant="page.props.darkMode ? 'dark' : 'light'"
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

                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-800">
                          <li v-for="prompt in props.prompts"
                              :key="prompt.id"
                              class="cursor-pointer flex flex-wrap items-center gap-x-6 gap-y-4 py-6 sm:py-7 sm:flex-nowrap relative group hover:bg-primary/5 transition-colors rounded-lg w-full px-4 overflow-hidden">
                            <!-- Subtle neon glow on hover (expanded to cover full width) -->
                            <div class="absolute inset-0 -mx-4 opacity-0 group-hover:opacity-20 transition-opacity bg-gradient-to-r from-primary/20 via-transparent to-secondary/20 pointer-events-none"></div>

                            <Link :href="route('prompts.show', prompt)" class="flex flex-1 z-10 w-full">
                              <div class="grid grid-cols-1 sm:grid-cols-4 w-full gap-4">
                                <!-- Left section: Title, Author, Date -->
                                <div class="flex flex-col sm:col-span-1 justify-center">
                                  <p class="text-lg font-semibold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary truncate">
                                    {{ prompt.name }}
                                  </p>
                                  <div class="flex items-center text-xs/5 text-gray-500 dark:text-gray-400 mt-1">
                                    <p class="truncate">
                                      {{ prompt.author?.name || 'Anonymous' }}
                                    </p>
                                    <svg viewBox="0 0 2 2" class="size-0.5 fill-current mx-2 flex-shrink-0">
                                      <circle cx="1" cy="1" r="1" />
                                    </svg>
                                    <p class="whitespace-nowrap"><time :datetime="prompt.created_at">{{ new Date(prompt.created_at).toLocaleDateString() }}</time></p>
                                  </div>
                                </div>

                                <!-- Middle section: Description excerpt -->
                                <div class="sm:col-span-2 flex items-center">
                                  <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 leading-relaxed">
                                    {{ truncateText(prompt.description) }}
                                  </p>
                                </div>
                              </div>
                            </Link>

                            <dl class="flex items-center gap-x-6 flex-shrink-0 hidden">
                              <div class="flex -space-x-2">
                                <dt class="sr-only">Users</dt>
                                <!-- We could replace this with actual user avatars if available -->
                                <dd v-for="i in 3" :key="i">
                                  <div class="size-8 rounded-full bg-primary/20 ring-2 ring-white dark:ring-gray-800 flex items-center justify-center text-xs text-primary-foreground">
                                    {{ i }}
                                  </div>
                                </dd>
                              </div>
                              <div class="flex items-center gap-x-2">
                                <dt>
                                  <span class="sr-only">Usage count</span>
                                  <svg class="size-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                  </svg>
                                </dt>
                                <dd class="font-medium text-gray-900 dark:text-gray-100">{{ getUsageCount(prompt) }}</dd>
                              </div>
                            </dl>

                            <!-- Neon border glow effect (expanded to cover full width) -->
                            <div class="absolute inset-x-0 -mx-4 inset-y-0 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                              <div class="absolute inset-0 border border-primary/30 shadow-[0_0_10px_1px_rgba(var(--primary-rgb),0.2)]"></div>
                            </div>
                          </li>

                          <!-- Empty state when no prompts are available -->
                          <li v-if="!props.prompts || props.prompts.length === 0" class="py-10 text-center">
                            <p class="text-gray-500 dark:text-gray-400">No prompts found</p>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search or create a new prompt.</p>
                          </li>
                        </ul>
                    </GlassContainer>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
