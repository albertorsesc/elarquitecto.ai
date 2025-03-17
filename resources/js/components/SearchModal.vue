<script setup lang="ts">
import { Article } from '@/types/article';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

defineProps<{
  isActive: boolean;
}>();

const emit = defineEmits(['close']);

const searchQuery = ref('');
const isLoading = ref(false);
const searchResults = ref<Article[]>([]);

function handleEscape(event: KeyboardEvent) {
  if (event.key === 'Escape') {
    searchQuery.value = '';
    emit('close');
  }
}

function clearSearch() {
  searchQuery.value = '';
  searchResults.value = [];
}

function deactivateSearch() {
  if (searchQuery.value === '') {
    emit('close');
  }
}

// Perform search when query changes
watch(searchQuery, async (newQuery) => {
  if (newQuery.length < 2) {
    searchResults.value = [];
    return;
  }

  isLoading.value = true;

  try {
    // Use Inertia.js to fetch search results
    router.visit(`/search?query=${encodeURIComponent(newQuery)}`, {
      method: 'get',
      preserveState: true,
      preserveScroll: true,
      only: ['articles'],
      onSuccess: (page) => {
        const articles = page.props.articles as Article[];
        searchResults.value = articles || [];
        isLoading.value = false;
      },
      onFinish: () => {
        isLoading.value = false;
      }
    });
  } catch (error) {
    console.error('Search error:', error);
    searchResults.value = [];
    isLoading.value = false;
  }
}, { deep: false });

function goToArticle(slug: string) {
  router.visit(`/blog/${slug}`);
  emit('close');
}
</script>

<template>
  <div
    v-if="isActive"
    class="absolute inset-0 z-[60] flex items-start justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300"
    :class="isActive ? 'opacity-100' : 'opacity-0 pointer-events-none'"
    @click="deactivateSearch"
  >
    <div
      class="mt-20 w-full max-w-2xl transform px-4 transition-all duration-300"
      :class="isActive ? 'translate-y-0 opacity-100' : '-translate-y-10 opacity-0'"
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
              @click="clearSearch"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
          <!-- Animated border effect -->
          <div class="absolute bottom-0 left-0 h-[1px] w-full bg-gradient-to-r from-primary via-cyan-400 to-secondary opacity-50"></div>
        </div>

        <!-- Search Results -->
        <div v-if="searchQuery" class="max-h-[60vh] overflow-y-auto p-4">
          <!-- Loading state -->
          <div v-if="isLoading" class="p-4 text-center">
            <div class="relative mx-auto mb-4 h-8 w-8">
              <div class="absolute inset-0 animate-spin rounded-full border-2 border-transparent border-t-cyan-400 opacity-70"></div>
            </div>
            <p class="text-sm text-foreground/70">Buscando "<span class="text-cyan-400">{{ searchQuery }}</span>"...</p>
            <p class="mt-2 text-xs text-foreground/50">Presiona <span class="rounded border border-white/20 bg-white/5 px-1.5 py-0.5 text-[10px] font-mono">ESC</span> para cerrar</p>
          </div>

          <!-- Results -->
          <div v-else-if="searchResults.length > 0" class="space-y-4">
            <h3 class="mb-2 text-sm font-medium text-foreground/70">Resultados ({{ searchResults.length }})</h3>
            <div v-for="article in searchResults" :key="article.id"
                 class="group cursor-pointer rounded-lg border border-white/5 bg-white/5 p-3 transition-all hover:border-primary/30 hover:bg-white/10"
                 @click="goToArticle(article.slug)">
              <h4 class="font-medium text-foreground group-hover:text-primary">{{ article.title }}</h4>
              <p v-if="article.excerpt" class="mt-1 text-sm text-foreground/70 line-clamp-2">{{ article.excerpt }}</p>
              <div class="mt-2 flex items-center text-xs text-foreground/50">
                <span v-if="article.author">{{ article.author.name }}</span>
                <span v-if="article.published_at" class="ml-2">· {{ new Date(article.published_at).toLocaleDateString() }}</span>
              </div>
            </div>
          </div>

          <!-- No results -->
          <div v-else-if="searchQuery.length >= 2" class="p-4 text-center">
            <p class="text-sm text-foreground/70">No se encontraron resultados para "<span class="text-cyan-400">{{ searchQuery }}</span>"</p>
            <p class="mt-2 text-xs text-foreground/50">Intenta con otra búsqueda</p>
          </div>
        </div>

        <!-- Empty state -->
        <div v-else class="p-6 text-center">
          <p class="text-sm text-foreground/70">Comienza a escribir para buscar</p>
          <p class="mt-2 text-xs text-foreground/50">Presiona <span class="rounded border border-white/20 bg-white/5 px-1.5 py-0.5 text-[10px] font-mono">ESC</span> para cerrar</p>
        </div>
      </div>
    </div>
  </div>
</template>