<script setup lang="ts">
import BlogCarousel from '@/components/landing/BlogCarousel.vue';
import ColorPalette from '@/components/landing/ColorPalette.vue';
import HeroSection from '@/components/landing/HeroSection.vue';
import SectionCards from '@/components/landing/SectionCards.vue';
import TimelineSection from '@/components/landing/TimelineSection.vue';
import RetroGame from '@/components/RetroGame.vue';
import SearchModal from '@/components/SearchModal.vue';
import SeoHead from '@/components/SeoHead.vue';
import { Article } from '@/types/article';
import { TimelineItem } from '@/types/timeline-item';
import { CheckCircleIcon } from '@heroicons/vue/24/outline';
import type { PageProps as InertiaPageProps } from '@inertiajs/core';
import { Link, usePage } from '@inertiajs/vue3';
import gsap from 'gsap';
import { computed, ref } from 'vue';

interface User {
  id: number;
  name: string;
  email: string;
}

interface PageProps extends InertiaPageProps {
  auth: {
    user: User | null;
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
const isSearchActive = ref(false);
const searchQuery = ref('');
const showNotification = ref(true);

// Add flash message computed property
const flashMessage = computed(() => {
  const flash = page.props.flash as { success?: string };
  return flash?.success;
});

// Add randomPosition function to determine where the notification appears
const positions = [
  'top-5 left-5', // top-left
  'top-5 right-5', // top-right
  'top-20 left-1/2 -translate-x-1/2', // top-center
  'bottom-5 left-5', // bottom-left
  'bottom-5 right-5', // bottom-right
  'top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2', // center
];

const randomPosition = ref(positions[Math.floor(Math.random() * positions.length)]);

// Transition methods for the cyberpunk notification
const beforeEnter = (el: Element) => {
  // Reset position for each notification
  randomPosition.value = positions[Math.floor(Math.random() * positions.length)];

  gsap.set(el, {
    opacity: 0,
    scale: 0.8,
    rotation: Math.random() > 0.5 ? 2 : -2, // Random slight rotation
    y: -20
  });
};

const enter = (el: Element, done: () => void) => {
  // Create more intense glitch effect during entrance
  const glitchTimeline = gsap.timeline({ repeat: 5, repeatDelay: 0.1 });
  glitchTimeline.to(el, { x: -8, duration: 0.03, opacity: 0.8 })
    .to(el, { x: 8, duration: 0.03, opacity: 1 })
    .to(el, { x: -5, duration: 0.03, opacity: 0.9 })
    .to(el, { x: 0, duration: 0.03, opacity: 1 });

  // Main animation
  gsap.to(el, {
    opacity: 1,
    scale: 1,
    y: 0,
    duration: 0.6,
    ease: 'back.out(1.7)',
    onComplete: done
  });

  // Random color shift glitches
  const colorShift = gsap.timeline({ repeat: 8, repeatDelay: 0.3 });
  colorShift.to(el, {
    filter: 'hue-rotate(90deg)',
    duration: 0.05
  })
  .to(el, {
    filter: 'hue-rotate(0deg)',
    duration: 0.05
  });
};

const afterEnter = () => {
  // Auto-dismiss after 5 seconds
  setTimeout(() => {
    showNotification.value = false;
  }, 5000);
};

const leave = (el: Element, done: () => void) => {
  gsap.to(el, {
    opacity: 0,
    y: -50,
    x: gsap.utils.random(-20, 20),
    scale: 0.9,
    duration: 0.5,
    ease: 'power2.in',
    onComplete: done
  });
};

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

// Define props for the Welcome component
const props = defineProps<{
    articles: {
        data: Article[];
        links: {
            first: string;
            last: string;
            prev: string | null;
            next: string | null;
        };
    },
  items: {
    data: TimelineItem[];
    links: {
      first: string;
      last: string;
      prev: string | null;
      next: string | null;
    };
  };
}>();

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

// Access SEO data from shared props
const seo = page.props.seo;
</script>

<template>
  <SeoHead v-bind="seo" />

  <div class="min-h-screen bg-background text-foreground overflow-x-hidden">
    <!-- Music Player removed as it's now persistent -->

    <!-- Search Modal -->
    <SearchModal
      :is-active="isSearchActive"
      @close="isSearchActive = false"
    />

    <!-- Navigation -->
    <header class="sticky top-0 z-50 w-full">
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

    <!-- Flash Message Alert with Enhanced Cyberpunk Animation -->
    <div v-if="flashMessage" :class="[randomPosition, 'absolute z-50']">
      <transition name="cyber-alert" @before-enter="beforeEnter" @enter="enter" @after-enter="afterEnter" @leave="leave">
        <div v-if="showNotification" class="relative overflow-hidden rounded-lg border border-green-500 bg-black/80 p-4 shadow-lg shadow-green-500/20 backdrop-blur-sm max-w-md">
          <!-- Digital noise overlay -->
          <div class="absolute inset-0 bg-noise opacity-40 mix-blend-overlay"></div>

          <!-- Scan line effect -->
          <div class="absolute inset-0 bg-scanlines opacity-30"></div>

          <!-- Glitch overlay -->
          <div class="absolute inset-0 bg-gradient-to-r from-green-500/10 to-blue-500/10 animate-pulse opacity-30"></div>
          <div class="absolute inset-0 bg-gradient-to-r from-red-500/5 to-purple-500/5 animate-pulse opacity-20" style="animation-delay: 0.5s;"></div>
          <div class="absolute inset-0 bg-green-500/5 animate-glitchOverlay"></div>

          <!-- Corner accents -->
          <div class="absolute top-0 left-0 h-3 w-3 border-t border-l border-green-500 animate-pulse"></div>
          <div class="absolute top-0 right-0 h-3 w-3 border-t border-r border-green-500 animate-pulse"></div>
          <div class="absolute bottom-0 left-0 h-3 w-3 border-b border-l border-green-500 animate-pulse"></div>
          <div class="absolute bottom-0 right-0 h-3 w-3 border-b border-r border-green-500 animate-pulse"></div>

          <!-- Sliding neon lights -->
          <div class="absolute top-0 h-px w-full bg-gradient-to-r from-transparent via-green-500 to-transparent animate-slide-right"></div>
          <div class="absolute bottom-0 h-px w-full bg-gradient-to-r from-transparent via-cyan-500 to-transparent animate-slide-left"></div>
          <div class="absolute left-0 w-px h-full bg-gradient-to-b from-transparent via-green-500 to-transparent animate-slide-down"></div>
          <div class="absolute right-0 w-px h-full bg-gradient-to-b from-transparent via-cyan-500 to-transparent animate-slide-up"></div>

          <div class="relative flex items-start">
            <!-- Success icon with multiple animated rings -->
            <div class="relative mr-3 flex-shrink-0">
              <div class="h-10 w-10 rounded-full bg-green-500/20 flex items-center justify-center">
                <CheckCircleIcon class="h-6 w-6 text-green-500 animate-bounce-subtle" />
              </div>
              <div class="absolute inset-0 rounded-full border border-green-500 animate-ping-slow"></div>
              <div class="absolute inset-0 rounded-full border border-cyan-500 animate-ping-slow-delayed"></div>
              <div class="absolute inset-0 rounded-full border border-green-500/50 animate-glitch"></div>
            </div>

            <div class="ml-3 flex-1">
              <!-- Text with glitch effect -->
              <div class="relative">
                <!-- Base text -->
                <p class="text-sm font-medium text-green-500 animate-cyber-text-flicker">
                  {{ flashMessage }}
                </p>

                <!-- Glitch text layers -->
                <p class="absolute inset-0 text-sm font-medium text-red-500/30 animate-glitch" style="animation-delay: 0.05s;">
                  {{ flashMessage }}
                </p>
                <p class="absolute inset-0 text-sm font-medium text-blue-500/30 animate-glitch2" style="animation-delay: 0.1s;">
                  {{ flashMessage }}
                </p>
                <p class="absolute inset-0 text-sm font-medium text-green-500/30 animate-rgb-shift">
                  {{ flashMessage }}
                </p>
                <p class="absolute inset-0 text-sm font-medium text-white/20 animate-clip-glitch">
                  {{ flashMessage }}
                </p>
              </div>
            </div>

            <!-- Data stream effect -->
            <div class="absolute -bottom-2 -left-2 -right-2 h-8 overflow-hidden opacity-20">
              <div class="animate-slide-left whitespace-nowrap font-mono text-xs text-green-500">
                01001001 10101010 11001100 10101010 01001001 10101010 11001100 10101010 01001001 10101010 11001100
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>

    <!-- Main Content with Side Panels -->
    <div class="w-full">
      <!-- Main Content -->
      <div class="w-full mx-auto">
        <main class="relative w-full">
          <!-- Hero Section -->
          <HeroSection
            title="El Arquitecto A.I."
            subtitle="Democtratizando I.A. para el beneficio de Latinoamérica"
            logo-src="/logo.png"
          />

          <!-- Timeline Section -->
          <div class="relative">
            <TimelineSection :items="props.items.data" />

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
            <BlogCarousel :articles="articles" />

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

          <!-- Retro Games Section -->
          <div class="relative py-8">
            <h2 class="mb-8 text-center text-3xl font-bold text-foreground">
              Juegos Retro
              <span class="mt-2 h-1 w-24 bg-gradient-to-r from-primary via-cyan-400 to-secondary mx-auto"></span>
            </h2>

            <!-- Section corner accents -->
            <div class="absolute left-0 top-0 h-8 w-8">
              <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
              <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
            </div>
            <div class="absolute right-0 top-0 h-8 w-8">
              <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
              <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
            </div>

            <!-- Grid container for games -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 max-w-full sm:max-w-4xl mx-auto px-2 sm:px-4">
              <!-- Ms Pacman -->
              <RetroGame
                title="Ms. Pac-Man"
                color="primary"
                src="https://www.retrogames.cc/embed/9254-ms-pacman-champion-edition-super-zola-pac-gal.html"
              />

              <!-- Prince of Persia -->
              <RetroGame
                title="Prince of Persia"
                color="secondary"
                src="https://www.retrogames.cc/embed/21597-prince-of-persia-usa.html"
              />

              <!-- Sonic the Hedgehog -->
              <RetroGame
                title="Sonic the Hedgehog"
                color="accent"
                src="https://www.retrogames.cc/embed/30899-sonic-the-hedgehog-usa-europe.html"
              />

              <!-- Mario -->
              <RetroGame
                title="Mario Bros"
                color="cyan"
                src="https://www.retrogames.cc/embed/41973-yet-another-smw-hack.html"
              />
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
      </div>
    </div>

    <!-- Footer -->
    <footer class="relative border-t border-white/10 bg-background/90 py-8 w-full">
      <!-- Animated bottom border -->
      <div class="absolute inset-x-0 bottom-0 h-[1px]">
        <div class="absolute inset-0 animate-neon-slide-left bg-gradient-to-r from-transparent via-primary to-transparent opacity-30"></div>
      </div>
      <div class="mx-auto max-w-6xl px-4 text-center text-foreground/60">
        <p>
          {{ new Date().getFullYear() }} &bull;
          El Arquitecto A.I. &bull;
          Democratizando I.A. para el beneficio de Latinoamérica
        </p>
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

.side-panel {
  position: sticky;
  top: 0;
  height: 100vh;
  overflow: hidden;
  background-color: rgba(18, 18, 18, 0.8);
  backdrop-filter: blur(8px);
  border-radius: 0.5rem;
  margin: 0.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

@media (max-width: 1280px) {
  .side-panel {
    display: none;
  }
}

/* Cyberpunk notification animations */
@keyframes glitch {
  0% {
    transform: translate(0);
  }
  10% {
    transform: translate(-5px, 5px);
  }
  20% {
    transform: translate(-15px, 10px);
    opacity: 0.8;
  }
  30% {
    transform: translate(15px, -10px);
    opacity: 0.6;
  }
  40% {
    transform: translate(-5px, -5px);
    opacity: 0.8;
  }
  50% {
    transform: translate(5px, 5px);
    opacity: 1;
  }
  60% {
    transform: translate(10px, -5px);
  }
  70% {
    transform: translate(-10px, 10px);
    opacity: 0.9;
  }
  80% {
    transform: translate(5px, -10px);
    opacity: 0.8;
  }
  90% {
    transform: translate(-5px, 5px);
    opacity: 0.9;
  }
  100% {
    transform: translate(0);
    opacity: 1;
  }
}

@keyframes glitch2 {
  0% {
    transform: translate(0);
  }
  20% {
    transform: translate(10px, 2px);
  }
  40% {
    transform: translate(-10px, -5px);
  }
  60% {
    transform: translate(5px, 3px);
  }
  80% {
    transform: translate(-5px, -2px);
  }
  100% {
    transform: translate(0);
  }
}

@keyframes glitchOverlay {
  0%, 100% { opacity: 0; }
  10% { opacity: 0.1; }
  11% { opacity: 0.3; }
  12% { opacity: 0; }
  20% { opacity: 0; }
  21% { opacity: 0.2; }
  22% { opacity: 0; }
  50% { opacity: 0; }
  51% { opacity: 0.3; }
  52% { opacity: 0; }
  80% { opacity: 0; }
  81% { opacity: 0.4; }
  82% { opacity: 0; }
  90% { opacity: 0.2; }
  91% { opacity: 0; }
}

.cyberpunk-notification {
  animation: glitch 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
  animation-delay: 0.5s;
  box-shadow: 0 0 20px rgba(124, 58, 237, 0.3);
}

.animate-glitch-overlay {
  animation: glitchOverlay 4s infinite;
}

/* Enhanced cyberpunk animations */
@keyframes ping-slow {
  0%, 100% { transform: scale(1); opacity: 0.3; }
  50% { transform: scale(1.2); opacity: 0.5; }
}

@keyframes ping-slow-delayed {
  0%, 100% { transform: scale(1); opacity: 0.5; }
  50% { transform: scale(1.15); opacity: 0.3; }
}

@keyframes pulse-glow {
  0%, 100% { opacity: 0; }
  50% { opacity: 0.3; }
}

@keyframes bounce-subtle {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-2px); }
}

@keyframes cyber-text-flicker {
  0%, 100% { opacity: 1; }
  8% { opacity: 0.8; }
  9% { opacity: 0.1; }
  10% { opacity: 0.8; }
  20% { opacity: 1; }
  40% { opacity: 0.7; }
  42% { opacity: 0.1; }
  43% { opacity: 0.7; }
  50% { opacity: 0.9; }
  60% { opacity: 1; }
  70% { opacity: 0.8; }
  80% { opacity: 0.7; }
  81% { opacity: 0.1; }
  82% { opacity: 0.7; }
  90% { opacity: 0.9; }
  95% { opacity: 0.3; }
  96% { opacity: 0.9; }
}

@keyframes data-stream {
  0% { background-position: 0 0; }
  100% { background-position: 0 100px; }
}

.animate-ping-slow {
  animation: ping-slow 3s cubic-bezier(0, 0, 0.2, 1) infinite;
}

.animate-ping-slow-delayed {
  animation: ping-slow-delayed 3s cubic-bezier(0, 0, 0.2, 1) infinite;
  animation-delay: 1.5s;
}

.animate-pulse-glow {
  animation: pulse-glow 4s infinite;
}

.animate-bounce-subtle {
  animation: bounce-subtle 2s ease-in-out infinite;
}

.cyber-text {
  animation: cyber-text-flicker 5s infinite;
  text-shadow: 0 0 5px rgba(0, 255, 170, 0.7), 0 0 10px rgba(0, 255, 170, 0.5);
}

.cyber-text-glitch {
  animation: glitch 0.3s infinite;
  animation-play-state: paused;
}

.cyber-text-glitch:hover {
  animation-play-state: running;
}

/* Scan lines effect */
.scan-lines {
  background: linear-gradient(
    to bottom,
    transparent 0%,
    rgba(255, 255, 255, 0.05) 50%,
    transparent 100%
  );
  background-size: 100% 4px;
  z-index: 2;
}

/* Digital noise effect */
.bg-noise {
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
}

/* Data stream effect */
.data-stream {
  background: linear-gradient(to bottom,
    transparent 0%,
    rgba(0, 255, 170, 0.2) 10%,
    rgba(0, 255, 170, 0.5) 50%,
    rgba(0, 255, 170, 0.2) 90%,
    transparent 100%
  );
  background-size: 1px 100px;
  animation: data-stream 2s linear infinite;
}

.data-stream-reverse {
  background: linear-gradient(to top,
    transparent 0%,
    rgba(124, 58, 237, 0.2) 10%,
    rgba(124, 58, 237, 0.5) 50%,
    rgba(124, 58, 237, 0.2) 90%,
    transparent 100%
  );
  background-size: 1px 100px;
  animation: data-stream 3s linear infinite;
}

/* Glitch shadow effect */
.glitch-shadow {
  box-shadow: inset 0 0 10px rgba(0, 255, 170, 0.5), inset 0 0 20px rgba(124, 58, 237, 0.3);
}

/* Transition classes */
.cyber-alert-enter-active,
.cyber-alert-leave-active {
  transition: all 0.5s ease;
}

.cyber-alert-enter-from,
.cyber-alert-leave-to {
  opacity: 0;
  transform: translateY(-30px) scale(0.9);
}

@keyframes rgb-shift {
  0%, 100% { transform: translate(0); }
  20% { transform: translate(1px, 1px); }
  40% { transform: translate(-1px, -1px); }
  60% { transform: translate(1px, -1px); }
  80% { transform: translate(-1px, 1px); }
}

@keyframes clip-glitch {
  0%, 100% { clip-path: inset(0 0 0 0); }
  10% { clip-path: inset(10% 0 0 10%); }
  20% { clip-path: inset(0 10% 10% 0); }
  30% { clip-path: inset(0 0 5% 0); }
  40% { clip-path: inset(0 0 0 5%); }
  50% { clip-path: inset(5% 5% 0 0); }
  60% { clip-path: inset(0 0 5% 5%); }
  70% { clip-path: inset(5% 0 0 0); }
  80% { clip-path: inset(0 5% 0 0); }
  90% { clip-path: inset(0 0 0 0); }
}
</style>
