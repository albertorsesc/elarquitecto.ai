<script setup lang="ts">
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';
import { inject, ref, watch } from 'vue';
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

// Get the footer expanded state from parent component
const isFooterExpanded = inject('isFooterExpanded', ref(false));

// Local state to track if footer is expanded or collapsed
const isExpanded = ref(false);

// Define emits
const emit = defineEmits(['toggle']);

// Toggle footer expansion state
const toggleFooter = () => {
  isExpanded.value = !isExpanded.value;
  emit('toggle');
};

// Sync local state with injected state
watch(isFooterExpanded, (newValue) => {
  isExpanded.value = newValue;
});

// Sync injected state with local state
watch(isExpanded, (newValue) => {
  if (isFooterExpanded.value !== newValue) {
    isFooterExpanded.value = newValue;
  }
});
</script>

<template>
  <footer class="absolute bottom-0 left-0 right-0 border-t border-white/10 bg-background/90 transition-all duration-300 z-20"
          :class="{ 'h-[160px]': isExpanded, 'h-[64px]': !isExpanded }">
    <!-- Animated bottom border -->
    <div class="absolute inset-x-0 bottom-0 h-[1px]">
      <div class="absolute inset-0 animate-neon-slide-left bg-gradient-to-r from-transparent via-primary to-transparent opacity-30"></div>
    </div>

    <!-- Toggle button -->
    <div class="absolute -top-4 left-1/2 -translate-x-1/2 transform z-30">
      <button
        @click="toggleFooter"
        class="group flex h-8 w-8 items-center justify-center rounded-full bg-background border border-white/10 transition-all hover:bg-primary/20"
        :aria-expanded="isExpanded"
        aria-label="Toggle footer"
      >
        <Icon
          :icon="isExpanded ? 'carbon:chevron-down' : 'carbon:chevron-up'"
          class="text-lg text-primary transition-transform"
        />
        <!-- Glow effect on hover -->
        <div class="absolute -inset-1 rounded-full opacity-0 transition-opacity group-hover:opacity-100">
          <NeonBorders position="all" color="primary" :opacity="0.3" />
        </div>
      </button>
    </div>

    <!-- Footer content container - positioned from bottom -->
    <div class="absolute bottom-0 left-0 right-0 w-full transition-all duration-300 backdrop-blur-sm"
         :style="{ height: isExpanded ? '160px' : '64px' }">
      <!-- Expanded footer content -->
      <div v-if="isExpanded" class="mx-auto max-w-6xl px-4 h-full flex flex-col justify-center">
        <!-- Footer links with text labels -->
        <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-6">
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

        <!-- Full copyright -->
        <div class="text-center text-xs text-foreground/60 mt-4">
          <p>&copy; 2024 El Arquitecto A.I. Todos los derechos reservados.</p>
        </div>
      </div>

      <!-- Collapsed footer with icons only -->
      <div v-else class="mx-auto max-w-3xl px-4 h-full flex flex-col justify-center">
        <div class="flex flex-col items-center">
          <!-- Icons only in collapsed state -->
          <div class="flex flex-1 justify-between w-full items-center">
            <Link
              v-for="link in links"
              :key="link.title"
              :href="link.href"
              class="group relative"
              :title="link.title"
            >
              <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 transition-all group-hover:bg-primary/20">
                <Icon :icon="link.icon" class="text-lg text-primary" />
              </div>
              <!-- Glow effect on hover -->
              <div class="absolute -inset-1 rounded-full opacity-0 transition-opacity group-hover:opacity-100">
                <NeonBorders position="all" color="primary" :opacity="0.3" />
              </div>
            </Link>
          </div>

          <!-- Subtle copyright -->
          <div class="text-[10px] text-foreground/40 mt-0.5">
            &copy; 2024 El Arquitecto A.I.
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>