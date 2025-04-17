<script setup lang="ts">
import { onMounted } from 'vue';
import AnimatedCorners from '@/components/theme/AnimatedCorners.vue';
import SlidingNeonEdges from '@/components/theme/SlidingNeonEdges.vue';

defineProps<{
    title?: string;
    description?: string;
}>();

onMounted(() => {
    // Ensure dark mode is applied
    document.documentElement.classList.add('dark');
});
</script>

<template>
    <div class="dark flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10 antialiased overflow-hidden"
         style="background-image: radial-gradient(circle at 15% 50%, rgb(var(--secondary-rgb)) / 0.15, transparent 25%), radial-gradient(circle at 85% 30%, rgb(var(--accent-rgb)) / 0.15, transparent 25%); background-attachment: fixed; background-size: cover;">
        <div class="auth-glass-card relative w-full max-w-sm rounded-xl border border-white/10 p-8 shadow-[0_0_30px_rgba(124,58,237,0.3)]">
            <!-- Center logo with semi-transparency -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">
                <img src="/img/logo.webp" alt="El Arquitecto A.I. Logo" class="w-full min-w-full min-h-full object-cover opacity-[0.07]" />
            </div>
            
            <!-- Animated corner accents -->
            <AnimatedCorners />
            
            <!-- Sliding neon lights -->
            <SlidingNeonEdges />
            
            <div class="relative z-10 flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <div class="space-y-2 text-center">
                        <h1 class="text-2xl font-bold text-foreground animate-text-glow">¡Bienvenid@s al <span class="bg-gradient-to-r from-primary via-cyan-400 to-secondary bg-clip-text text-transparent">A.I. Hub</span>!</h1>
                        <p class="text-center text-sm text-foreground/70">{{ description }}</p>
                        <span class="mt-1 block h-1 w-32 bg-gradient-to-r from-primary via-cyan-400 to-secondary mx-auto"></span>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
.auth-glass-card {
  backdrop-filter: blur(12px);
  background: rgba(10, 10, 10, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.1);
}
</style>
