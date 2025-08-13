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
    protected $signature = 'subscribers:send-verification {--dry-run : Show what would be sent without actually sending}';

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
        $unverifiedSubscribers = Subscriber::whereNull('verified_at')
            ->whereNull('unsubscribed_at')
            ->get();

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

        if (! $this->confirm("Send verification emails to {$count} subscribers?")) {
            $this->info('❌ Operation cancelled.');

            return 0;
        }

        $sent = 0;
        $failed = 0;

        $this->withProgressBar($unverifiedSubscribers, function ($subscriber) use ($resendService, &$sent, &$failed) {
            try {
                // Regenerate hash if missing
                if (! $subscriber->hash) {
                    $subscriber->hash = md5($subscriber->email);
                    $subscriber->save();
                }

                // Add to Resend audience
                $resendService->addContact($subscriber);

                // Send verification email
                if ($resendService->sendVerificationEmail($subscriber)) {
                    $sent++;
                } else {
                    $failed++;
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
