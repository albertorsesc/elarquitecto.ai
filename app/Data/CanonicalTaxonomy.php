<?php

namespace App\Data;

/**
 * Single source of truth for taxonomy across tools, prompts, and articles.
 *
 * Rules:
 *   - slug: ASCII kebab-case, no accents. Used in URLs, validation, and for
 *           public display of tags (cards + profile pages).
 *   - name: Spanish sentence case with accents. Used for admin UI and the
 *           public display of CATEGORIES (tags display the slug).
 *   - acronyms (AI, IDE, LLM, CLI, MCP, API) stay uppercase in the name
 *           even under sentence case rules.
 *
 * Any taxonomy change lives here — the migration
 * NormalizeCategoriesAndTags reads this class to bring production into
 * alignment on the next deploy.
 */
class CanonicalTaxonomy
{
    /**
     * Canonical categories. Slugs that already exist on production are
     * preserved so existing URLs keep working (SEO).
     *
     * @return array<int, array{slug: string, name: string, description: string}>
     */
    public static function categories(): array
    {
        return [
            [
                'slug' => 'ai',
                'name' => 'AI',
                'description' => 'Sistemas y modelos de inteligencia artificial — LLMs, generación de imágenes, voz y capacidades generales de IA.',
            ],
            [
                'slug' => 'codigo',
                'name' => 'Código',
                'description' => 'Herramientas para desarrollo de software, programación y productividad de código.',
            ],
            [
                'slug' => 'creacion-de-contenido',
                'name' => 'Creación de contenido',
                'description' => 'Herramientas para crear contenido escrito, visual y multimedia de alta calidad.',
            ],
            [
                'slug' => 'productividad',
                'name' => 'Productividad',
                'description' => 'Herramientas para automatizar tareas repetitivas y trabajar con mayor enfoque.',
            ],
            [
                'slug' => 'estrategia',
                'name' => 'Estrategia',
                'description' => 'Recursos para pensar, planificar y tomar decisiones estratégicas.',
            ],
            [
                'slug' => 'emprendimiento',
                'name' => 'Emprendimiento',
                'description' => 'Recursos para fundadores, freelancers y emprendedores en LATAM.',
            ],
            [
                'slug' => 'agentes',
                'name' => 'Agentes',
                'description' => 'Sistemas de IA que pueden actuar de forma autónoma o semi-autónoma para completar tareas.',
            ],
            [
                'slug' => 'machine-learning',
                'name' => 'Machine Learning',
                'description' => 'Conceptos, algoritmos e implementaciones de aprendizaje automático.',
            ],
            [
                'slug' => 'automatizacion',
                'name' => 'Automatización',
                'description' => 'Herramientas y técnicas para automatizar flujos de trabajo y procesos.',
            ],
        ];
    }

    /**
     * Canonical tags. Each tag belongs to exactly one parent category.
     *
     * @return array<int, array{slug: string, name: string, category: string}>
     */
    public static function tags(): array
    {
        return [
            // Código
            ['slug' => 'cli', 'name' => 'CLI', 'category' => 'codigo'],
            ['slug' => 'ide', 'name' => 'IDE', 'category' => 'codigo'],
            ['slug' => 'codigo-abierto', 'name' => 'Código abierto', 'category' => 'codigo'],
            ['slug' => 'mcp', 'name' => 'MCP', 'category' => 'codigo'],
            ['slug' => 'api-tutorial', 'name' => 'API tutorial', 'category' => 'codigo'],
            ['slug' => 'guias', 'name' => 'Guías', 'category' => 'codigo'],

            // AI / general
            ['slug' => 'llm', 'name' => 'LLM', 'category' => 'ai'],
            ['slug' => 'llm-ops', 'name' => 'LLM Ops', 'category' => 'ai'],
            ['slug' => 'prompt-engineering', 'name' => 'Prompt engineering', 'category' => 'ai'],

            // Agentes
            ['slug' => 'multi-agente', 'name' => 'Multi-agente', 'category' => 'agentes'],
            ['slug' => 'agente-de-razonamiento', 'name' => 'Agente de razonamiento', 'category' => 'agentes'],

            // Creación de contenido
            ['slug' => 'redes-sociales', 'name' => 'Redes sociales', 'category' => 'creacion-de-contenido'],
            ['slug' => 'video', 'name' => 'Video', 'category' => 'creacion-de-contenido'],
            ['slug' => 'movil', 'name' => 'Móvil', 'category' => 'creacion-de-contenido'],

            // Estrategia
            ['slug' => 'modelos-mentales', 'name' => 'Modelos mentales', 'category' => 'estrategia'],
            ['slug' => 'decisiones', 'name' => 'Decisiones', 'category' => 'estrategia'],
            ['slug' => 'planificacion', 'name' => 'Planificación', 'category' => 'estrategia'],
            ['slug' => 'vision', 'name' => 'Visión', 'category' => 'estrategia'],
            ['slug' => 'analisis', 'name' => 'Análisis', 'category' => 'estrategia'],
            ['slug' => 'transformacion', 'name' => 'Transformación', 'category' => 'estrategia'],
            ['slug' => 'cambio-mental', 'name' => 'Cambio mental', 'category' => 'estrategia'],
            ['slug' => 'aprendizaje', 'name' => 'Aprendizaje', 'category' => 'estrategia'],

            // Emprendimiento
            ['slug' => 'negocios', 'name' => 'Negocios', 'category' => 'emprendimiento'],
            ['slug' => 'monetizacion', 'name' => 'Monetización', 'category' => 'emprendimiento'],
            ['slug' => 'ejecucion', 'name' => 'Ejecución', 'category' => 'emprendimiento'],
        ];
    }

    /**
     * Look up a category's name by its slug. Returns null if not canonical.
     */
    public static function categoryName(string $slug): ?string
    {
        foreach (self::categories() as $c) {
            if ($c['slug'] === $slug) {
                return $c['name'];
            }
        }

        return null;
    }

    /**
     * Look up a tag definition by slug. Returns null if not canonical.
     *
     * @return array{slug: string, name: string, category: string}|null
     */
    public static function tag(string $slug): ?array
    {
        foreach (self::tags() as $t) {
            if ($t['slug'] === $slug) {
                return $t;
            }
        }

        return null;
    }
}
