<?php

namespace App\Services;

use App\Models\Newsletter;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class NewsletterService
{
    private string $newslettersPath;

    private CommonMarkConverter $markdownConverter;

    public function __construct()
    {
        $this->newslettersPath = storage_path('newsletters');
        $this->initializeMarkdownConverter();
    }

    private function initializeMarkdownConverter(): void
    {
        $environment = new Environment;
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);

        $this->markdownConverter = new CommonMarkConverter([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ], $environment);
    }

    public function scanForNewsletters(): int
    {
        if (! File::exists($this->newslettersPath)) {
            File::makeDirectory($this->newslettersPath, 0755, true);

            return 0;
        }

        $processed = 0;
        $markdownFiles = File::allFiles($this->newslettersPath);

        foreach ($markdownFiles as $file) {
            if ($file->getExtension() === 'md') {
                $this->processNewsletterFile($file->getRealPath());
                $processed++;
            }
        }

        return $processed;
    }

    public function processNewsletterFile(string $filePath): ?Newsletter
    {
        if (! File::exists($filePath)) {
            return null;
        }

        $content = File::get($filePath);
        $fileHash = md5($content);
        $relativePath = str_replace(storage_path().'/', '', $filePath);

        // Parse frontmatter and content
        $document = YamlFrontMatter::parse($content);
        $frontmatter = $document->matter();
        $markdownContent = $document->body();

        // Validate required fields
        if (! isset($frontmatter['title']) || ! isset($frontmatter['send_date'])) {
            \Log::warning("Newsletter file missing required fields: {$filePath}");

            return null;
        }

        // Check if newsletter already exists
        $newsletter = Newsletter::where('file_path', $relativePath)->first();

        if ($newsletter && $newsletter->hash === $fileHash) {
            // No changes, return existing
            return $newsletter;
        }

        // Parse send date with timezone
        $timezone = $frontmatter['timezone'] ?? 'America/Mexico_City';
        try {
            $sendDate = Carbon::parse($frontmatter['send_date'], $timezone);
        } catch (\Exception $e) {
            \Log::error("Invalid send_date format in newsletter: {$filePath}");

            return null;
        }

        $data = [
            'title' => $frontmatter['title'],
            'slug' => $frontmatter['slug'] ?? null,
            'description' => $frontmatter['description'] ?? null,
            'file_path' => $relativePath,
            'send_date' => $sendDate->utc(),
            'timezone' => $timezone,
            'status' => $frontmatter['status'] ?? 'scheduled',
            'author' => $frontmatter['author'] ?? null,
            'tags' => $frontmatter['tags'] ?? [],
            'sponsors' => $frontmatter['sponsors'] ?? [],
            'metadata' => array_diff_key($frontmatter, array_flip([
                'title', 'slug', 'description', 'send_date', 'timezone',
                'status', 'author', 'tags', 'sponsors',
            ])),
            'hash' => $fileHash,
        ];

        if ($newsletter) {
            // Update existing newsletter (but don't change status if already sent)
            if (! $newsletter->isSent()) {
                $newsletter->update($data);
            }
        } else {
            // Create new newsletter
            $newsletter = Newsletter::create($data);
        }

        return $newsletter;
    }

    public function renderNewsletterContent(Newsletter $newsletter): string
    {
        $filePath = storage_path($newsletter->file_path);

        if (! File::exists($filePath)) {
            throw new \Exception("Newsletter file not found: {$filePath}");
        }

        $content = File::get($filePath);
        $document = YamlFrontMatter::parse($content);
        $markdownContent = $document->body();

        // Process custom markdown extensions
        $processedContent = $this->processCustomExtensions($markdownContent, $newsletter);

        // Convert to HTML
        $html = $this->markdownConverter->convert($processedContent);

        return $html->getContent();
    }

    private function processCustomExtensions(string $content, Newsletter $newsletter): string
    {
        // Process auto table of contents: @[toc]
        $content = preg_replace_callback(
            '/@\[toc\]/',
            function ($matches) use ($content) {
                return $this->generateTableOfContents($content);
            },
            $content
        );

        // Process YouTube embeds: @[youtube](video_id)
        $content = preg_replace_callback(
            '/@\[youtube\]\(([^)]+)\)/',
            function ($matches) {
                $videoId = $matches[1];

                return "<div style='text-align: center; margin: 20px 0;'>
                    <a href='https://www.youtube.com/watch?v={$videoId}' 
                       style='display: inline-block; background: linear-gradient(45deg, #ff0000, #ff4444); 
                              color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; 
                              font-weight: bold; box-shadow: 0 4px 12px rgba(255, 0, 0, 0.3);'>
                        🎥 Ver Video en YouTube
                    </a>
                </div>";
            },
            $content
        );

        // Process sponsor blocks: @[sponsor](sponsor_name)
        $content = preg_replace_callback(
            '/@\[sponsor\]\(([^)]+)\)/',
            function ($matches) use ($newsletter) {
                $sponsorName = $matches[1];
                $sponsor = collect($newsletter->sponsors)->firstWhere('name', $sponsorName);

                if (! $sponsor) {
                    return "<!-- Sponsor '{$sponsorName}' not found -->";
                }

                return "<div style='border: 2px solid #00ffff; border-radius: 12px; padding: 20px; 
                                   margin: 25px 0; background: rgba(0, 255, 255, 0.1); text-align: center;'>
                    <h3 style='color: #00ffff; margin-top: 0;'>💼 Patrocinador</h3>
                    ".(isset($sponsor['logo']) ?
                        "<img src='{$sponsor['logo']}' alt='{$sponsor['name']}' style='max-height: 60px; margin-bottom: 10px;'>" :
                        '')."
                    <h4 style='margin: 10px 0; color: #ffffff;'>{$sponsor['name']}</h4>
                    ".(isset($sponsor['description']) ?
                        "<p style='margin: 10px 0; color: #b0b0b0;'>{$sponsor['description']}</p>" :
                        '')."
                    <a href='{$sponsor['url']}' target='_blank' 
                       style='display: inline-block; background: linear-gradient(45deg, #00ffff, #ff00ff); 
                              color: #000; text-decoration: none; padding: 10px 20px; border-radius: 6px; 
                              font-weight: bold; margin-top: 10px;'>
                        Visitar Sitio Web
                    </a>
                </div>";
            },
            $content
        );

        // Process enhanced images: @[image](path, alt, caption)
        $content = preg_replace_callback(
            '/@\[image\]\(([^,]+),\s*([^,]+),\s*([^)]+)\)/',
            function ($matches) {
                $path = trim($matches[1]);
                $alt = trim($matches[2]);
                $caption = trim($matches[3]);

                return "<div style='text-align: center; margin: 25px 0;'>
                    <img src='{$path}' alt='{$alt}' 
                         style='max-width: 100%; height: auto; border-radius: 8px; 
                                box-shadow: 0 4px 15px rgba(0, 255, 255, 0.2);'>
                    <p style='margin: 10px 0 0 0; font-style: italic; color: #888; font-size: 14px;'>{$caption}</p>
                </div>";
            },
            $content
        );

        return $content;
    }

    private function generateTableOfContents(string $content): string
    {
        // Extract headings (h2 and h3) from markdown content
        preg_match_all('/^##\s+(.+)$/m', $content, $headings);

        if (empty($headings[1])) {
            return ''; // No headings found
        }

        $toc = "<div style='background: rgba(0, 255, 255, 0.1); border: 1px solid #00ffff; border-radius: 8px; padding: 20px; margin: 25px 0;'>\n";
        $toc .= "<h3 style='color: #00ffff; margin-top: 0; text-align: center;'>📋 En esta edición</h3>\n";
        $toc .= "<div style='text-align: center; margin-bottom: 15px;'>\n";
        $toc .= str_repeat('-', 41)."\n";
        $toc .= "</div>\n";
        $toc .= "<ul style='list-style: none; padding: 0; margin: 0; color: #e0e0e0;'>\n";

        foreach ($headings[1] as $heading) {
            // Clean heading text and add appropriate emoji
            $cleanHeading = trim($heading);

            // Add emoji based on content
            $emoji = $this->getEmojiForHeading($cleanHeading);

            $toc .= "<li style='margin: 8px 0; padding: 5px 0; border-bottom: 1px solid rgba(0, 255, 255, 0.2);'>\n";
            $toc .= "<strong>{$emoji} {$cleanHeading}</strong>\n";
            $toc .= "</li>\n";
        }

        $toc .= "</ul>\n";
        $toc .= "<div style='text-align: center; margin-top: 15px; color: #b0b0b0; font-size: 12px;'>\n";
        $toc .= '💡 '.count($headings[1])." secciones principales\n";
        $toc .= "</div>\n";
        $toc .= "</div>\n";

        return $toc;
    }

    private function getEmojiForHeading(string $heading): string
    {
        $heading = strtolower($heading);

        if (str_contains($heading, 'noticia') || str_contains($heading, 'news')) {
            return '📰';
        }
        if (str_contains($heading, 'herramienta') || str_contains($heading, 'tool')) {
            return '🛠️';
        }
        if (str_contains($heading, 'tutorial') || str_contains($heading, 'guía')) {
            return '📚';
        }
        if (str_contains($heading, 'recurso') || str_contains($heading, 'finding')) {
            return '🔗';
        }
        if (str_contains($heading, 'video')) {
            return '🎥';
        }
        if (str_contains($heading, 'tip') || str_contains($heading, 'consejo')) {
            return '💡';
        }
        if (str_contains($heading, 'último') || str_contains($heading, 'latest') || str_contains($heading, 'tech')) {
            return '🚀';
        }
        if (str_contains($heading, 'sponsor') || str_contains($heading, 'patrocin')) {
            return '💼';
        }

        return '📌'; // Default emoji
    }

    public function createNewsletterTemplate(string $title, Carbon $sendDate): string
    {
        $year = $sendDate->format('Y');
        $month = $sendDate->format('m');
        $day = $sendDate->format('d');
        $slug = \Str::slug($title);

        $directory = $this->newslettersPath."/{$year}/{$month}";
        File::makeDirectory($directory, 0755, true);

        $filename = "{$day}-{$slug}.md";
        $filePath = "{$directory}/{$filename}";

        $template = "---
title: \"{$title}\"
slug: \"{$slug}\"
send_date: \"{$sendDate->format('Y-m-d H:i:s')}\"
timezone: \"America/Mexico_City\"
status: \"draft\"
author: \"El Arquitecto AI\"
description: \"Descripción del newsletter\"
tags: [\"AI\", \"Technology\"]
sponsors: []
---

# {$title} 🤖

¡Hola, Comunidad Inteligente! 👋

Bienvenidos a una nueva edición del Buzón 'El Arquitecto AI Hub'. Aquí tienes las últimas novedades en IA, tecnología y desarrollo profesional.

@[toc]

## 📰 Noticias de la Semana

### Título de la noticia principal

Contenido de la noticia aquí...

Agrega más noticias según necesites.

## 🛠️ Herramientas IA para revisar

- **[Nombre de la herramienta](https://example.com)** - Descripción breve de qué hace
- **[Otra herramienta](https://example.com)** - Para qué la usarías
- **[Herramienta destacada](https://example.com)** - Por qué es especial

## 📚 Tutorial: [Tema del Tutorial]

Paso a paso de cómo hacer algo útil con IA...

## 🔗 Recursos y Findings

- [Artículo interesante](https://example.com) - Breve descripción
- [Recurso útil](https://example.com) - Por qué vale la pena
- [Investigación](https://example.com) - Hallazgos importantes

## 🎥 Video de la Semana

@[youtube](VIDEO_ID)

## 💡 Tip de la Semana

Un consejo práctico para nuestros lectores...

## 🚀 Lo Último en IA y Tech

- Breve actualización 1
- Desarrollo importante 2  
- Tendencia emergente 3

---

¿Te gustó este newsletter? [Compártelo con tus colegas](mailto:?subject=Te%20recomiendo%20este%20newsletter&body=Echa%20un%20vistazo%20a%20este%20interesante%20newsletter%20de%20El%20Arquitecto%20AI)

¡Hasta la próxima! 🚀
";

        File::put($filePath, $template);

        return $filePath;
    }

    public function getNewslettersPath(): string
    {
        return $this->newslettersPath;
    }
}
