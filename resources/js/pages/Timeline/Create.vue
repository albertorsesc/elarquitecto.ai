<script setup lang="ts">
import {reactive, watch} from "vue";
import { router } from '@inertiajs/vue3';

defineProps({ errors: Object });

type FormState = {
    title: String,
    slug: String,
    description: String,
    content: String,
    excerpt: String,
}

const form = reactive<FormState>({
    title: '',
    slug: '',
    description: '',
    content: '',
    excerpt: '',
})

// slugify the slug property based on title onchange
watch(() => form.title, (title: String) => {
    form.slug = title.toLowerCase().replace(/ /g, '-');
});

const store = () => {
    router.post('/timeline', {
        title: form.title,
        slug: form.slug,
        description: form.description,
        content: form.content,
        excerpt: form.excerpt,
    });
}
</script>

<template>
    <div class="divide-y divide-gray-900/10">
        <div class="grid grid-cols-1 gap-x-8 gap-y-8 py-10 md:grid-cols-3">
            <div class="px-4 sm:px-0">
                <h2 class="text-base/7 font-semibold text-gray-900">Publicacion para Timeline</h2>
                <p class="mt-1 text-sm/6 text-gray-600">This information will be displayed publicly so be careful what you share.</p>
            </div>

            <form @submit.prevent="store" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
                <div class="px-4 py-6 sm:p-8">
                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-full">
                            <label for="username" class="block text-sm/6 font-medium text-gray-900">
                                Titulo
                            </label>
                            <div class="mt-2">
                                <div class="flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                    <input v-model="form.title" type="text" id="username" class="block min-w-0 grow py-1.5 pl-1 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6" placeholder="janesmith">
                                </div>
                                <div v-if="errors.title" class="text-red-400">{{ errors.title }}</div>
                            </div>
                        </div>

                        <div class="sm:col-span-full">
                            <label for="username" class="block text-sm/6 font-medium text-gray-900">
                                Slug
                            </label>
                            <div class="mt-2">
                                <div class="flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                    <input v-model="form.slug" type="text" id="slug" class="block min-w-0 grow py-1.5 pl-1 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="excerpt" class="block text-sm/6 font-medium text-gray-900">Excerpt</label>
                            <div class="mt-2">
                                <textarea v-model="form.excerpt" id="excerpt" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                            </div>
                            <div v-if="errors.excerpt" class="text-red-400">{{ errors.excerpt }}</div>
                        </div>

                        <div class="col-span-full">
                            <label for="description" class="block text-sm/6 font-medium text-gray-900">Descripción</label>
                            <div class="mt-2">
                                <textarea v-model="form.description" id="description" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                            </div>
                            <div v-if="errors.description" class="text-red-400">{{ errors.description }}</div>
                        </div>

                        <div class="col-span-full">
                            <label for="content" class="block text-sm/6 font-medium text-gray-900">Contenido</label>
                            <div class="mt-2">
                                <textarea v-model="form.content" id="content" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                            </div>
                            <div v-if="errors.content" class="text-red-400">{{ errors.content }}</div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                    <button type="button" class="text-sm/6 font-semibold text-gray-900">Cancel</button>
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                </div>
            </form>
        </div>
    </div>

</template>

<style scoped>

</style>
