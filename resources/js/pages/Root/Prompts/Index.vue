<script setup lang="ts">
import FloatingNeonLines from '@/components/theme/FloatingNeonLines.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import NeonBorders from '@/components/theme/NeonBorders.vue';
import NeonEffects from '@/components/theme/NeonEffects.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Prompt } from '@/types/prompt';
import { Head, router, Link } from '@inertiajs/vue3';
import { PropType, ref, watch } from 'vue';

const props = defineProps({
    prompts: Object as PropType<Prompt[]>,
    filters: Object,
});

const search = ref(props.filters?.search || '');

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
</script>

<template>
    <Head title="Prompts" />

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
                        :variant="$page.props.darkMode ? 'dark' : 'light'"
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

                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-800 px-4">
                          <li v-for="prompt in props.prompts"
                              :key="prompt.id"
                              class="cursor-pointer flex flex-wrap items-center justify-between gap-x-6 gap-y-4 py-5 sm:flex-nowrap">
                            <Link :href="route('prompts.show', prompt)">
                              <div>
                              <div class="mt-1 flex items-center gap-x-2 text-xs/5 text-gray-500 dark:text-gray-400">
                                <div>
                                  <p class="text-lg font-semibold text-primary">
                                  {{ prompt.name }}
                                </p>
                                <p>
                                  {{ prompt.author?.name || 'Anonymous' }}
                                </p>
                                <svg viewBox="0 0 2 2" class="size-0.5 fill-current">
                                  <circle cx="1" cy="1" r="1" />
                                </svg>
                                <p><time :datetime="prompt.created_at">{{ new Date(prompt.created_at).toLocaleDateString() }}</time></p>
                                </div>
                              </div>
                              </div>
                            </Link>
                            <dl class="flex w-full flex-none justify-between gap-x-8 sm:w-auto">
                              <div class="flex -space-x-0.5">
                                <dt class="sr-only">Users</dt>
                                <!-- We could replace this with actual user avatars if available -->
                                <dd v-for="i in 3" :key="i">
                                  <div class="size-6 rounded-full bg-primary/20 ring-2 ring-white dark:ring-gray-800 flex items-center justify-center text-xs text-primary-foreground">
                                    {{ i }}
                                  </div>
                                </dd>
                              </div>
                              <div class="flex w-16 gap-x-2.5">
                                <dt>
                                  <span class="sr-only">Usage count</span>
                                  <svg class="size-6 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                  </svg>
                                </dt>
                                <dd class="text-sm/6 text-gray-900 dark:text-gray-100">{{ prompt.usage_count || 0 }}</dd>
                              </div>
                            </dl>
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
