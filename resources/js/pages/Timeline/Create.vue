<script setup lang="ts">
import FormButton from '@/components/theme/FormButton.vue';
import FormInput from '@/components/theme/FormInput.vue';
import FormTextarea from '@/components/theme/FormTextarea.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { reactive, watch } from "vue";

defineProps({ errors: Object });

type FormState = {
    title: string,
    slug: string,
    description: string,
    content: string,
    excerpt: string,
    tags: Array<number>,
}

const form = reactive<FormState>({
    title: '',
    slug: '',
    description: '',
    content: '',
    excerpt: '',
    tags: [],
})

// slugify the slug property based on title onchange
watch(() => form.title, (title: string) => {
    form.slug = title.toLowerCase().replace(/ /g, '-');
});

const store = () => {
    router.post('/timeline', {
        title: form.title,
        slug: form.slug,
        description: form.description,
        content: form.content,
        excerpt: form.excerpt,
        tags: form.tags,
    });
}
</script>

<template>
    <AppLayout>
        <div class="relative py-10 px-4 sm:px-6 lg:px-8">
            <!-- Page header with neon glow -->
            <h1 class="text-glow-medium mb-8 text-center text-2xl font-bold text-primary sm:text-3xl">
                Crear Publicación para Timeline
            </h1>

            <div class="mx-auto max-w-4xl">
                <GlassContainer
                    variant="default"
                    :withBorders="true"
                    :withCorners="true"
                    rounded="xl"
                    padding="lg"
                    borderColor="primary"
                >
                    <form @submit.prevent="store" class="space-y-8">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Title input -->
                            <div class="sm:col-span-full">
                                <FormInput
                                    v-model="form.title"
                                    id="title"
                                    label="Titulo"
                                    placeholder="Ingrese el título"
                                    :error="errors?.title"
                                />
                            </div>

                            <!-- Slug input -->
                            <div class="sm:col-span-full">
                                <FormInput
                                    v-model="form.slug"
                                    id="slug"
                                    label="Slug"
                                    :disabled="true"
                                    :error="errors?.slug"
                                />
                            </div>

                            <!-- Excerpt textarea -->
                            <div class="sm:col-span-full">
                                <FormTextarea
                                    v-model="form.excerpt"
                                    id="excerpt"
                                    label="Excerpt"
                                    placeholder="Breve resumen del contenido"
                                    :error="errors?.excerpt"
                                />
                            </div>

                            <!-- Description textarea -->
                            <div class="sm:col-span-full">
                                <FormTextarea
                                    v-model="form.description"
                                    id="description"
                                    label="Descripción"
                                    placeholder="Descripción detallada"
                                    :error="errors?.description"
                                />
                            </div>

                            <!-- Content textarea -->
                            <div class="sm:col-span-full">
                                <FormTextarea
                                    v-model="form.content"
                                    id="content"
                                    label="Contenido"
                                    :rows="6"
                                    placeholder="Contenido completo"
                                    :error="errors?.content"
                                />
                            </div>
                        </div>

                        <!-- Form actions -->
                        <div class="flex items-center justify-end gap-x-4 border-t border-white/10 pt-6">
                            <FormButton
                                type="button"
                                variant="text"
                            >
                                Cancelar
                            </FormButton>
                            <FormButton
                                type="submit"
                                variant="primary"
                            >
                                Guardar
                            </FormButton>
                        </div>
                    </form>
                </GlassContainer>

                <!-- Corner accents for the page -->
                <div class="absolute left-0 top-0 h-16 w-16">
                    <div class="absolute left-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-primary via-transparent to-transparent"></div>
                    <div class="absolute left-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-r from-primary via-transparent to-transparent"></div>
                </div>
                <div class="absolute right-0 top-0 h-16 w-16">
                    <div class="absolute right-0 top-0 h-full w-[1px] animate-glow bg-gradient-to-b from-cyan-400 via-transparent to-transparent"></div>
                    <div class="absolute right-0 top-0 h-[1px] w-full animate-glow bg-gradient-to-l from-cyan-400 via-transparent to-transparent"></div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@keyframes glow {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.8; }
}

.animate-glow {
  animation: glow 3s infinite;
}
</style>
