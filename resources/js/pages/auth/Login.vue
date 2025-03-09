<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-background text-foreground">
        <!-- Navigation -->
        <header class="fixed top-0 z-50 w-full">
            <nav class="glass-effect relative border-b border-white/10 bg-background/70 px-2 py-3 backdrop-blur-xl sm:py-4">
                <!-- Animated top border -->
                <div class="absolute inset-x-0 top-0 h-[1px]">
                    <div class="absolute inset-0 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-30"></div>
                </div>

                <div class="mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-2 px-2">
                    <!-- Logo with glow -->
                    <a href="/" class="group flex items-center">
                        <img src="/logo.png" alt="El Arquitecto A.I. Logo" class="h-8 w-auto transition-all duration-300 group-hover:shadow-[0_0_15px_rgba(124,58,237,0.3)] sm:h-10" />
                    </a>
                </div>
            </nav>
        </header>

        <main class="relative pt-20">
            <!-- Floating neon lines for desktop -->
            <div class="pointer-events-none fixed inset-0 hidden sm:block">
                <!-- Horizontal lines -->
                <div class="absolute left-0 top-1/3 h-[1px] w-1/4 animate-neon-slide-right bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-20"></div>
                <div class="absolute right-0 top-2/3 h-[1px] w-1/4 animate-neon-slide-left bg-gradient-to-r from-transparent via-primary to-transparent opacity-20"></div>
                <!-- Vertical lines -->
                <div class="absolute left-1/3 top-0 h-1/4 w-[1px] animate-neon-slide-down bg-gradient-to-b from-transparent via-secondary to-transparent opacity-20"></div>
                <div class="absolute right-2/3 top-0 h-1/4 w-[1px] animate-neon-slide-down-delayed bg-gradient-to-b from-transparent via-accent to-transparent opacity-20"></div>
            </div>

            <Head title="Log in" />

            <div class="flex min-h-[80vh] flex-col items-center justify-center px-4 py-12">
                <div class="glass-effect relative w-full max-w-md overflow-hidden rounded-xl border border-white/20 bg-background/80 p-8 shadow-[0_0_30px_rgba(124,58,237,0.3)]">
                    <!-- Center logo with semi-transparency -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <img src="/logo.png" alt="El Arquitecto A.I. Logo" class="h-64 w-auto opacity-[0.07]" />
                    </div>

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

                    <div class="relative z-10 flex flex-col items-center gap-6">
                        <div class="flex flex-col items-center gap-2">
                            <h1 class="text-2xl font-medium text-foreground">Log in to your account</h1>
                            <p class="text-center text-sm text-foreground/70">Enter your email and password below to log in</p>
                        </div>

                        <div v-if="status" class="w-full rounded-md bg-green-500/10 p-3 text-center text-sm font-medium text-green-600">
                            {{ status }}
                        </div>

                        <form @submit.prevent="submit" class="w-full">
                            <div class="grid gap-6">
                                <div class="grid gap-2">
                                    <Label for="email" class="text-foreground/80">Email address</Label>
                                    <div class="group relative">
                                        <Input
                                            id="email"
                                            type="email"
                                            required
                                            autofocus
                                            :tabindex="1"
                                            autocomplete="email"
                                            v-model="form.email"
                                            placeholder="email@example.com"
                                            class="peer relative z-10 w-full rounded-xl border border-white/10 bg-background/50 py-2 pl-3 pr-4 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                                        />
                                        <!-- Animated border effect -->
                                        <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>
                                    </div>
                                    <InputError :message="form.errors.email" />
                                </div>

                                <div class="grid gap-2">
                                    <div class="flex items-center justify-between">
                                        <Label for="password" class="text-foreground/80">Password</Label>
                                        <TextLink
                                            v-if="canResetPassword"
                                            :href="route('password.request')"
                                            class="text-sm text-primary hover:text-primary/80 transition-colors"
                                            :tabindex="5"
                                        >
                                            Forgot password?
                                        </TextLink>
                                    </div>
                                    <div class="group relative">
                                        <Input
                                            id="password"
                                            type="password"
                                            required
                                            :tabindex="2"
                                            autocomplete="current-password"
                                            v-model="form.password"
                                            placeholder="Password"
                                            class="peer relative z-10 w-full rounded-xl border border-white/10 bg-background/50 py-2 pl-3 pr-4 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                                        />
                                        <!-- Animated border effect -->
                                        <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>
                                    </div>
                                    <InputError :message="form.errors.password" />
                                </div>

                                <div class="flex items-center justify-between" :tabindex="3">
                                    <Label for="remember" class="flex items-center space-x-3 text-foreground/80">
                                        <Checkbox id="remember" v-model:checked="form.remember" :tabindex="4" class="border-white/20 data-[state=checked]:bg-primary data-[state=checked]:text-white" />
                                        <span>Remember me</span>
                                    </Label>
                                </div>

                                <Button
                                    type="submit"
                                    class="neon-border mt-4 w-full rounded bg-primary px-4 py-2 text-white transition-all hover:bg-primary/80 disabled:opacity-70"
                                    :tabindex="4"
                                    :disabled="form.processing"
                                >
                                    <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                                    Log in
                                </Button>
                            </div>

                            <div class="mt-6 text-center text-sm text-foreground/60">
                                Don't have an account?
                                <TextLink
                                    :href="route('register')"
                                    :tabindex="5"
                                    class="text-primary hover:text-primary/80 transition-colors"
                                >
                                    Sign up
                                </TextLink>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative border-t border-white/10 bg-background/90 py-6">
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

.glass-effect {
  backdrop-filter: blur(12px);
  background: rgba(10, 10, 10, 0.2);
}

.neon-border {
  position: relative;
  overflow: hidden;
}

.neon-border::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: inherit;
  padding: 1px;
  background: linear-gradient(to right, var(--primary), var(--cyan-400), var(--secondary));
  mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
  -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;
  opacity: 0.5;
  transition: opacity 0.3s ease;
}

.neon-border:hover::before {
  opacity: 1;
}
</style>
