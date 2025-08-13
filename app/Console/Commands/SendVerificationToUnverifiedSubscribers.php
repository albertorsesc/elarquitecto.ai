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
            $this->info("🎯 Filtering for specific emails: " . implode(', ', $emails));
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
        $requestCount = 0;
        $lastRequestTime = microtime(true);

        $this->withProgressBar($unverifiedSubscribers, function ($subscriber) use ($resendService, &$sent, &$failed, &$requestCount, &$lastRequestTime) {
            try {
                // Regenerate hash if missing
                if (! $subscriber->hash) {
                    $subscriber->hash = md5($subscriber->email);
                    $subscriber->save();
                }

                // Rate limiting: 2 requests per second
                $currentTime = microtime(true);
                $timeSinceLastRequest = $currentTime - $lastRequestTime;
                
                // If we've made 2 requests and less than 1 second has passed, wait
                if ($requestCount >= 2 && $timeSinceLastRequest < 1.0) {
                    $sleepTime = 1.0 - $timeSinceLastRequest;
                    usleep($sleepTime * 1000000); // Convert to microseconds
                    $requestCount = 0;
                    $lastRequestTime = microtime(true);
                }

                // Add to Resend audience (counts as 1 request)
                $resendService->addContact($subscriber);
                $requestCount++;
                
                // Check rate limit again before sending email
                $currentTime = microtime(true);
                $timeSinceLastRequest = $currentTime - $lastRequestTime;
                
                if ($requestCount >= 2 && $timeSinceLastRequest < 1.0) {
                    $sleepTime = 1.0 - $timeSinceLastRequest;
                    usleep($sleepTime * 1000000);
                    $requestCount = 0;
                    $lastRequestTime = microtime(true);
                }

                // Send verification email (counts as 1 request)
                if ($resendService->sendVerificationEmail($subscriber)) {
                    $sent++;
                } else {
                    $failed++;
                }
                $requestCount++;
                
                // Reset counter if 1 second has passed
                $currentTime = microtime(true);
                if ($currentTime - $lastRequestTime >= 1.0) {
                    $requestCount = 0;
                    $lastRequestTime = $currentTime;
                }
                
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
}
