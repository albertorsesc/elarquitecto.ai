<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import type { PageProps as InertiaPageProps } from '@inertiajs/core';
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

interface User {
  id: number;
  name: string;
  email: string;
}

interface PageProps extends InertiaPageProps {
  auth: {
    user: User | null;
  };
}

const page = usePage<PageProps>();
const isSearchActive = ref(false);
const searchQuery = ref('');

const props = withDefaults(defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
}>(), {
    breadcrumbs: () => [],
});

function activateSearch() {
  isSearchActive.value = true;
  setTimeout(() => {
    document.getElementById('spotlight-search')?.focus();
  }, 50);
}

function deactivateSearch() {
  if (searchQuery.value === '') {
    isSearchActive.value = false;
  }
}

function handleEscape(event: KeyboardEvent) {
  if (event.key === 'Escape') {
    searchQuery.value = '';
    isSearchActive.value = false;
  }
}
</script>

<template>
    <header class="glass-effect relative border-b border-white/10 bg-background/70 px-2 py-3 backdrop-blur-xl sm:py-4">
        <!-- Animated top border -->
        <div class="absolute inset-x-0 top-0 h-[1px]">
            <div class="absolute inset-0 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-30"></div>
        </div>

        <div class="mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-2 px-2">
            <!-- Left section with trigger and breadcrumbs -->
            <div class="flex items-center gap-2">
                <SidebarTrigger class="-ml-1" />
                <template v-if="props.breadcrumbs.length > 0">
                    <Breadcrumbs :breadcrumbs="props.breadcrumbs" />
                </template>
            </div>

            <!-- Search Bar -->
            <div class="order-3 mt-2 w-full sm:order-2 sm:mt-0 sm:w-auto sm:flex-1 sm:px-4 md:px-6">
                <div class="search-container group relative">
                    <input
                        type="text"
                        placeholder="Buscar contenido..."
                        v-model="searchQuery"
                        class="peer relative z-10 w-full rounded-xl border border-white/10 bg-background/50 py-1.5 pl-8 pr-4 text-sm text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                        @focus="activateSearch"
                    />
                    <div class="absolute inset-y-0 left-0 z-10 flex items-center pl-2.5 text-foreground/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    <!-- Animated border effect -->
                    <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>

                    <!-- Sliding neon lights (visible on focus) -->
                    <div class="pointer-events-none absolute -inset-1 opacity-0 group-focus-within:opacity-100">
                        <div class="absolute inset-x-0 top-0 h-[2px]">
                            <div class="absolute inset-0 animate-neon-slide-right-slow bg-gradient-to-r from-transparent via-primary to-transparent opacity-30"></div>
                        </div>
                        <div class="absolute right-0 top-0 h-full w-[2px]">
                            <div class="absolute inset-0 animate-neon-slide-down-slow bg-gradient-to-b from-transparent via-secondary to-transparent opacity-30"></div>
                        </div>
                        <div class="absolute bottom-0 inset-x-0 h-[2px]">
                            <div class="absolute inset-0 animate-neon-slide-left-slow bg-gradient-to-r from-transparent via-accent to-transparent opacity-30"></div>
                        </div>
                        <div class="absolute left-0 top-0 h-full w-[2px]">
                            <div class="absolute inset-0 animate-neon-slide-up-slow bg-gradient-to-b from-transparent via-cyan-400 to-transparent opacity-30"></div>
                        </div>
                    </div>

                    <!-- Corner accents -->
                    <div class="opacity-0 transition-opacity duration-300 group-focus-within:opacity-100">
                        <!-- Top Left Corner -->
                        <div class="absolute left-0 top-0 h-8 w-8">
                            <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
                            <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
                        </div>
                        <!-- Top Right Corner -->
                        <div class="absolute right-0 top-0 h-8 w-8">
                            <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
                            <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="order-2 flex items-center gap-1 sm:order-3 sm:gap-3">
                <Link
                    v-if="page.props.auth.user"
                    :href="route('dashboard')"
                    class="neon-border rounded bg-primary/10 px-2 py-1 text-xs font-semibold text-primary transition-all hover:bg-primary/20 sm:px-3 sm:py-1.5 sm:text-sm"
                >
                    Dashboard
                </Link>
                <template v-else>
<!--                    <Link
                        :href="route('login')"
                        class="px-1 text-xs text-foreground/80 transition-colors hover:text-primary sm:px-2 sm:text-sm"
                    >
                        Iniciar
                    </Link>-->
<!--                    <Link
                        :href="route('register')"
                        class="neon-border rounded bg-primary px-2 py-1 text-xs font-semibold text-white transition-all hover:bg-primary/80 sm:px-3 sm:py-1.5 sm:text-sm"
                    >
                        Registrarse
                    </Link>-->
                </template>
            </div>
        </div>
    </header>

    <!-- Spotlight Search Overlay -->
    <div
        v-if="isSearchActive"
        class="fixed inset-0 z-[60] flex items-start justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300"
        :class="isSearchActive ? 'opacity-100' : 'opacity-0 pointer-events-none'"
        @click="deactivateSearch"
    >
        <div
            class="mt-20 w-full max-w-2xl transform px-4 transition-all duration-300"
            :class="isSearchActive ? 'translate-y-0 opacity-100' : '-translate-y-10 opacity-0'"
            @click.stop
        >
            <div class="glass-effect relative overflow-hidden rounded-xl border border-white/20 bg-background/80 shadow-[0_0_30px_rgba(124,58,237,0.3)]">
                <!-- Animated corner accents -->
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

                <!-- Sliding neon lights -->
                <div class="pointer-events-none absolute -inset-1">
                    <!-- Top edge -->
                    <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right-slow bg-gradient-to-r from-transparent via-primary to-transparent opacity-50"></div>
                    <!-- Right edge -->
                    <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down-slow bg-gradient-to-b from-transparent via-secondary to-transparent opacity-50"></div>
                    <!-- Bottom edge -->
                    <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left-slow bg-gradient-to-r from-transparent via-accent to-transparent opacity-50"></div>
                    <!-- Left edge -->
                    <div class="absolute left-0 top-0 h-full w-[2px] animate-neon-slide-up-slow bg-gradient-to-b from-transparent via-cyan-400 to-transparent opacity-50"></div>
                </div>

                <!-- Search Input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 z-10 flex items-center pl-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input
                        id="spotlight-search"
                        v-model="searchQuery"
                        type="text"
                        placeholder="Buscar en El Arquitecto A.I..."
                        class="relative z-10 w-full border-b border-white/10 bg-transparent py-4 pl-12 pr-4 text-lg text-foreground placeholder:text-foreground/50 focus:outline-none"
                        @keydown="handleEscape"
                        @blur="deactivateSearch"
                    />
                    <div v-if="searchQuery" class="absolute inset-y-0 right-0 z-10 flex items-center pr-4">
                        <button
                            class="rounded-full p-1 text-foreground/50 hover:bg-white/10 hover:text-foreground"
                            @click="searchQuery = ''"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <!-- Animated border effect -->
                    <div class="absolute bottom-0 left-0 h-[1px] w-full bg-gradient-to-r from-primary via-cyan-400 to-secondary opacity-50"></div>
                </div>

                <!-- Search Results (placeholder) -->
                <div v-if="searchQuery" class="max-h-[60vh] overflow-y-auto p-4">
                    <div class="p-4 text-center">
                        <div class="relative mx-auto mb-4 h-8 w-8">
                            <div class="absolute inset-0 animate-spin rounded-full border-2 border-transparent border-t-cyan-400 opacity-70"></div>
                        </div>
                        <p class="text-sm text-foreground/70">Buscando "<span class="text-cyan-400">{{ searchQuery }}</span>"...</p>
                        <p class="mt-2 text-xs text-foreground/50">Presiona <span class="rounded border border-white/20 bg-white/5 px-1.5 py-0.5 text-[10px] font-mono">ESC</span> para cerrar</p>
                    </div>
                </div>
                <div v-else class="p-6 text-center">
                    <p class="text-sm text-foreground/70">Comienza a escribir para buscar</p>
                    <p class="mt-2 text-xs text-foreground/50">Presiona <span class="rounded border border-white/20 bg-white/5 px-1.5 py-0.5 text-[10px] font-mono">ESC</span> para cerrar</p>
                </div>
            </div>
        </div>
    </div>
</template>
