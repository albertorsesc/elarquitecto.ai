<script setup lang="ts">

interface BlogPost {
  id: number;
  title: string;
  excerpt: string;
  image: string;
  category: string;
}

defineProps<{
  posts: BlogPost[];
}>();

// Function to handle image loading errors
function handleImageError(event: Event) {
  const target = event.target as HTMLImageElement;
  target.src = 'https://via.placeholder.com/400x200/1a1a2e/ffffff?text=El+Arquitecto+A.I.';
}
</script>

<template>
  <section class="relative py-12 sm:py-20">
    <div class="mx-auto max-w-6xl px-4">
      <h2 class="text-glow-light mb-8 text-center text-2xl font-bold text-accent sm:mb-12 sm:text-3xl md:text-4xl">
        Últimos Artículos
      </h2>

      <!-- Carousel container -->
      <div class="relative overflow-hidden">
        <div class="flex gap-4 overflow-x-auto pb-6 scrollbar-hide sm:gap-6">
          <!-- Blog cards -->
          <article v-for="post in posts" :key="post.id"
                   class="glass-effect relative min-w-[260px] flex-none rounded-lg border border-white/10 bg-background/70 backdrop-blur-xl sm:min-w-[300px] md:min-w-[400px]">
            <!-- Image -->
            <div class="relative h-36 overflow-hidden rounded-t-lg sm:h-48">
              <img :src="post.image" :alt="post.title" class="h-full w-full object-cover" @error="handleImageError" />
              <div class="absolute inset-0 bg-gradient-to-t from-background to-transparent"></div>
            </div>

            <!-- Content -->
            <div class="p-4 sm:p-6">
              <span class="mb-1 inline-block text-xs font-medium text-cyan-400 sm:mb-2 sm:text-sm">
                {{ post.category }}
              </span>
              <h3 class="mb-2 text-lg font-bold text-primary sm:mb-3 sm:text-xl">{{ post.title }}</h3>
              <p class="text-sm text-foreground/80 sm:text-base">{{ post.excerpt }}</p>

              <!-- Read more button -->
              <button class="neon-border mt-3 rounded bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary transition-all hover:bg-primary/20 sm:mt-4 sm:px-4 sm:py-2 sm:text-sm">
                Leer Más
              </button>
            </div>

            <!-- Decorative corner -->
            <div class="absolute right-0 top-0 h-12 w-12 overflow-hidden sm:h-16 sm:w-16">
              <div class="absolute right-0 top-0 h-3 w-3 translate-x-1.5 -translate-y-1.5 rotate-45 bg-cyan-400 sm:h-4 sm:w-4 sm:translate-x-2 sm:-translate-y-2"></div>
            </div>
          </article>
        </div>

        <!-- Navigation dots -->
        <div class="mt-4 flex justify-center gap-2 sm:mt-6">
          <button v-for="i in Math.ceil(posts.length / 3)" :key="i"
                  class="h-1.5 w-1.5 rounded-full bg-primary/30 transition-all hover:bg-primary sm:h-2 sm:w-2"
                  :class="{ 'w-3 bg-primary sm:w-4': i === 1 }">
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
</style>