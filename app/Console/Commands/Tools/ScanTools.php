<?php

namespace App\Console\Commands\Tools;

use App\Services\ToolMarkdownService;
use Illuminate\Console\Command;

class ScanTools extends Command
{
    protected $signature = 'tools:scan {--dry-run : Show what would be processed without making changes}';

    protected $description = 'Scan storage/tools/*.md and sync tool markdown files with the database';

    public function handle(ToolMarkdownService $service): int
    {
        $isDryRun = (bool) $this->option('dry-run');

        if ($isDryRun) {
            $this->info('🧪 Running in DRY RUN mode - no changes will be made');
        }

        $this->info('🔍 Scanning for tool files...');

        $toolsPath = $service->getToolsPath();
        $this->line("📁 Scanning directory: {$toolsPath}");

        if ($isDryRun) {
            if (! \File::exists($toolsPath)) {
                $this->warn('Tools directory does not exist yet.');

                return 0;
            }

            $files = collect(\File::files($toolsPath))
                ->filter(fn ($file) => $file->getExtension() === 'md');

            $this->info("📄 Found {$files->count()} markdown files:");

            foreach ($files as $file) {
                $this->line('  - '.$file->getFilename());
            }

            if ($files->isEmpty()) {
                $this->warn('No tool files found to process.');
            }

            return 0;
        }

        try {
            $counters = $service->scanForTools();

            $this->newLine();
            $this->info("✅ Processed: {$counters['processed']}");
            $this->line("⏭️  Skipped (unchanged): {$counters['skipped']}");

            if ($counters['failed'] > 0) {
                $this->error("❌ Failed: {$counters['failed']} — check storage/logs/laravel.log for details");

                return 1;
            }

            if ($counters['processed'] === 0 && $counters['skipped'] === 0) {
                $this->warn('No tool files found to process.');
                $this->line('💡 Create your first tool with: php artisan tools:create "Tool Name"');
            }

            return 0;
        } catch (\Throwable $e) {
            $this->error("❌ Error scanning tools: {$e->getMessage()}");

            return 1;
        }
    }
}
