<template>
    <Head title="Edit Tool" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 bg-background">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-glow animate-text-glow">Editar Herramienta</h1>
                <CyberLink :href="route('root.tools.index')" variant="outline" size="md">
                    Volver a Herramientas
                </CyberLink>
            </div>

            <!-- Edit Tool Form -->
            <div class="glass-effect neon-border rounded-xl p-6 max-w-4xl mx-auto w-full">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label for="title" class="block text-sm font-medium text-foreground/80">Título</label>
                        <AnimatedInputBorder 
                            id="title" 
                            v-model="form.title" 
                            placeholder="Nombre de la herramienta" 
                            required
                            @input="generateSlug"
                        />
                        <p v-if="form.errors.title" class="mt-1 text-sm text-red-500">{{ form.errors.title }}</p>
                    </div>

                    <!-- Slug -->
                    <div class="space-y-2">
                        <label for="slug" class="block text-sm font-medium text-foreground/80">
                            Slug (URL)
                            <span 
                                @click="generateSlug" 
                                class="ml-2 text-primary cursor-pointer text-xs hover:text-primary/80 transition-colors"
                            >
                                (Generar desde título)
                            </span>
                        </label>
                        <AnimatedInputBorder 
                            id="slug" 
                            v-model="form.slug" 
                            placeholder="nombre-de-la-herramienta" 
                            required
                        />
                        <p v-if="form.errors.slug" class="mt-1 text-sm text-red-500">{{ form.errors.slug }}</p>
                    </div>

                    <!-- Business Model and Featured -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Business Model -->
                        <div class="space-y-2">
                            <label for="business_model" class="block text-sm font-medium text-foreground/80">Modelo de negocio</label>
                            <select
                                id="business_model"
                                v-model="form.business_model"
                                class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground 
                                       focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 
                                       focus:ring-cyan-400/30 transition-all duration-300"
                                required
                            >
                                <option value="free">Gratis</option>
                                <option value="freemium">Freemium</option>
                                <option value="paid">Pago</option>
                                <option value="subscription">Suscripción</option>
                                <option value="one_time">Pago único</option>
                                <option value="open_source">Código abierto</option>
                            </select>
                            <p v-if="form.errors.business_model" class="mt-1 text-sm text-red-500">{{ form.errors.business_model }}</p>
                        </div>

                        <!-- Featured -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-foreground/80">Destacar</label>
                            <div class="flex items-center space-x-2 mt-2">
                                <input 
                                    type="checkbox" 
                                    id="is_featured" 
                                    v-model="form.is_featured"
                                    class="rounded border-white/20 bg-background/50 text-primary focus:ring-primary/30"
                                />
                                <label for="is_featured" class="text-sm">Destacar herramienta en la página principal</label>
                            </div>
                        </div>
                    </div>

                    <!-- Excerpt -->
                    <div class="space-y-2">
                        <label for="excerpt" class="block text-sm font-medium text-foreground/80">
                            Extracto
                            <span class="text-xs text-muted-foreground ml-1">(máx 500 caracteres)</span>
                        </label>
                        <textarea
                            id="excerpt"
                            v-model="form.excerpt"
                            rows="3"
                            maxlength="500"
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                            placeholder="Descripción breve de la herramienta"
                        ></textarea>
                        <div class="text-xs text-muted-foreground">
                            {{ form.excerpt?.length || 0 }}/500 caracteres
                        </div>
                        <p v-if="form.errors.excerpt" class="mt-1 text-sm text-red-500">{{ form.errors.excerpt }}</p>
                    </div>

                    <!-- Featured Image -->
                    <div class="space-y-2">
                        <label for="featured_image" class="block text-sm font-medium text-foreground/80">
                            Imagen Principal
                            <span class="text-xs text-muted-foreground ml-1">(JPG, PNG, WebP - máx 5MB)</span>
                        </label>
                        
                        <!-- Current Image -->
                        <div v-if="currentImageUrl && !imagePreview" class="mt-2">
                            <p class="text-xs text-muted-foreground mb-2">Imagen actual:</p>
                            <img :src="currentImageUrl" alt="Current" class="h-32 w-auto rounded-lg border border-white/10" />
                        </div>
                        
                        <input
                            type="file"
                            id="featured_image"
                            @change="handleImageUpload"
                            accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground 
                                   file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 
                                   file:text-sm file:font-semibold file:bg-primary/10 file:text-primary
                                   hover:file:bg-primary/20 focus:border-cyan-400/30 focus:outline-none 
                                   focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                        />
                        <p v-if="form.errors.featured_image" class="mt-1 text-sm text-red-500">{{ form.errors.featured_image }}</p>
                        
                        <!-- New Image Preview -->
                        <div v-if="imagePreview" class="mt-2">
                            <p class="text-xs text-muted-foreground mb-2">Nueva imagen:</p>
                            <img :src="imagePreview" alt="Preview" class="h-32 w-auto rounded-lg border border-white/10" />
                        </div>
                    </div>

                    <!-- Description (Markdown) -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-foreground/80">Descripción completa (Markdown)</label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="10"
                            class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300 font-mono text-sm"
                            placeholder="## Características principales
- Markdown soportado
- Usa encabezados, listas, etc."
                        ></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-500">{{ form.errors.description }}</p>
                    </div>

                    <!-- URLs Section -->
                    <div class="space-y-4 border-t border-border/30 pt-6">
                        <h3 class="text-lg font-medium text-foreground">Enlaces</h3>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div class="space-y-2">
                                <label for="website_url" class="block text-sm font-medium text-foreground/80">Sitio web</label>
                                <AnimatedInputBorder 
                                    id="website_url" 
                                    v-model="form.website_url" 
                                    type="url"
                                    placeholder="https://ejemplo.com" 
                                />
                                <p v-if="form.errors.website_url" class="mt-1 text-sm text-red-500">{{ form.errors.website_url }}</p>
                            </div>

                            <div class="space-y-2">
                                <label for="pricing_url" class="block text-sm font-medium text-foreground/80">Página de precios</label>
                                <AnimatedInputBorder 
                                    id="pricing_url" 
                                    v-model="form.pricing_url" 
                                    type="url"
                                    placeholder="https://ejemplo.com/pricing" 
                                />
                                <p v-if="form.errors.pricing_url" class="mt-1 text-sm text-red-500">{{ form.errors.pricing_url }}</p>
                            </div>

                            <div class="space-y-2">
                                <label for="documentation_url" class="block text-sm font-medium text-foreground/80">Documentación</label>
                                <AnimatedInputBorder 
                                    id="documentation_url" 
                                    v-model="form.documentation_url" 
                                    type="url"
                                    placeholder="https://ejemplo.com/docs" 
                                />
                                <p v-if="form.errors.documentation_url" class="mt-1 text-sm text-red-500">{{ form.errors.documentation_url }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Categories and Tags -->
                    <div class="space-y-4 border-t border-border/30 pt-6">
                        <h3 class="text-lg font-medium text-foreground">Categorización</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Categories -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-foreground/80">Categorías</label>
                                <div class="space-y-2 max-h-48 overflow-y-auto rounded-lg border border-white/10 p-3">
                                    <label
                                        v-for="category in categories"
                                        :key="category.id"
                                        class="flex items-center space-x-2 hover:bg-sidebar-accent/20 p-1 rounded transition-colors"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="category.id"
                                            v-model="form.categories"
                                            class="rounded border-white/20 bg-background/50 text-primary focus:ring-primary/30"
                                        />
                                        <span class="text-sm">{{ category.name }}</span>
                                    </label>
                                </div>
                                <p v-if="form.errors.categories" class="mt-1 text-sm text-red-500">{{ form.errors.categories }}</p>
                            </div>

                            <!-- Tags -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-foreground/80">Etiquetas</label>
                                <div class="space-y-2 max-h-48 overflow-y-auto rounded-lg border border-white/10 p-3">
                                    <label
                                        v-for="tag in tags"
                                        :key="tag.id"
                                        class="flex items-center space-x-2 hover:bg-sidebar-accent/20 p-1 rounded transition-colors"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="tag.id"
                                            v-model="form.tags"
                                            class="rounded border-white/20 bg-background/50 text-primary focus:ring-primary/30"
                                        />
                                        <span class="text-sm">{{ tag.name }}</span>
                                    </label>
                                </div>
                                <p v-if="form.errors.tags" class="mt-1 text-sm text-red-500">{{ form.errors.tags }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Section -->
                    <div class="space-y-4 border-t border-border/30 pt-6">
                        <h3 class="text-lg font-medium text-foreground">SEO</h3>
                        
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label for="meta_title" class="block text-sm font-medium text-foreground/80">
                                    Título SEO
                                    <span class="text-xs text-muted-foreground ml-1">(máx 60 caracteres)</span>
                                </label>
                                <AnimatedInputBorder 
                                    id="meta_title" 
                                    v-model="form.meta_title" 
                                    placeholder="Título para motores de búsqueda"
                                    maxlength="60"
                                />
                                <div class="text-xs text-muted-foreground">
                                    {{ form.meta_title?.length || 0 }}/60 caracteres
                                </div>
                                <p v-if="form.errors.meta_title" class="mt-1 text-sm text-red-500">{{ form.errors.meta_title }}</p>
                            </div>

                            <div class="space-y-2">
                                <label for="meta_description" class="block text-sm font-medium text-foreground/80">
                                    Descripción SEO
                                    <span class="text-xs text-muted-foreground ml-1">(máx 160 caracteres)</span>
                                </label>
                                <textarea
                                    id="meta_description"
                                    v-model="form.meta_description"
                                    rows="2"
                                    maxlength="160"
                                    class="w-full rounded-xl border border-white/10 bg-background/50 py-2 px-3 text-foreground placeholder:text-foreground/50 focus:border-cyan-400/30 focus:bg-background/70 focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300"
                                    placeholder="Descripción para motores de búsqueda"
                                ></textarea>
                                <div class="text-xs text-muted-foreground">
                                    {{ form.meta_description?.length || 0 }}/160 caracteres
                                </div>
                                <p v-if="form.errors.meta_description" class="mt-1 text-sm text-red-500">{{ form.errors.meta_description }}</p>
                            </div>

                            <div class="space-y-2">
                                <label for="meta_keywords" class="block text-sm font-medium text-foreground/80">Palabras clave</label>
                                <AnimatedInputBorder 
                                    id="meta_keywords" 
                                    v-model="keywordsInput" 
                                    @input="updateKeywords"
                                    placeholder="Separadas por comas: ia, inteligencia artificial, herramienta"
                                />
                                <p v-if="form.errors.meta_keywords" class="mt-1 text-sm text-red-500">{{ form.errors.meta_keywords }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Publication -->
                    <div class="space-y-2 border-t border-border/30 pt-6">
                        <label for="published_at" class="block text-sm font-medium text-foreground/80">Fecha de publicación</label>
                        <AnimatedInputBorder 
                            id="published_at" 
                            type="datetime-local"
                            v-model="form.published_at" 
                        />
                        <p v-if="form.errors.published_at" class="mt-1 text-sm text-red-500">{{ form.errors.published_at }}</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-border/30">
                        <button 
                            type="submit" 
                            class="neon-border w-full py-2 px-4 rounded-lg font-medium focus:outline-none transition-all duration-300
                                   border border-primary/30 bg-primary/10 text-primary-foreground hover:bg-primary/90 focus:ring-2 focus:ring-primary/50 focus:ring-offset-2"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Procesando...
                            </span>
                            <span v-else>Guardar Cambios</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import CyberLink from '@/components/theme/CyberLink.vue'
import AnimatedInputBorder from '@/components/theme/AnimatedInputBorder.vue'
import type { Category, Tag, Tool, BreadcrumbItem } from '@/types'

interface Props {
    tool: Tool
    categories: Category[]
    tags: Tag[]
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
        title: 'Editar',
        href: route('root.tools.edit', props.tool.slug),
    }
]

const form = useForm({
    title: props.tool.title,
    slug: props.tool.slug,
    excerpt: props.tool.excerpt || '',
    description: props.tool.description || '',
    business_model: props.tool.business_model,
    featured_image: null as File | null,
    gallery: props.tool.gallery || [],
    website_url: props.tool.website_url || '',
    pricing_url: props.tool.pricing_url || '',
    documentation_url: props.tool.documentation_url || '',
    meta_title: props.tool.meta_title || '',
    meta_description: props.tool.meta_description || '',
    meta_keywords: props.tool.meta_keywords || [] as string[],
    categories: props.tool.categories?.map(c => c.id) || [] as number[],
    tags: props.tool.tags?.map(t => t.id) || [] as number[],
    is_featured: props.tool.is_featured,
    published_at: props.tool.published_at ? 
        new Date(props.tool.published_at).toISOString().slice(0, 16) : 
        new Date().toISOString().slice(0, 16)
})

const keywordsInput = ref('')
const imagePreview = ref<string | null>(null)
const currentImageUrl = ref(props.tool.featured_image_url || props.tool.featured_image || '')

onMounted(() => {
    if (form.meta_keywords && form.meta_keywords.length > 0) {
        keywordsInput.value = form.meta_keywords.join(', ')
    }
})

const generateSlug = () => {
    if (form.title) {
        form.slug = form.title
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
    }
}

const updateKeywords = () => {
    form.meta_keywords = keywordsInput.value
        .split(',')
        .map(k => k.trim())
        .filter(k => k.length > 0)
}

const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement
    const file = target.files?.[0]
    
    if (file) {
        form.featured_image = file
        
        // Create preview
        const reader = new FileReader()
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string
        }
        reader.readAsDataURL(file)
    }
}

const submit = () => {
    // Use POST route when there's a file upload (required for FormData)
    if (form.featured_image) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT' // Laravel will treat this as a PUT request
        })).post(route('root.tools.update.post', props.tool.slug), {
            forceFormData: true,
            preserveScroll: true
        })
    } else {
        form.put(route('root.tools.update', props.tool.slug), {
            preserveScroll: true
        })
    }
}
</script>

<style>
.glass-effect {
  background-color: hsl(var(--card)) !important;
}

input[type="checkbox"] {
  @apply h-4 w-4;
  accent-color: hsl(var(--primary));
  outline: none !important;
}

input[type="checkbox"]:focus {
  @apply ring-1 ring-primary/50;
}

select {
  appearance: auto;
}
</style>