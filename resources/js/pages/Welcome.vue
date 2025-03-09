<script setup lang="ts">
import BlogCarousel from '@/components/landing/BlogCarousel.vue';
import ColorPalette from '@/components/landing/ColorPalette.vue';
import HeroSection from '@/components/landing/HeroSection.vue';
import MusicPlayer from '@/components/landing/MusicPlayer.vue';
import SectionCards from '@/components/landing/SectionCards.vue';
import TimelineSection from '@/components/landing/TimelineSection.vue';
import type { PageProps as InertiaPageProps } from '@inertiajs/core';
import { Head, Link, usePage } from '@inertiajs/vue3';
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

function activateSearch() {
  isSearchActive.value = true;
  // Focus the input after a short delay to allow the animation to start
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

// Sample data
const timelineItems = [
  {
    date: '2023',
    title: 'Origen de El Arquitecto A.I.',
    description: 'Nace la idea de crear una plataforma para compartir conocimiento sobre IA en español.'
  },
  {
    date: '2024',
    title: 'Lanzamiento Oficial',
    description: 'Inicio de nuestra misión para compartir conocimiento sobre IA en Latinoamérica.'
  },
  {
    date: '2024',
    title: 'Comunidad en Crecimiento',
    description: 'Alcanzamos miles de seguidores en nuestras redes sociales.'
  },
  {
    date: '2024',
    title: 'Expansión de Contenido',
    description: 'Ampliamos nuestras secciones para incluir prompts, herramientas y más recursos.'
  }
];

const blogPosts = [
  {
    id: 1,
    title: 'Introducción a la IA',
    excerpt: 'Descubre los conceptos básicos de la Inteligencia Artificial y cómo está cambiando el mundo.',
    image: 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
    category: 'Fundamentos'
  },
  {
    id: 2,
    title: 'Prompts Efectivos',
    excerpt: 'Aprende a crear prompts que generen resultados precisos con modelos de lenguaje avanzados.',
    image: 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
    category: 'Técnicas'
  },
  {
    id: 3,
    title: 'IA en Latinoamérica',
    excerpt: 'Análisis del estado actual de la Inteligencia Artificial en los países latinoamericanos.',
    image: 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3',
    category: 'Tendencias'
  }
];

const sections = [
  {
    id: 1,
    title: 'Blog',
    description: 'Artículos sobre IA, tutoriales y mejores prácticas para mantenerte actualizado.',
    icon: 'carbon:blog',
    link: '/blog'
  },
  {
    id: 2,
    title: 'Prompts',
    description: 'Colección de prompts efectivos para diferentes modelos de IA y casos de uso.',
    icon: 'carbon:chat',
    link: '/prompts'
  },
  {
    id: 3,
    title: 'Herramientas',
    description: 'Las mejores herramientas de IA recomendadas para potenciar tu productividad.',
    icon: 'carbon:tools',
    link: '/tools'
  },
  {
    id: 4,
    title: 'Foro',
    description: 'Únete a la conversación y comparte tus experiencias con otros entusiastas de la IA.',
    icon: 'carbon:forum',
    link: '/forum'
  },
  {
    id: 5,
    title: 'Recursos',
    description: 'Guías, libros, cursos y otros recursos para profundizar en el mundo de la IA.',
    icon: 'carbon:document',
    link: '/resources'
  },
  {
    id: 6,
    title: 'Comunidad',
    description: 'Conecta con otros profesionales y entusiastas de la IA en Latinoamérica.',
    icon: 'carbon:group',
    link: '/community'
  }
];
</script>

<template>
  <Head>
    <title>El Arquitecto A.I. - Conocimiento de IA en Español</title>
    <meta name="description" content="Aprende sobre Inteligencia Artificial en español con El Arquitecto A.I. Blog, tutoriales, prompts y más." />
    </Head>

  <div class="min-h-screen bg-background text-foreground">
    <!-- Music Player -->
    <MusicPlayer />

    <!-- Navigation -->
    <header class="fixed top-0 z-50 w-full">
      <nav class="glass-effect relative border-b border-white/10 bg-background/70 px-2 py-3 backdrop-blur-xl sm:py-4">
        <!-- Animated top border -->
        <div class="absolute inset-x-0 top-0 h-[1px]">
          <div class="absolute inset-0 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-30"></div>
        </div>

        <div class="mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-2 px-2">
          <!-- Logo with glow -->
          <Link href="/" class="group flex items-center">
            <img src="/logo.png" alt="El Arquitecto A.I. Logo" class="h-8 w-auto transition-all duration-300 group-hover:shadow-[0_0_15px_rgba(124,58,237,0.3)] sm:h-10" />
          </Link>

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
                    <Link
                        :href="route('login')"
                class="px-1 text-xs text-foreground/80 transition-colors hover:text-primary sm:px-2 sm:text-sm"
                    >
                Iniciar
                    </Link>
                    <Link
                        :href="route('register')"
                class="neon-border rounded bg-primary px-2 py-1 text-xs font-semibold text-white transition-all hover:bg-primary/80 sm:px-3 sm:py-1.5 sm:text-sm"
                    >
                Registrarse
                    </Link>
                </template>
          </div>

          <!-- Search Bar -->
          <div class="order-3 mt-2 w-full sm:order-2 sm:mt-0 sm:w-auto sm:flex-1 sm:px-4 md:px-6">
            <div class="group relative">
              <input
                type="text"
                placeholder="Buscar contenido..."
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
                <!-- Top edge -->
                <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right-slow bg-gradient-to-r from-transparent via-accent to-transparent"></div>
                <!-- Right edge -->
                <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down-slow bg-gradient-to-b from-transparent via-primary to-transparent"></div>
                <!-- Bottom edge -->
                <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left-slow bg-gradient-to-r from-transparent via-secondary to-transparent"></div>
                <!-- Left edge -->
                <div class="absolute left-0 top-0 h-full w-[2px] animate-neon-slide-up-slow bg-gradient-to-b from-transparent via-cyan-400 to-transparent"></div>
              </div>

              <!-- Corner accents -->
              <div class="absolute left-0 top-0 h-8 w-8 opacity-0 transition-opacity duration-300 group-focus-within:opacity-100">
                <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
                <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
              </div>
              <div class="absolute right-0 top-0 h-8 w-8 opacity-0 transition-opacity duration-300 group-focus-within:opacity-100">
                <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
                <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
              </div>
            </div>
          </div>
        </div>
            </nav>
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

    <main class="relative">
      <!-- Floating neon lines for desktop -->
      <div class="pointer-events-none fixed inset-0 hidden sm:block">
        <!-- Horizontal lines -->
        <div class="absolute left-0 top-1/3 h-[1px] w-1/4 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-20"></div>
        <div class="absolute right-0 top-2/3 h-[1px] w-1/4 animate-neon-slide-left bg-gradient-to-r from-transparent via-primary to-transparent opacity-20"></div>
        <!-- Vertical lines -->
        <div class="absolute left-1/3 top-0 h-1/4 w-[1px] animate-neon-slide-down bg-gradient-to-b from-transparent via-secondary to-transparent opacity-20"></div>
        <div class="absolute right-2/3 top-0 h-1/4 w-[1px] animate-neon-slide-down-delayed bg-gradient-to-b from-transparent via-accent to-transparent opacity-20"></div>
      </div>

      <!-- Hero Section -->
      <HeroSection
        title="El Arquitecto A.I."
        subtitle="Descubre el poder de la Inteligencia Artificial en español"
        logo-src="/logo.png"
      />

      <!-- Timeline Section -->
      <div class="relative">
        <TimelineSection :items="timelineItems" />
        <!-- Section corner accents -->
        <div class="absolute left-0 top-0 h-8 w-8">
          <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
          <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
        </div>
        <div class="absolute right-0 top-0 h-8 w-8">
          <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
          <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
        </div>
      </div>

      <!-- Blog Carousel -->
      <div class="relative">
        <BlogCarousel :posts="blogPosts" />
        <!-- Section corner accents -->
        <div class="absolute left-0 top-0 h-8 w-8">
          <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-secondary via-transparent to-transparent"></div>
          <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-secondary via-transparent to-transparent"></div>
        </div>
        <div class="absolute right-0 top-0 h-8 w-8">
          <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-accent via-transparent to-transparent"></div>
          <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-accent via-transparent to-transparent"></div>
        </div>
      </div>

      <!-- Section Cards -->
      <div class="relative">
        <SectionCards :sections="sections" />
        <!-- Section corner accents -->
        <div class="absolute left-0 top-0 h-8 w-8">
          <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
          <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
        </div>
        <div class="absolute right-0 top-0 h-8 w-8">
          <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
          <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
        </div>
      </div>

      <!-- Color Palette -->
      <div class="relative">
        <ColorPalette />
        <!-- Section corner accents -->
        <div class="absolute left-0 top-0 h-8 w-8">
          <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-secondary via-transparent to-transparent"></div>
          <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-secondary via-transparent to-transparent"></div>
        </div>
        <div class="absolute right-0 top-0 h-8 w-8">
          <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-accent via-transparent to-transparent"></div>
          <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-accent via-transparent to-transparent"></div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="relative border-t border-white/10 bg-background/90 py-8">
      <!-- Animated bottom border -->
      <div class="absolute inset-x-0 bottom-0 h-[1px]">
        <div class="absolute inset-0 animate-neon-slide-left bg-gradient-to-r from-transparent via-primary to-transparent opacity-30"></div>
      </div>
      <div class="mx-auto max-w-6xl px-4 text-center text-foreground/60">
        <p>&copy; 2024 El Arquitecto A.I. Todos los derechos reservados.</p>
      </div>
    </footer>
    </div>
</template>

<style scoped>
@keyframes glow {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.8; }
}

.animate-glow {
  animation: glow 3s infinite;
}
</style>
