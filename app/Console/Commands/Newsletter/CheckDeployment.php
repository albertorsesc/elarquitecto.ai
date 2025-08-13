<?php

namespace App\Console\Commands\Newsletter;

use App\Services\NewsletterService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CheckDeployment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:check-deployment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new newsletters after deployment (Forge-friendly)';

    /**
     * Execute the console command.
     */
    public function handle(NewsletterService $newsletterService)
    {
        // Check if this is a fresh deployment by looking at app modification time
        $appLastModified = filemtime(app_path());
        $lastChecked = Cache::get('newsletter.last_deployment_check', 0);

        // If app was modified since last check, likely a new deployment
        if ($appLastModified > $lastChecked) {
            $this->info('🚀 Deployment detected, checking for newsletters...');

            // Process newsletters with time adjustment
            $processed = $newsletterService->scanForNewsletters();

            if ($processed > 0) {
                $this->info("✅ Processed {$processed} newsletter files");
                $this->adjustNewsletterTimes();
                $this->showUpcomingNewsletters();
            } else {
                $this->info('📭 No new newsletters found');
            }

            // Update the last checked time
            Cache::put('newsletter.last_deployment_check', time(), now()->addDays(7));
        } else {
            $this->info('ℹ️  No deployment detected, skipping newsletter check');
        }

        return 0;
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
            ->limit(3)
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
