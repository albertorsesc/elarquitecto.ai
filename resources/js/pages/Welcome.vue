<script setup lang="ts">
import BlogCarousel from '@/components/landing/BlogCarousel.vue';
import ColorPalette from '@/components/landing/ColorPalette.vue';
import HeroSection from '@/components/landing/HeroSection.vue';
import SectionCards from '@/components/landing/SectionCards.vue';
import TimelineSection from '@/components/landing/TimelineSection.vue';
import type { PageProps as InertiaPageProps } from '@inertiajs/core';
import { Head, Link, usePage } from '@inertiajs/vue3';

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
    image: 'https://via.placeholder.com/400x200/1a1a2e/ffffff?text=Introducción+a+la+IA',
    category: 'Fundamentos'
  },
  {
    id: 2,
    title: 'Prompts Efectivos',
    excerpt: 'Aprende a crear prompts que generen resultados precisos con modelos de lenguaje avanzados.',
    image: 'https://via.placeholder.com/400x200/1a1a2e/ffffff?text=Prompts+Efectivos',
    category: 'Técnicas'
  },
  {
    id: 3,
    title: 'IA en Latinoamérica',
    excerpt: 'Análisis del estado actual de la Inteligencia Artificial en los países latinoamericanos.',
    image: 'https://via.placeholder.com/400x200/1a1a2e/ffffff?text=IA+en+Latinoamérica',
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
    <!-- Navigation -->
    <header class="fixed top-0 z-50 w-full">
      <nav class="glass-effect border-b border-white/10 bg-background/70 px-2 py-3 backdrop-blur-xl">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
          <Link href="/" class="text-xl font-bold text-primary sm:text-2xl">
            El Arquitecto A.I.
          </Link>

          <div class="flex items-center gap-2 sm:gap-4">
            <Link
              v-if="page.props.auth.user"
              :href="route('dashboard')"
              class="neon-border rounded bg-primary/10 px-3 py-1.5 text-sm font-semibold text-primary transition-all hover:bg-primary/20 sm:px-4 sm:py-2"
            >
              Dashboard
            </Link>
            <template v-else>
              <Link
                :href="route('login')"
                class="px-2 text-sm text-foreground/80 transition-colors hover:text-primary sm:text-base"
              >
                Iniciar Sesión
              </Link>
              <Link
                :href="route('register')"
                class="neon-border rounded bg-primary px-3 py-1.5 text-sm font-semibold text-white transition-all hover:bg-primary/80 sm:px-4 sm:py-2"
              >
                Registrarse
              </Link>
            </template>
          </div>
        </div>
      </nav>
    </header>

    <main>
      <!-- Hero Section -->
      <HeroSection
        title="El Arquitecto A.I."
        subtitle="Descubre el poder de la Inteligencia Artificial en español"
      />

      <!-- Timeline Section -->
      <TimelineSection :items="timelineItems" />

      <!-- Blog Carousel -->
      <BlogCarousel :posts="blogPosts" />

      <!-- Section Cards -->
      <SectionCards :sections="sections" />

      <!-- Color Palette -->
      <ColorPalette />
    </main>

    <!-- Footer -->
    <footer class="border-t border-white/10 bg-background/90 py-8">
      <div class="container mx-auto px-4 text-center text-foreground/60">
        <p>&copy; 2024 El Arquitecto A.I. Todos los derechos reservados.</p>
      </div>
    </footer>
  </div>
</template>
