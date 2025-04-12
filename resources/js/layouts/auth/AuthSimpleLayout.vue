<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

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
    <div class="dark flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10 antialiased overflow-x-hidden"
         style="background-image: radial-gradient(circle at 15% 50%, rgb(var(--secondary-rgb)) / 0.15, transparent 25%), radial-gradient(circle at 85% 30%, rgb(var(--accent-rgb)) / 0.15, transparent 25%); background-attachment: fixed; background-size: cover;">
        <div class="glass-effect w-full max-w-sm rounded-xl border border-white/10 p-8 relative shadow-[0_0_30px_rgba(124,58,237,0.3)]">
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
            
            <div class="relative z-10 flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link :href="route('home')" class="flex flex-col items-center gap-2 font-medium">
                        <div class="mb-1 flex h-9 w-9 items-center justify-center rounded-md">
                            <AppLogoIcon class="size-9 fill-current text-[var(--foreground)] dark:text-white" />
                        </div>
                        <span class="sr-only">{{ title }}</span>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium text-foreground">{{ title }}</h1>
                        <p class="text-center text-sm text-foreground/70">{{ description }}</p>
                        <!-- Underscore line similar to welcome blade -->
                        <span class="mt-1 block h-1 w-24 bg-gradient-to-r from-primary via-cyan-400 to-secondary mx-auto"></span>
                    </div>
                </div>
                <slot />
            </div>
        </div>
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

.glass-effect {
  backdrop-filter: blur(12px);
  background: rgba(10, 10, 10, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.1);
}
</style>
