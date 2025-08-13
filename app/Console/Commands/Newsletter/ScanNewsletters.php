<?php

namespace App\Console\Commands\Newsletter;

use App\Services\NewsletterService;
use Illuminate\Console\Command;

class ScanNewsletters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:scan {--dry-run : Show what would be processed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan for newsletter markdown files and sync them with the database';

    /**
     * Execute the console command.
     */
    public function handle(NewsletterService $newsletterService)
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info('🧪 Running in DRY RUN mode - no changes will be made');
        }

        $this->info('🔍 Scanning for newsletter files...');

        $newslettersPath = $newsletterService->getNewslettersPath();
        $this->line("📁 Scanning directory: {$newslettersPath}");

        if ($isDryRun) {
            // In dry-run mode, just show what files would be processed
            if (! \File::exists($newslettersPath)) {
                $this->warn('Newsletter directory does not exist yet.');

                return 0;
            }

            $files = \File::allFiles($newslettersPath);
            $markdownFiles = collect($files)->filter(fn ($file) => $file->getExtension() === 'md');

            $this->info("📄 Found {$markdownFiles->count()} markdown files:");

            foreach ($markdownFiles as $file) {
                $relativePath = str_replace($newslettersPath.'/', '', $file->getRealPath());
                $this->line("  - {$relativePath}");
            }

            if ($markdownFiles->isEmpty()) {
                $this->warn('No newsletter files found to process.');
            }

            return 0;
        }

        // Actual processing
        try {
            $processed = $newsletterService->scanForNewsletters();

            if ($processed > 0) {
                $this->info("✅ Successfully processed {$processed} newsletter files");
            } else {
                $this->warn('No newsletter files found to process.');
                $this->line('💡 Create your first newsletter with: php artisan newsletter:create');
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Error scanning newsletters: {$e->getMessage()}");

            return 1;
        }
    }
}
