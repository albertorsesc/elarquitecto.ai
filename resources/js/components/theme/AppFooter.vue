<script setup lang="ts">
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';
import NeonBorders from './NeonBorders.vue';

interface FooterLink {
  title: string;
  href: string;
  icon: string;
}

const links: FooterLink[] = [
  {
    title: 'Blog',
    href: '/blog',
    icon: 'carbon:blog'
  },
  {
    title: 'Prompts',
    href: '/prompts',
    icon: 'carbon:chat'
  },
  {
    title: 'Herramientas',
    href: '/tools',
    icon: 'carbon:tools'
  },
  {
    title: 'Foro',
    href: '/forum',
    icon: 'carbon:forum'
  },
  {
    title: 'Recursos',
    href: '/resources',
    icon: 'carbon:document'
  },
  {
    title: 'Comunidad',
    href: '/community',
    icon: 'carbon:group'
  }
];
</script>

<template>
  <footer class="relative border-t border-white/10 bg-background/90 py-4">
    <!-- Animated bottom border -->
    <div class="absolute inset-x-0 bottom-0 h-[1px]">
      <div class="absolute inset-0 animate-neon-slide-left bg-gradient-to-r from-transparent via-primary to-transparent opacity-30"></div>
    </div>

    <!-- Footer content -->
    <div class="mx-auto max-w-6xl px-4">
      <!-- Footer links -->
      <div class="mb-3 grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-6">
        <Link
          v-for="link in links"
          :key="link.title"
          :href="link.href"
          class="group flex flex-col items-center justify-center gap-1 rounded-lg p-2 transition-all hover:bg-white/5"
        >
          <div class="relative flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 transition-all group-hover:bg-primary/20">
            <Icon :icon="link.icon" class="text-lg text-primary" />
            <!-- Glow effect on hover -->
            <div class="absolute -inset-1 rounded-full opacity-0 transition-opacity group-hover:opacity-100">
              <NeonBorders position="all" color="primary" :opacity="0.3" />
            </div>
          </div>
          <span class="text-xs text-foreground/80 transition-colors group-hover:text-primary">{{ link.title }}</span>
        </Link>
      </div>

      <!-- Copyright -->
      <div class="text-center text-xs text-foreground/60">
        <p>&copy; 2024 El Arquitecto A.I. Todos los derechos reservados.</p>
      </div>
    </div>
  </footer>
</template>