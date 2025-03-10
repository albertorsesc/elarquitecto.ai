<script setup lang="ts">
import FloatingNeonLines from '@/components/theme/FloatingNeonLines.vue';
import FormInput from '@/components/theme/FormInput.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    posts: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');

watch(search, (value) => {
    if (value === null) {
        value = '';
    }

    if (value.length === 0 || value.length >= 3) {
        router.get(
            route('root.articles.index'),
            { search: value },
            { preserveState: true, replace: true }
        );
    }
});

const deletePost = (postId: number) => {
    if (confirm('Are you sure you want to delete this post?')) {
        router.delete(route('blog.destroy', postId));
    }
};
</script>

<template>
    <Head title="Manage Blog Posts" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-glow text-foreground">
                    Manage Blog Posts
                </h2>
                <Link
                    :href="route('blog.create')"
                    class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
                >
                    Create New Post
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
                    <!-- Search -->
                    <div class="mb-6">
                        <FormInput
                            v-model="search"
                            placeholder="Search posts..."
                        />
                    </div>

                    <!-- Posts Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-background/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-foreground/70">
                                        Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-foreground/70">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-foreground/70">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-foreground/70">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-foreground/70">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10 bg-background/30">
                                <tr v-for="post in posts?.data" :key="post.id">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm font-medium text-foreground">{{ post.title }}</div>
                                        <div class="text-sm text-foreground/60">{{ post.excerpt ? post.excerpt.substring(0, 50) + '...' : '' }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-foreground">{{ post.category ? post.category.name : 'Uncategorized' }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            :class="[
                                                'inline-flex rounded-full px-2 text-xs font-semibold leading-5',
                                                post.published
                                                    ? 'bg-green-900/30 text-green-400 border border-green-500/30'
                                                    : 'bg-yellow-900/30 text-yellow-400 border border-yellow-500/30'
                                            ]"
                                        >
                                            {{ post.published ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-foreground/60">
                                        {{ post.published_at ? new Date(post.published_at).toLocaleDateString() : 'Not published' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <Link
                                                :href="route('blog.show', post.slug)"
                                                target="_blank"
                                                class="text-indigo-400 hover:text-indigo-300"
                                            >
                                                View
                                            </Link>
                                            <Link
                                                :href="route('blog.edit', post.id)"
                                                class="text-blue-400 hover:text-blue-300"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                type="button"
                                                class="text-red-400 hover:text-red-300"
                                                @click="deletePost(post.id)"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!posts?.data?.length">
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-foreground/60">
                                        No posts found.
                                        <Link :href="route('blog.create')" class="text-primary hover:underline">Create your first post</Link>.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-foreground/60">
                                Showing {{ posts?.from || 0 }} to {{ posts?.to || 0 }} of {{ posts?.total || 0 }} results
                            </div>
                            <div class="flex space-x-2">
                                <template v-for="(link, i) in posts?.links" :key="i">
                                    <Link
                                        v-if="link.url"
                                        :href="link.url"
                                        class="rounded px-4 py-2 text-sm"
                                        :class="[
                                            link.active
                                                ? 'bg-primary text-white'
                                                : 'bg-background/50 text-foreground border border-white/10 hover:bg-background/80'
                                        ]"
                                    >
                                        <span v-if="link.label.includes('Previous')">&laquo; Previous</span>
                                        <span v-else-if="link.label.includes('Next')">Next &raquo;</span>
                                        <span v-else>{{ link.label }}</span>
                                    </Link>
                                    <span
                                        v-else
                                        class="rounded px-4 py-2 text-sm bg-background/50 text-foreground/50 border border-white/10 opacity-50 cursor-not-allowed"
                                    >
                                        <span v-if="link.label.includes('Previous')">&laquo; Previous</span>
                                        <span v-else-if="link.label.includes('Next')">Next &raquo;</span>
                                        <span v-else>{{ link.label }}</span>
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>
                </GlassContainer>
            </div>
        </div>
    </AppLayout>
</template>
