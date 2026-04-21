<template>
    <Head title="Tools" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 bg-background p-4">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="animate-text-glow text-2xl font-bold text-glow">Herramientas</h1>
                <CyberLink :href="route('root.tools.create')" variant="primary" size="md"> Nueva Herramienta </CyberLink>
            </div>

            <!-- Filters -->
            <div class="mb-6 flex gap-4">
                <AnimatedInputBorder id="search" v-model="form.search" placeholder="Buscar herramientas..." class="max-w-sm" @input="handleSearch" />
                <select
                    v-model="form.business_model"
                    @change="handleFilter"
                    class="rounded-xl border border-white/10 bg-background/50 px-3 py-2 text-foreground transition-all duration-300 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30"
                >
                    <option value="">Todos los modelos</option>
                    <option value="free">Gratis</option>
                    <option value="freemium">Freemium</option>
                    <option value="paid">Pago</option>
                    <option value="subscription">Suscripción</option>
                    <option value="one_time">Pago único</option>
                    <option value="open_source">Código abierto</option>
                </select>
            </div>

            <!-- Grid of Tool Cards -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <!-- Tool Card (repeated for each tool) -->
                <div
                    v-for="tool in tools.data"
                    :key="tool.id"
                    class="glass-effect neon-border group h-full overflow-hidden rounded-xl border border-border/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_15px_rgba(var(--primary-rgb),0.5)]"
                >
                    <!-- Card Image -->
                    <div class="relative h-40 w-full overflow-hidden">
                        <img
                            :src="tool.featured_image_url || tool.featured_image || '/img/logo.webp'"
                            :alt="tool.title"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                        />

                        <!-- Business Model Badge -->
                        <div class="absolute left-3 top-3 z-10">
                            <span
                                class="rounded-full px-2 py-1 text-xs font-medium backdrop-blur-sm"
                                :class="{
                                    'bg-green-500/80 text-white': tool.business_model === 'free',
                                    'bg-blue-500/80 text-white': tool.business_model === 'freemium',
                                    'bg-red-500/80 text-white': tool.business_model === 'paid',
                                    'bg-purple-500/80 text-white': tool.business_model === 'subscription',
                                    'bg-orange-500/80 text-white': tool.business_model === 'one_time',
                                    'bg-cyan-500/80 text-white': tool.business_model === 'open_source',
                                }"
                            >
                                {{ formatBusinessModel(tool.business_model) }}
                            </span>
                        </div>

                        <!-- Featured Badge -->
                        <div v-if="tool.is_featured" class="absolute right-3 top-3 z-10">
                            <span class="rounded-full bg-yellow-500/80 px-2 py-1 text-xs font-medium text-white backdrop-blur-sm"> Destacado </span>
                        </div>

                        <!-- Status Indicator -->
                        <div class="absolute bottom-3 right-3 z-10">
                            <span
                                v-if="tool.published_at"
                                class="rounded-full bg-green-500/80 px-2 py-1 text-xs font-medium text-white backdrop-blur-sm"
                            >
                                Publicado
                            </span>
                            <span v-else class="rounded-full bg-yellow-500/80 px-2 py-1 text-xs font-medium text-white backdrop-blur-sm">
                                Borrador
                            </span>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="flex flex-col gap-2 p-4">
                        <h3 class="line-clamp-1 text-lg font-semibold transition-colors group-hover:text-primary">
                            {{ tool.title }}
                        </h3>

                        <p class="line-clamp-2 min-h-[2.5rem] text-sm text-muted-foreground">
                            {{ tool.excerpt || 'Sin descripción disponible' }}
                        </p>

                        <!-- Categories -->
                        <div v-if="tool.categories && tool.categories.length > 0" class="mt-1 flex flex-wrap gap-1">
                            <span
                                v-for="category in tool.categories.slice(0, 3)"
                                :key="category.id"
                                class="rounded-full border border-primary/30 bg-primary/20 px-2 py-0.5 text-xs text-primary-foreground"
                            >
                                {{ category.name }}
                            </span>
                            <span
                                v-if="tool.categories.length > 3"
                                class="rounded-full bg-sidebar-accent px-2 py-0.5 text-xs text-sidebar-accent-foreground"
                            >
                                +{{ tool.categories.length - 3 }}
                            </span>
                        </div>

                        <!-- Tags -->
                        <div v-if="tool.tags && tool.tags.length > 0" class="flex flex-wrap gap-1">
                            <span
                                v-for="tag in tool.tags.slice(0, 2)"
                                :key="tag.id"
                                class="rounded-full bg-sidebar-accent px-2 py-0.5 text-xs text-sidebar-accent-foreground"
                            >
                                {{ tag.name }}
                            </span>
                            <span
                                v-if="tool.tags.length > 2"
                                class="rounded-full bg-sidebar-accent/50 px-2 py-0.5 text-xs text-sidebar-accent-foreground"
                            >
                                +{{ tool.tags.length - 2 }}
                            </span>
                        </div>

                        <!-- Footer -->
                        <div class="mt-3 flex items-center justify-between text-xs text-muted-foreground">
                            <span class="flex items-center gap-2">
                                <span v-if="tool.website_url" class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                        />
                                    </svg>
                                    Web
                                </span>
                                <span v-if="tool.documentation_url" class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                    Docs
                                </span>
                            </span>
                            <span>{{ formatDate(tool.updated_at) }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="mt-3 space-y-2 border-t border-border/30 pt-3">
                            <CyberLink :href="route('root.tools.show', tool.slug)" variant="outline" size="sm" :fullWidth="true">
                                Ver Detalles
                            </CyberLink>
                            <div class="flex gap-2">
                                <CyberLink :href="route('root.tools.edit', tool.slug)" variant="outline" size="sm" class="flex-1"> Editar </CyberLink>
                                <button
                                    @click="deleteTool(tool)"
                                    class="flex-1 rounded-md border border-red-500/30 px-3 py-1.5 text-xs text-red-500 transition-colors hover:bg-red-500/10"
                                >
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State (shown when no tools) -->
                <div
                    v-if="!tools.data || tools.data.length === 0"
                    class="col-span-full flex flex-col items-center justify-center gap-4 p-8 text-center"
                >
                    <div class="rounded-full bg-muted p-3">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 text-muted-foreground"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"
                            />
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-semibold">No hay herramientas</h3>
                        <p class="text-muted-foreground">Comienza agregando tu primera herramienta.</p>
                    </div>
                    <CyberLink :href="route('root.tools.create')" variant="primary" size="md"> Agregar Primera Herramienta </CyberLink>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="tools.links && tools.links.length > 3" class="mt-6 flex justify-center">
                <nav class="flex gap-1">
                    <Link
                        v-for="link in tools.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        class="rounded-lg px-3 py-2 text-sm transition-all"
                        :class="{
                            'neon-border bg-primary text-primary-foreground': link.active,
                            'text-foreground hover:bg-sidebar-accent': !link.active && link.url,
                            'cursor-not-allowed opacity-50': !link.url,
                        }"
                        :disabled="!link.url"
                    >
                        <span v-html="link.label"></span>
                    </Link>
                </nav>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AnimatedInputBorder from '@/components/theme/AnimatedInputBorder.vue';
import CyberLink from '@/components/theme/CyberLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, Tool } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { debounce } from 'es-toolkit';
import { reactive } from 'vue';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedTools {
    data: Tool[];
    links: PaginationLink[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

interface Props {
    tools: PaginatedTools;
    filters?: {
        search?: string;
        business_model?: string;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Herramientas',
        href: route('root.tools.index'),
    },
];

const form = reactive({
    search: props.filters?.search || '',
    business_model: props.filters?.business_model || '',
});

const handleSearch = debounce(() => {
    router.get(route('root.tools.index'), form, {
        preserveState: true,
        preserveScroll: true,
    });
}, 300);

const handleFilter = () => {
    router.get(route('root.tools.index'), form, {
        preserveState: true,
        preserveScroll: true,
    });
};

const formatBusinessModel = (model: string): string => {
    const models: Record<string, string> = {
        free: 'Gratis',
        freemium: 'Freemium',
        paid: 'Pago',
        subscription: 'Suscripción',
        one_time: 'Pago único',
        open_source: 'Código abierto',
    };
    return models[model] || model;
};

const formatDate = (date: string): string => {
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const deleteTool = (tool: Tool) => {
    if (confirm(`¿Estás seguro de eliminar "${tool.title}"?`)) {
        router.delete(route('root.tools.destroy', tool.slug));
    }
};
</script>

<style>
.glass-effect {
    background-color: hsl(var(--card)) !important;
}

/* Force dark mode for consistent card appearance */
html:not(.dark) .force-dark {
    --background: 226 30% 12%;
    --foreground: 213 10% 95%;
    --card: 226 30% 15% / 0.7;
    --card-foreground: 213 10% 95%;
    color-scheme: dark;
}
</style>
