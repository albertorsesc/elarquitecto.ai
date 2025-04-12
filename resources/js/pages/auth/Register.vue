<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="" description="Crea tu cuenta y descubre el poder de la inteligencia artificial">
        <Head title="Registrarse" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name" class="text-foreground/80">Nombre</Label>
                    <div class="group relative">
                        <Input 
                            id="name" 
                            type="text" 
                            required 
                            autofocus 
                            :tabindex="1" 
                            autocomplete="name" 
                            v-model="form.name" 
                            placeholder="Nombre completo"
                            class="peer relative z-10 w-full rounded-xl border border-white/10 bg-background/50 py-2 pl-3 pr-4 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                        />
                        <!-- Animated border effect -->
                        <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>
                    </div>
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email" class="text-foreground/80">Correo electrónico</Label>
                    <div class="group relative">
                        <Input 
                            id="email" 
                            type="email" 
                            required 
                            :tabindex="2" 
                            autocomplete="email" 
                            v-model="form.email" 
                            placeholder="correo@ejemplo.com" 
                            class="peer relative z-10 w-full rounded-xl border border-white/10 bg-background/50 py-2 pl-3 pr-4 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                        />
                        <!-- Animated border effect -->
                        <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>
                    </div>
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" class="text-foreground/80">Contraseña</Label>
                    <div class="group relative">
                        <Input
                            id="password"
                            type="password"
                            required
                            :tabindex="3"
                            autocomplete="new-password"
                            v-model="form.password"
                            placeholder="Contraseña"
                            class="peer relative z-10 w-full rounded-xl border border-white/10 bg-background/50 py-2 pl-3 pr-4 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                        />
                        <!-- Animated border effect -->
                        <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>
                    </div>
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation" class="text-foreground/80">Confirmar contraseña</Label>
                    <div class="group relative">
                        <Input
                            id="password_confirmation"
                            type="password"
                            required
                            :tabindex="4"
                            autocomplete="new-password"
                            v-model="form.password_confirmation"
                            placeholder="Confirmar contraseña"
                            class="peer relative z-10 w-full rounded-xl border border-white/10 bg-background/50 py-2 pl-3 pr-4 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                        />
                        <!-- Animated border effect -->
                        <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>
                    </div>
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button 
                    type="submit" 
                    class="neon-border mt-2 w-full" 
                    tabindex="5" 
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                    Crear cuenta
                </Button>
            </div>

            <div class="text-center text-sm text-foreground/60">
                ¿Ya tienes una cuenta?
                <TextLink 
                    :href="route('login')" 
                    class="text-primary hover:text-primary/80 transition-colors" 
                    :tabindex="6"
                >
                    Iniciar sesión
                </TextLink>
            </div>
        </form>
    </AuthBase>
</template>
