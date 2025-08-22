<?php

namespace App\Console\Commands;

use App\Models\Subscriber;
use App\Services\ResendService;
use Illuminate\Console\Command;

class SendVerificationToUnverifiedSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribers:send-verification {--dry-run : Show what would be sent without actually sending} {--force : Skip confirmation prompt} {--emails= : Comma-separated list of specific emails to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send verification emails to unverified subscribers to register them in Resend';

    /**
     * Execute the console command.
     */
    public function handle(ResendService $resendService)
    {
        $this->info('🔍 Checking for unverified subscribers...');

        // Get unverified subscribers that need hash regeneration
        $query = Subscriber::whereNull('verified_at')
            ->whereNull('unsubscribed_at');

        // Filter by specific emails if provided
        if ($emailList = $this->option('emails')) {
            $emails = array_map('trim', explode(',', $emailList));
            $query->whereIn('email', $emails);
            $this->info('🎯 Filtering for specific emails: '.implode(', ', $emails));
        }

        $unverifiedSubscribers = $query->get();

        if ($unverifiedSubscribers->isEmpty()) {
            $this->info('✅ No unverified subscribers found.');

            return 0;
        }

        $count = $unverifiedSubscribers->count();
        $this->info("📧 Found {$count} unverified subscriber(s).");

        if ($this->option('dry-run')) {
            $this->warn('🔥 DRY RUN MODE - No emails will be sent');
            $this->table(['Email', 'Created At', 'Hash Status'],
                $unverifiedSubscribers->map(fn ($s) => [
                    $s->email,
                    $s->created_at->format('Y-m-d H:i:s'),
                    $s->hash ? 'Has hash' : 'Missing hash',
                ])->toArray()
            );

            return 0;
        }

        if (! $this->option('force') && ! $this->confirm("Send verification emails to {$count} subscribers?")) {
            $this->info('❌ Operation cancelled.');

            return 0;
        }

        $sent = 0;
        $failed = 0;
        $requestTimes = []; // Track last 2 request timestamps

        $this->withProgressBar($unverifiedSubscribers, function ($subscriber) use ($resendService, &$sent, &$failed, &$requestTimes) {
            try {
                // Regenerate hash if missing
                if (! $subscriber->hash) {
                    $subscriber->hash = md5($subscriber->email);
                    $subscriber->save();
                }

                // Rate limiter: ensure max 2 requests per second
                $this->enforceRateLimit($requestTimes);

                // Add to Resend audience (request #1)
                $resendService->addContact($subscriber);
                $requestTimes[] = microtime(true);

                // Rate limiter before next request
                $this->enforceRateLimit($requestTimes);

                // Send verification email (request #2)
                if ($resendService->sendVerificationEmail($subscriber)) {
                    $sent++;
                } else {
                    $failed++;
                }
                $requestTimes[] = microtime(true);

            } catch (\Exception $e) {
                $this->error("Failed to process {$subscriber->email}: ".$e->getMessage());
                $failed++;
            }
        });

        $this->newLine(2);
        $this->info("✅ Successfully sent: {$sent}");
        if ($failed > 0) {
            $this->error("❌ Failed to send: {$failed}");
        }

        return 0;
    }

    /**
     * Enforce rate limit of 2 requests per second
     */
    private function enforceRateLimit(array &$requestTimes): void
    {
        $now = microtime(true);

        // Remove requests older than 1 second
        $requestTimes = array_filter($requestTimes, function ($time) use ($now) {
            return ($now - $time) < 1.0;
        });

        // If we have 2 requests in the last second, wait
        if (count($requestTimes) >= 2) {
            $oldestRequest = min($requestTimes);
            $waitTime = 1.0 - ($now - $oldestRequest);

            if ($waitTime > 0) {
                usleep($waitTime * 1000000); // Convert to microseconds

                // Clean up old requests after waiting
                $now = microtime(true);
                $requestTimes = array_filter($requestTimes, function ($time) use ($now) {
                    return ($now - $time) < 1.0;
                });
            }
        }
    }
}
