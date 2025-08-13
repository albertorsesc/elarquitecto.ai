<?php

namespace App\Console\Commands\Newsletter;

use App\Services\NewsletterService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateNewsletterTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:create 
                          {title : The title of the newsletter}
                          {--date= : Send date (Y-m-d H:i format, defaults to next week)}
                          {--timezone=America/Mexico_City : Timezone for the send date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new newsletter template markdown file';

    /**
     * Execute the console command.
     */
    public function handle(NewsletterService $newsletterService)
    {
        $title = $this->argument('title');
        $timezone = $this->option('timezone');

        // Parse send date
        if ($dateOption = $this->option('date')) {
            try {
                $sendDate = Carbon::createFromFormat('Y-m-d H:i', $dateOption, $timezone);
            } catch (\Exception $e) {
                $this->error('Invalid date format. Use Y-m-d H:i (e.g., 2025-01-15 09:00)');

                return 1;
            }
        } else {
            // Default to next week, same time
            $sendDate = now($timezone)->addWeek()->setTime(9, 0);
        }

        $this->info('📝 Creating newsletter template...');
        $this->line("📰 Title: {$title}");
        $this->line("📅 Send Date: {$sendDate->format('Y-m-d H:i:s')} ({$timezone})");

        try {
            $filePath = $newsletterService->createNewsletterTemplate($title, $sendDate);

            $this->info('✅ Newsletter template created successfully!');
            $this->line("📁 File path: {$filePath}");

            $this->newLine();
            $this->info('📝 Next steps:');
            $this->line('1. Edit the markdown file with your content');
            $this->line("2. Run 'php artisan newsletter:scan' to sync with database");
            $this->line('3. The newsletter will be sent automatically at the scheduled time');

            $this->newLine();
            $this->info('💡 Tips:');
            $this->line('• Use @[youtube](VIDEO_ID) to embed YouTube videos');
            $this->line('• Use @[sponsor](sponsor_name) to insert sponsor blocks');
            $this->line('• Use @[image](path, alt, caption) for enhanced images');

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Error creating newsletter template: {$e->getMessage()}");

            return 1;
        }
    }
}
