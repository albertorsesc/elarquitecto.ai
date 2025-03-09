<script setup lang="ts">
import FormButton from '@/components/theme/FormButton.vue';
import FormInput from '@/components/theme/FormInput.vue';
import FormTextarea from '@/components/theme/FormTextarea.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { reactive, ref, watch } from "vue";

interface Tag {
    id: number;
    name: string;
}

// Define props with proper types
const props = defineProps({
    errors: Object,
    tags: {
        type: Array as () => Tag[],
        default: () => []
    }
});

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
});

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
};

// For the dropdown state
const isDropdownOpen = ref(false);
const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

// Toggle tag selection
const toggleTag = (tagId: number) => {
    const index = form.tags.indexOf(tagId);
    if (index === -1) {
        form.tags.push(tagId);
    } else {
        form.tags.splice(index, 1);
    }
};

// Check if a tag is selected
const isTagSelected = (tagId: number) => {
    return form.tags.includes(tagId);
};

// Get selected tag names for display
const selectedTagNames = () => {
    return props.tags
        .filter(tag => form.tags.includes(tag.id))
        .map(tag => tag.name)
        .join(', ');
};
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

                            <!-- Tags selection -->
                            <div class="sm:col-span-full">
                                <label for="tags-input" class="block text-sm font-medium text-foreground">Tags</label>
                                <div class="mt-2 relative">
                                    <!-- Custom dropdown input -->
                                    <div class="cyberpunk-dropdown group">
                                        <div
                                            @click="toggleDropdown"
                                            class="cyberpunk-dropdown-input"
                                            :class="{ 'active': isDropdownOpen }"
                                        >
                                            <span v-if="form.tags.length === 0" class="text-foreground/50">Select tags...</span>
                                            <span v-else class="text-foreground">{{ selectedTagNames() }}</span>
                                            <div class="cyberpunk-dropdown-arrow">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Dropdown options -->
                                        <div v-if="isDropdownOpen" class="cyberpunk-dropdown-options">
                                            <div
                                                v-for="tag in props.tags"
                                                :key="tag.id"
                                                @click="toggleTag(tag.id)"
                                                class="cyberpunk-dropdown-option"
                                                :class="{ 'selected': isTagSelected(tag.id) }"
                                            >
                                                <span>{{ tag.name }}</span>
                                                <svg v-if="isTagSelected(tag.id)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-primary">
                                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Animated border effect on focus -->
                                        <div class="absolute bottom-0 left-0 h-[1px] w-0 bg-gradient-to-r from-primary via-cyan-400 to-secondary transition-all duration-300 group-focus-within:w-full"></div>
                                    </div>

                                    <div v-if="errors?.tags" class="mt-1 text-sm text-red-400">{{ errors.tags }}</div>
                                </div>
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

/* Custom styling for cyberpunk dropdown */
.cyberpunk-dropdown {
    position: relative;
    width: 100%;
}

.cyberpunk-dropdown-input {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    min-height: 38px;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: var(--color-foreground);
    background-color: var(--color-background, rgba(17, 24, 39, 0.8));
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 0.375rem;
    outline: 1px solid rgba(255, 255, 255, 0.2);
    outline-offset: -1px;
    cursor: pointer;
    transition: all 0.2s ease;
    backdrop-filter: blur(4px);
}

.cyberpunk-dropdown-input.active {
    outline: 2px solid rgba(124, 58, 237, 1);
    outline-offset: -2px;
    box-shadow: 0 0 8px rgba(124, 58, 237, 0.4);
}

.cyberpunk-dropdown-input:hover {
    border-color: rgba(124, 58, 237, 0.5);
}

.cyberpunk-dropdown-arrow {
    color: rgba(255, 255, 255, 0.5);
    transition: transform 0.2s ease;
}

.cyberpunk-dropdown-input.active .cyberpunk-dropdown-arrow {
    transform: rotate(180deg);
    color: rgba(124, 58, 237, 1);
}

.cyberpunk-dropdown-options {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 10;
    max-height: 200px;
    overflow-y: auto;
    margin-top: 4px;
    background-color: rgba(17, 24, 39, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0.375rem;
    box-shadow: 0 4px 20px -1px rgba(0, 0, 0, 0.3), 0 0 15px -3px rgba(124, 58, 237, 0.2);
    backdrop-filter: blur(12px);
}

.cyberpunk-dropdown-option {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    transition: background-color 0.2s ease;
    color: var(--color-foreground, rgba(255, 255, 255, 0.9));
}

.cyberpunk-dropdown-option:hover {
    background-color: rgba(124, 58, 237, 0.2);
}

.cyberpunk-dropdown-option.selected {
    background-color: rgba(124, 58, 237, 0.3);
    color: var(--color-foreground, rgba(255, 255, 255, 1));
}

/* Scrollbar styling */
.cyberpunk-dropdown-options::-webkit-scrollbar {
    width: 6px;
}

.cyberpunk-dropdown-options::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 3px;
}

.cyberpunk-dropdown-options::-webkit-scrollbar-thumb {
    background: rgba(124, 58, 237, 0.5);
    border-radius: 3px;
}

.cyberpunk-dropdown-options::-webkit-scrollbar-thumb:hover {
    background: rgba(124, 58, 237, 0.7);
}

/* Neon glow effects */
.cyberpunk-dropdown-input:focus,
.cyberpunk-dropdown-input.active {
    box-shadow: 0 0 10px rgba(124, 58, 237, 0.4);
}

/* Animated border for the dropdown */
.cyberpunk-dropdown:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 1px;
    background: linear-gradient(to right, transparent, var(--color-primary, #7C3AED), var(--color-cyan, #22D3EE), transparent);
    transition: width 0.3s ease;
    opacity: 0;
}

.cyberpunk-dropdown:hover:after,
.cyberpunk-dropdown:focus-within:after {
    width: 100%;
    opacity: 0.7;
}
</style>
