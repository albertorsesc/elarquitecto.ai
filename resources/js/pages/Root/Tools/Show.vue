<template>
    <Head :title="tool.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-background">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-glow animate-text-glow">Detalles de Herramienta</h1>
                <div class="flex space-x-3">
                    <CyberLink :href="route('root.tools.edit', tool.slug)" variant="outline" size="md">
                        Editar
                    </CyberLink>
                    <CyberLink :href="route('root.tools.index')" variant="outline" size="md">
                        Volver a Herramientas
                    </CyberLink>
                </div>
            </div>

            <!-- Tool Header -->
            <div class="glass-effect neon-border rounded-xl overflow-hidden">
                <!-- Banner Image -->
                <div v-if="tool.featured_image_url || tool.featured_image" class="relative h-48 md:h-64 w-full">
                    <img :src="tool.featured_image_url || tool.featured_image || '/img/logo.webp'" 
                         :alt="tool.title" 
                         class="h-full w-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-t from-background/90 to-transparent"></div>
                </div>
                
                <div class="relative p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold mb-2">{{ tool.title }}</h1>
                            <p class="text-sm text-muted-foreground mb-4">{{ tool.slug }}</p>
                            
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 text-sm rounded-full font-medium"
                                    :class="{
                                        'bg-green-100 text-green-800': tool.business_model === 'free',
                                        'bg-blue-100 text-blue-800': tool.business_model === 'freemium',
                                        'bg-red-100 text-red-800': tool.business_model === 'paid',
                                        'bg-purple-100 text-purple-800': tool.business_model === 'subscription',
                                        'bg-orange-100 text-orange-800': tool.business_model === 'one_time',
                                        'bg-cyan-100 text-cyan-800': tool.business_model === 'open_source',
                                    }">
                                    {{ formatBusinessModel(tool.business_model) }}
                                </span>
                                
                                <span v-if="tool.is_featured" class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800 font-medium">
                                    Destacado
                                </span>
                                
                                <span v-if="tool.published_at" class="text-green-500 text-sm font-medium">
                                    Publicado el {{ formatDate(tool.published_at) }}
                                </span>
                                <span v-else class="text-yellow-500 text-sm font-medium">
                                    Borrador
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content Area -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Excerpt -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-glow">Extracto</h2>
                        <p class="text-lg italic text-foreground/90">{{ tool.excerpt || 'Sin extracto' }}</p>
                    </div>

                    <!-- Description -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-glow">Descripción</h2>
                        <div class="whitespace-pre-wrap text-foreground/90">{{ tool.description || 'Sin descripción' }}</div>
                    </div>

                    <!-- SEO Information -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-glow">SEO</h2>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-foreground/70 mb-1">Título SEO</h3>
                                <p class="text-foreground">{{ tool.meta_title || tool.title }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-foreground/70 mb-1">Descripción SEO</h3>
                                <p class="text-foreground">{{ tool.meta_description || tool.excerpt || 'Sin descripción SEO' }}</p>
                            </div>
                            
                            <div v-if="tool.meta_keywords?.length">
                                <h3 class="text-sm font-medium text-foreground/70 mb-2">Palabras clave</h3>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="keyword in tool.meta_keywords"
                                        :key="keyword"
                                        class="px-2 py-1 text-xs bg-sidebar-accent text-sidebar-accent-foreground rounded-full"
                                    >
                                        {{ keyword }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Featured Image Preview -->
                    <div v-if="tool.featured_image_url || tool.featured_image" class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-glow">Imagen destacada</h2>
                        <div class="rounded-lg overflow-hidden border border-border/30">
                            <img :src="tool.featured_image_url || tool.featured_image || '/img/logo.webp'" 
                                 :alt="tool.title" 
                                 class="w-full h-auto object-cover" />
                        </div>
                        <p class="text-xs text-muted-foreground mt-2">
                            URL: {{ tool.featured_image_url || tool.featured_image }}
                        </p>
                    </div>
                    
                    <!-- Links -->
                    <div v-if="tool.website_url || tool.pricing_url || tool.documentation_url" 
                         class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-glow">Enlaces</h2>
                        <div class="space-y-3">
                            <a v-if="tool.website_url" 
                               :href="tool.website_url" 
                               target="_blank"
                               class="flex items-center justify-between p-3 rounded-lg bg-sidebar-accent/20 hover:bg-sidebar-accent/30 transition-colors group"
                            >
                                <span class="text-sm font-medium">Sitio web</span>
                                <svg class="w-4 h-4 text-primary group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                            
                            <a v-if="tool.pricing_url" 
                               :href="tool.pricing_url" 
                               target="_blank"
                               class="flex items-center justify-between p-3 rounded-lg bg-sidebar-accent/20 hover:bg-sidebar-accent/30 transition-colors group"
                            >
                                <span class="text-sm font-medium">Precios</span>
                                <svg class="w-4 h-4 text-primary group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                            
                            <a v-if="tool.documentation_url" 
                               :href="tool.documentation_url" 
                               target="_blank"
                               class="flex items-center justify-between p-3 rounded-lg bg-sidebar-accent/20 hover:bg-sidebar-accent/30 transition-colors group"
                            >
                                <span class="text-sm font-medium">Documentación</span>
                                <svg class="w-4 h-4 text-primary group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div v-if="tool.categories?.length" class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-glow">Categorías</h2>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="category in tool.categories"
                                :key="category.id"
                                class="px-3 py-1 text-sm bg-primary/20 text-primary-foreground rounded-full border border-primary/30"
                            >
                                {{ category.name }}
                            </span>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div v-if="tool.tags?.length" class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-glow">Etiquetas</h2>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="tag in tool.tags"
                                :key="tag.id"
                                class="px-2 py-1 text-xs bg-sidebar-accent text-sidebar-accent-foreground rounded-full"
                            >
                                {{ tag.name }}
                            </span>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="glass-effect neon-border rounded-xl p-6">
                        <h2 class="text-xl font-semibold mb-4 text-glow">Información</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-foreground/70">ID</span>
                                <span class="font-mono">{{ tool.id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-foreground/70">UUID</span>
                                <span class="font-mono text-xs">{{ tool.uuid }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-foreground/70">Creado</span>
                                <span>{{ formatDate(tool.created_at) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-foreground/70">Actualizado</span>
                                <span>{{ formatDate(tool.updated_at) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import CyberLink from '@/components/theme/CyberLink.vue'
import type { Tool, BreadcrumbItem } from '@/types'

interface Props {
    tool: Tool
}

const props = defineProps<Props>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Herramientas',
        href: route('root.tools.index'),
    },
    {
        title: props.tool.title,
        href: route('root.tools.show', props.tool.slug),
    }
]

const formatBusinessModel = (model: string): string => {
    const models: Record<string, string> = {
        free: 'Gratis',
        freemium: 'Freemium',
        paid: 'Pago',
        subscription: 'Suscripción',
        one_time: 'Pago único',
        open_source: 'Código abierto'
    }
    return models[model] || model
}

const formatDate = (date: string): string => {
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>

<style>
.glass-effect {
  background-color: hsl(var(--card)) !important;
}
</style>