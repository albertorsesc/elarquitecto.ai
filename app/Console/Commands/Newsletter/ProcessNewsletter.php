<?php

namespace App\Console\Commands\Newsletter;

use App\Services\NewsletterService;
use Illuminate\Console\Command;

class ProcessNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:process 
                          {--commit-message= : The git commit message to check}
                          {--adjust-time : Automatically adjust send time based on 7 AM/7 PM schedule}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process newsletters from git commits with [newsletter] prefix';

    /**
     * Execute the console command.
     */
    public function handle(NewsletterService $newsletterService)
    {
        $commitMessage = $this->option('commit-message');
        $adjustTime = $this->option('adjust-time');

        // If no commit message provided, get the latest commit
        if (! $commitMessage) {
            $commitMessage = $this->getLatestCommitMessage();
        }

        // Check if commit message starts with [newsletter]
        if (! str_starts_with(strtolower($commitMessage), '[newsletter]')) {
            $this->info('🔍 Commit message does not contain [newsletter] prefix, skipping processing');

            return 0;
        }

        $this->info('📧 Newsletter commit detected!');
        $this->line("💬 Commit: {$commitMessage}");

        // Scan for newsletter files
        $this->info('🔍 Scanning for newsletter files...');
        $processed = $newsletterService->scanForNewsletters();

        if ($processed === 0) {
            $this->warn('⚠️ No newsletter files found to process');

            return 0;
        }

        $this->info("✅ Processed {$processed} newsletter files");

        // If adjust-time option is set, check and adjust send times
        if ($adjustTime) {
            $this->adjustNewsletterTimes();
        }

        // Show upcoming newsletters
        $this->showUpcomingNewsletters();

        return 0;
    }

    private function getLatestCommitMessage(): string
    {
        try {
            $message = exec('git log -1 --pretty=%B');

            return trim($message);
        } catch (\Exception $e) {
            $this->error('Failed to get git commit message');

            return '';
        }
    }

    private function adjustNewsletterTimes(): void
    {
        $now = now();
        $morningCutoff = $now->copy()->setTime(6, 0); // 6 AM today
        $eveningCutoff = $now->copy()->setTime(18, 0); // 6 PM today

        // Get newsletters scheduled for today that haven't been sent
        $newsletters = \App\Models\Newsletter::where('status', 'scheduled')
            ->whereDate('send_date', $now->toDateString())
            ->where('send_date', '>', $now)
            ->get();

        foreach ($newsletters as $newsletter) {
            $sendTime = $newsletter->send_date;

            // If newsletter is scheduled for today, adjust based on current time
            if ($sendTime->isToday()) {
                $newSendTime = null;

                if ($now->lt($morningCutoff)) {
                    // Before 6 AM: schedule for 7 AM today
                    $newSendTime = $now->copy()->setTime(7, 0);
                } elseif ($now->lt($eveningCutoff)) {
                    // Between 6 AM and 6 PM: schedule for 7 PM today
                    $newSendTime = $now->copy()->setTime(19, 0);
                } else {
                    // After 6 PM: schedule for 7 AM tomorrow
                    $newSendTime = $now->copy()->addDay()->setTime(7, 0);
                }

                if ($newSendTime && ! $newSendTime->eq($sendTime)) {
                    $newsletter->update([
                        'send_date' => $newSendTime->utc(),
                    ]);

                    $this->info("⏰ Adjusted '{$newsletter->title}' to {$newSendTime->format('Y-m-d H:i')} (was: {$sendTime->format('Y-m-d H:i')})");
                }
            }
        }
    }

    private function showUpcomingNewsletters(): void
    {
        $upcomingNewsletters = \App\Models\Newsletter::where('status', 'scheduled')
            ->where('send_date', '>', now())
            ->orderBy('send_date')
            ->limit(5)
            ->get();

        if ($upcomingNewsletters->isNotEmpty()) {
            $this->newLine();
            $this->info('📅 Upcoming newsletters:');

            foreach ($upcomingNewsletters as $newsletter) {
                $timeStr = $newsletter->send_date->format('Y-m-d H:i');
                $relativeTime = $newsletter->send_date->diffForHumans();

                $this->line("  📰 {$newsletter->title}");
                $this->line("     📅 {$timeStr} ({$relativeTime})");
            }
        }
    }
}
