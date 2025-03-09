<script setup lang="ts">
defineProps<{
  title: string;
  subtitle: string;
  logoSrc?: string;
}>();
</script>

<template>
  <section class="relative min-h-[80vh] w-full overflow-hidden pt-20 sm:pt-24">
    <!-- Cyberpunk background with gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary/20 via-secondary/20 to-accent/20">
      <div class="cyberpunk-grid-bg absolute inset-0 opacity-20"></div>

      <!-- Animated neon lines -->
      <div class="hidden sm:block">
        <!-- Horizontal neon lines -->
        <div class="absolute left-0 top-1/4 h-[1px] w-1/3 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-30"></div>
        <div class="absolute right-0 top-2/3 h-[1px] w-1/3 animate-neon-slide-left bg-gradient-to-r from-transparent via-primary to-transparent opacity-30"></div>

        <!-- Vertical neon lines -->
        <div class="absolute left-1/4 top-0 h-1/3 w-[1px] animate-neon-slide-down bg-gradient-to-b from-transparent via-secondary to-transparent opacity-30"></div>
        <div class="absolute right-1/3 top-0 h-1/3 w-[1px] animate-neon-slide-down-delayed bg-gradient-to-b from-transparent via-accent to-transparent opacity-30"></div>
      </div>
    </div>

    <!-- Glass container -->
    <div class="mx-auto max-w-6xl px-4 py-12 sm:py-20">
      <div class="glass-effect relative overflow-hidden rounded-2xl border border-white/10 bg-background/70 p-4 shadow-[0_0_15px_rgba(0,0,0,0.2)] backdrop-blur-xl transition-all duration-300 hover:shadow-[0_0_30px_rgba(124,58,237,0.3)] sm:p-8">
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

        <!-- Background Logo -->
        <div
          v-if="logoSrc"
          class="absolute inset-0 z-0 opacity-10"
          :style="{
            backgroundImage: `url(${logoSrc})`,
            backgroundSize: '150%',
            backgroundPosition: 'center',
            backgroundRepeat: 'no-repeat',
            filter: 'blur(2px) brightness(1.2)'
          }"
        ></div>

        <div class="relative z-10 mx-auto max-w-3xl">
          <h1 class="mb-4 text-center text-3xl font-bold sm:mb-6 sm:text-4xl md:text-6xl">
            <span class="mr-2 text-primary animate-text-glow">El Arquitecto</span>
            <span class="text-cyan-400 animate-text-glow-delayed">A.I.</span>
          </h1>
          <p class="text-center text-lg text-foreground/90 sm:text-xl md:text-2xl">
            {{ subtitle }}
          </p>

          <!-- Subscription form -->
          <div class="mt-8 flex justify-center sm:mt-10">
            <div class="group relative w-full max-w-2xl">
              <input
                type="email"
                placeholder="Tu correo electrónico"
                class="relative z-10 w-full rounded-xl border border-white/10 bg-background/50 py-3 pl-4 pr-36 text-sm text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
              />
              <button class="absolute right-2 top-1/2 z-10 -translate-y-1/2 rounded-lg bg-primary px-4 py-1.5 text-sm font-semibold text-white transition-all hover:bg-primary/80 hover:shadow-[0_0_20px_rgba(124,58,237,0.5)]">
                Suscribirse
              </button>
              <!-- Animated border effect -->
              <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>

              <!-- Sliding neon lights (visible on focus) -->
              <div class="pointer-events-none absolute -inset-1 opacity-0 group-focus-within:opacity-100">
                <!-- Top edge -->
                <div class="absolute left-0 top-0 h-[2px] w-full animate-neon-slide-right-slow bg-gradient-to-r from-transparent via-primary to-transparent"></div>
                <!-- Right edge -->
                <div class="absolute right-0 top-0 h-full w-[2px] animate-neon-slide-down-slow bg-gradient-to-b from-transparent via-secondary to-transparent"></div>
                <!-- Bottom edge -->
                <div class="absolute bottom-0 left-0 h-[2px] w-full animate-neon-slide-left-slow bg-gradient-to-r from-transparent via-accent to-transparent"></div>
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

          <!-- Cyberpunk-styled buttons -->
          <div class="mt-6 flex flex-wrap justify-center gap-3 sm:mt-8 sm:gap-4">
            <button class="neon-border group relative rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white transition-all hover:bg-primary/80 hover:shadow-[0_0_20px_rgba(124,58,237,0.5)] sm:px-6 sm:py-3">
              <span class="relative z-10">Explorar</span>
              <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary via-secondary to-accent opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
            </button>
            <button class="group relative rounded-xl border border-primary/50 bg-transparent px-4 py-2 text-sm font-semibold text-primary transition-all hover:border-primary hover:bg-primary/10 hover:shadow-[0_0_20px_rgba(124,58,237,0.3)] sm:px-6 sm:py-3">
              <span class="relative z-10">Más Info</span>
              <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary/20 via-secondary/20 to-accent/20 opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced decorative elements -->
    <div class="absolute bottom-0 left-0 h-32 w-32 rotate-45 animate-pulse-slow bg-accent/20 blur-3xl"></div>
    <div class="absolute right-0 top-0 h-32 w-32 rotate-45 animate-pulse-slow bg-secondary/20 blur-3xl"></div>
    <div class="absolute bottom-1/4 right-1/4 h-24 w-24 rotate-45 animate-float bg-primary/20 blur-3xl"></div>
  </section>
</template>

<style scoped>
@keyframes glow {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.8; }
}

@keyframes float {
  0%, 100% { transform: translateY(0) rotate(45deg); }
  50% { transform: translateY(-20px) rotate(45deg); }
}

.animate-glow {
  animation: glow 3s infinite;
}

.animate-float {
  animation: float 6s infinite ease-in-out;
}

.animate-pulse-slow {
  animation: pulse 4s infinite;
}

.animate-text-glow {
  animation: textGlow 3s infinite;
}

.animate-text-glow-delayed {
  animation: textGlow 3s infinite 1.5s;
}

@keyframes textGlow {
  0%, 100% { text-shadow: 0 0 10px rgba(124,58,237,0.5); }
  50% { text-shadow: 0 0 20px rgba(124,58,237,0.8); }
}
</style>