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
  <section class="py-12 sm:py-16">
    <div class="mx-auto max-w-6xl px-4">
      <h2 class="text-glow-light mb-8 text-center text-2xl font-bold text-accent sm:mb-12 sm:text-3xl md:text-4xl">
        Últimos Artículos
      </h2>

      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="post in posts"
          :key="post.id"
          class="group relative overflow-hidden rounded-xl border border-white/10 bg-background/50 p-4 backdrop-blur-sm transition-all duration-300 hover:border-primary/30 hover:shadow-[0_0_20px_rgba(124,58,237,0.3)]"
        >
          <!-- Animated corner accents -->
          <div class="absolute left-0 top-0 h-8 w-8 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
            <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
            <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
          </div>
          <div class="absolute right-0 top-0 h-8 w-8 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
            <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
            <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
          </div>

          <!-- Neon border glow on hover -->
          <div class="pointer-events-none absolute inset-0 rounded-xl opacity-0 transition-opacity duration-300 group-hover:opacity-100">
            <div class="absolute inset-0 rounded-xl border border-primary/0 transition-all duration-300 group-hover:border-primary/30 group-hover:shadow-[0_0_15px_rgba(124,58,237,0.3),inset_0_0_10px_rgba(124,58,237,0.1)]"></div>

            <!-- Sliding neon lights -->
            <div class="absolute -inset-1 opacity-0 group-hover:opacity-100">
              <!-- Top edge -->
              <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right-slow bg-gradient-to-r from-transparent via-cyan-400 to-transparent"></div>
              <!-- Right edge -->
              <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down-slow bg-gradient-to-b from-transparent via-secondary to-transparent"></div>
              <!-- Bottom edge -->
              <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left-slow bg-gradient-to-r from-transparent via-accent to-transparent"></div>
              <!-- Left edge -->
              <div class="absolute left-0 top-0 h-full w-[2px] animate-neon-slide-up-slow bg-gradient-to-b from-transparent via-primary to-transparent"></div>
            </div>
          </div>

          <img :src="post.image" :alt="post.title" class="mb-4 rounded-lg" @error="handleImageError" />
          <span class="mb-2 inline-block rounded bg-primary/10 px-2 py-1 text-xs font-medium text-primary">
            {{ post.category }}
          </span>
          <h3 class="mb-2 text-lg font-semibold text-foreground transition-colors duration-300 group-hover:text-primary group-hover:text-shadow-sm">
            {{ post.title }}
          </h3>
          <p class="text-sm text-foreground/70">
            {{ post.excerpt }}
          </p>

          <!-- Hover effect overlay -->
          <div class="absolute inset-0 bg-gradient-to-t from-background/50 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
@keyframes glow {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.8; }
}

.animate-glow {
  animation: glow 3s infinite;
}

@keyframes neonSlideRight {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

@keyframes neonSlideLeft {
  0% { transform: translateX(100%); }
  100% { transform: translateX(-100%); }
}

@keyframes neonSlideDown {
  0% { transform: translateY(-100%); }
  100% { transform: translateY(100%); }
}

@keyframes neonSlideUp {
  0% { transform: translateY(100%); }
  100% { transform: translateY(-100%); }
}

.animate-neon-slide-right-slow {
  animation: neonSlideRight 3s linear infinite;
}

.animate-neon-slide-left-slow {
  animation: neonSlideLeft 3s linear infinite;
}

.animate-neon-slide-down-slow {
  animation: neonSlideDown 3s linear infinite;
}

.animate-neon-slide-up-slow {
  animation: neonSlideUp 3s linear infinite;
}
</style>