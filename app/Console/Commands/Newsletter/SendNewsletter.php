<?php

namespace App\Console\Commands\Newsletter;

use App\Jobs\SendNewsletterJob;
use App\Models\Newsletter;
use Illuminate\Console\Command;

class SendNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send 
                          {--scan : Scan for newsletter files first}
                          {--dry-run : Show what would be sent without actually sending}
                          {newsletter? : Specific newsletter ID to send}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled newsletters or a specific newsletter';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('scan')) {
            $this->info('🔍 Scanning for newsletter files first...');
            $this->call('newsletter:scan');
            $this->newLine();
        }

        $isDryRun = $this->option('dry-run');
        $specificNewsletter = $this->argument('newsletter');

        if ($specificNewsletter) {
            return $this->sendSpecificNewsletter($specificNewsletter, $isDryRun);
        }

        return $this->sendScheduledNewsletters($isDryRun);
    }

    private function sendSpecificNewsletter(int $newsletterId, bool $isDryRun): int
    {
        $newsletter = Newsletter::find($newsletterId);

        if (! $newsletter) {
            $this->error("❌ Newsletter with ID {$newsletterId} not found");

            return 1;
        }

        $this->info("📧 Processing specific newsletter: {$newsletter->title}");

        if ($newsletter->isSent()) {
            $this->warn('⚠️ Newsletter has already been sent');

            return 0;
        }

        if ($isDryRun) {
            $this->info('🧪 DRY RUN - Would send newsletter:');
            $this->displayNewsletterInfo($newsletter);

            return 0;
        }

        $this->info('📤 Queuing newsletter for sending...');
        SendNewsletterJob::dispatch($newsletter);

        $this->info('✅ Newsletter queued successfully!');
        $this->line('💡 Monitor the job queue to track sending progress');

        return 0;
    }

    private function sendScheduledNewsletters(bool $isDryRun): int
    {
        $this->info('🔍 Looking for newsletters ready to send...');

        $readyNewsletters = Newsletter::where('status', 'scheduled')
            ->where('send_date', '<=', now())
            ->orderBy('send_date')
            ->get();

        if ($readyNewsletters->isEmpty()) {
            $this->info('📭 No newsletters are scheduled to send at this time');

            $upcomingNewsletters = Newsletter::where('status', 'scheduled')
                ->where('send_date', '>', now())
                ->orderBy('send_date')
                ->limit(3)
                ->get();

            if ($upcomingNewsletters->isNotEmpty()) {
                $this->line("\n📅 Upcoming newsletters:");
                foreach ($upcomingNewsletters as $newsletter) {
                    $this->line("  • {$newsletter->title} - {$newsletter->send_date->format('Y-m-d H:i')}");
                }
            }

            return 0;
        }

        $this->info("📬 Found {$readyNewsletters->count()} newsletters ready to send:");

        foreach ($readyNewsletters as $newsletter) {
            $this->displayNewsletterInfo($newsletter);
        }

        if ($isDryRun) {
            $this->newLine();
            $this->warn('🧪 This was a DRY RUN - no newsletters were actually sent');

            return 0;
        }

        // Queue all ready newsletters
        $queuedCount = 0;
        foreach ($readyNewsletters as $newsletter) {
            try {
                SendNewsletterJob::dispatch($newsletter);
                $queuedCount++;
                $this->info("✅ Queued: {$newsletter->title}");
            } catch (\Exception $e) {
                $this->error("❌ Failed to queue {$newsletter->title}: {$e->getMessage()}");
            }
        }

        if ($queuedCount > 0) {
            $this->newLine();
            $this->info("🚀 {$queuedCount} newsletters queued successfully!");
            $this->line('💡 Monitor the job queue to track sending progress');
        }

        return 0;
    }

    private function displayNewsletterInfo(Newsletter $newsletter): void
    {
        $this->line("  📰 {$newsletter->title}");
        $this->line("     📅 Send Date: {$newsletter->send_date->format('Y-m-d H:i:s')}");
        $this->line("     📁 File: {$newsletter->file_path}");
        $this->line("     📊 Status: {$newsletter->status}");

        if ($newsletter->author) {
            $this->line("     👨‍💻 Author: {$newsletter->author}");
        }

        if (! empty($newsletter->tags)) {
            $this->line('     🏷️  Tags: '.implode(', ', $newsletter->tags));
        }

        $this->newLine();
    }
}
