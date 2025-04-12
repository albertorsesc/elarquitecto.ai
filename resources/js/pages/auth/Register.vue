<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import AnimatedInputBorder from '@/components/theme/AnimatedInputBorder.vue';
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
                    <AnimatedInputBorder 
                        id="name" 
                        type="text" 
                        required 
                        autofocus 
                        :tabindex="1" 
                        autocomplete="name" 
                        v-model="form.name" 
                        placeholder="Nombre completo"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email" class="text-foreground/80">Correo electrónico</Label>
                    <AnimatedInputBorder 
                        id="email" 
                        type="email" 
                        required 
                        :tabindex="2" 
                        autocomplete="email" 
                        v-model="form.email" 
                        placeholder="correo@ejemplo.com" 
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" class="text-foreground/80">Contraseña</Label>
                    <AnimatedInputBorder
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Contraseña"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation" class="text-foreground/80">Confirmar contraseña</Label>
                    <AnimatedInputBorder
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Confirmar contraseña"
                    />
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
