<?php

namespace App\Console\Commands\Tools;

use App\Services\ToolMarkdownService;
use Illuminate\Console\Command;

class CreateToolTemplate extends Command
{
    protected $signature = 'tools:create
                          {title : The title of the tool}
                          {--url= : The tool website URL (optional, prefilled in the template)}';

    protected $description = 'Create a new tool template markdown file in storage/tools/';

    public function handle(ToolMarkdownService $service): int
    {
        $title = $this->argument('title');
        $url = $this->option('url');

        $this->info('📝 Creating tool template...');
        $this->line("🛠️  Title: {$title}");

        if ($url) {
            $this->line("🔗 Website: {$url}");
        }

        try {
            $filePath = $service->createToolTemplate($title, $url);

            $this->info('✅ Tool template created successfully!');
            $this->line("📁 File path: {$filePath}");

            $this->newLine();
            $this->info('📝 Next steps:');
            $this->line('1. Edit the markdown file with content and affiliate data');
            $this->line('2. (Optional) Drop a featured image in storage/tools/assets/{slug}/');
            $this->line("3. Run 'php artisan tools:scan' to sync with the database");
            $this->line('4. Set published_at in the frontmatter to publish');

            return 0;
        } catch (\Throwable $e) {
            $this->error("❌ Error creating tool template: {$e->getMessage()}");

            return 1;
        }
    }
}
