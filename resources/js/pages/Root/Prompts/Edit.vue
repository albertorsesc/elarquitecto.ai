<script setup lang="ts">
import FloatingNeonLines from '@/components/theme/FloatingNeonLines.vue';
import FormButton from '@/components/theme/FormButton.vue';
import FormInput from '@/components/theme/FormInput.vue';
import FormTextarea from '@/components/theme/FormTextarea.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { MdEditor, XSSPlugin, config } from 'md-editor-v3';
import 'md-editor-v3/lib/style.css';
import { PropType, computed, reactive } from 'vue';
// import theme css
import '@vavt/cm-extension/dist/previewTheme/arknights.css';
// import existing language
import { SharedData } from '@/types';
import { Prompt } from '@/types/prompt';
import es_ES from '@vavt/cm-extension/dist/locale/es-ES';

config({
    editorConfig: {
        languageUserDefined: {
            'es-ES': es_ES,
        }
    },
    markdownItPlugins(plugins) {
        return [
            ...plugins,
            {
                type: 'xss',
                plugin: XSSPlugin,
                options: {},
            },
        ];
    },
})

const props = defineProps({
    prompt: {
        type: Object as PropType<Prompt>,
        required: true
    },
});

const page = usePage<SharedData>();
const errors = computed(() => page.props.errors || {});

const form = reactive({
    name: props.prompt.name,
    description: props.prompt.description,
    content: props.prompt.content,
    processing: false,
});

const submit = () => {
    console.log(form);
    form.processing = true;

    // Prepare form data with proper typing
    const formData: Record<string, any> = {
        name: form.name,
        description: form.description,
        content: form.content,
    };

    router.put(route('root.prompts.update', props.prompt.id), formData, {
        onSuccess: () => {
            router.visit(route('root.prompts.index'));
        },
        preserveScroll: true,
    });

    form.processing = false;
};
</script>

<template>
    <Head title="Edit Prompt" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-glow text-foreground">
                    Edit Prompt
                </h2>
                <Link
                    :href="route('root.prompts.index')"
                    class="rounded-md bg-background/80 px-4 py-2 text-sm font-medium text-foreground shadow-sm hover:bg-background/60 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 border border-white/10"
                >
                    Back to Prompts
                </Link>
            </div>
        </template>

        <!-- Background Effects -->
        <FloatingNeonLines variant="sparse" :opacity="0.2" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <GlassContainer
                    variant="dark"
                    :withBorders="true"
                    :withCorners="true"
                    rounded="xl"
                    padding="lg"
                >
                    <form @submit.prevent="submit" class="space-y-6">
                        <FormInput
                            id="name"
                            v-model="form.name"
                            label="Name"
                            placeholder="Introduce el nombre del prompt"
                            :error="errors.name"
                        />

                        <FormTextarea
                            id="description"
                            v-model="form.description"
                            label="Description"
                            placeholder="Describe el prompt"
                            :rows="3"
                            :error="errors.description"
                        />

                        <FormTextarea
                            id="content"
                            v-model="form.content"
                            label="Contenido"
                            placeholder="Escribe el contenido del prompt"
                            :rows="15"
                            :error="errors.content"
                        />
                        <p class="mt-1 text-sm text-foreground/70">
                            Puedes usar Markdown para formatear.
                        </p>

                        <MdEditor v-model="form.content" language="es-ES" />

                        <div class="flex items-center justify-end space-x-3">
                            <FormButton
                                type="button"
                                variant="outline"
                                @click="router.visit(route('root.prompts.index'))"
                            >
                                Cancel
                            </FormButton>
                            <FormButton
                                type="submit"
                                variant="primary"
                                :disabled="form.processing"
                            >
                                Actualizar Prompt
                            </FormButton>
                        </div>
                    </form>
                </GlassContainer>
            </div>
        </div>
    </AppLayout>
</template>
