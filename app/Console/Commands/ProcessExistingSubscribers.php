<?php

namespace App\Console\Commands;

use App\Models\Subscriber;
use App\Services\ResendService;
use Illuminate\Console\Command;

class ProcessExistingSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribers:process-existing {--dry-run : Run without sending emails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process existing subscribers: add to Resend and send verification emails to unverified subscribers';

    /**
     * Execute the console command.
     */
    public function handle(ResendService $resendService)
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info('🧪 Running in DRY RUN mode - no emails will be sent');
        }

        $this->info('🚀 Processing existing subscribers...');

        // Get all subscribers
        $allSubscribers = Subscriber::all();
        $totalCount = $allSubscribers->count();

        $this->info("📊 Found {$totalCount} total subscribers");

        // Get unverified subscribers
        $unverifiedSubscribers = Subscriber::whereNull('verified_at')->get();
        $unverifiedCount = $unverifiedSubscribers->count();

        $this->info("📧 Found {$unverifiedCount} unverified subscribers");

        // Process all subscribers (add to Resend)
        $this->newLine();
        $this->info('📋 Adding all subscribers to Resend audience...');

        $addedCount = 0;
        $skippedCount = 0;

        foreach ($allSubscribers as $subscriber) {
            if (! $isDryRun) {
                $added = $resendService->addContact($subscriber);
                if ($added) {
                    $addedCount++;
                    $this->info("✅ Added: {$subscriber->email}");
                } else {
                    $skippedCount++;
                    $this->warn("⚠️  Skipped: {$subscriber->email} (may already exist)");
                }
            } else {
                $this->info("🔍 Would add: {$subscriber->email}");
                $addedCount++;
            }
        }

        // Process unverified subscribers (send verification emails)
        if ($unverifiedCount > 0) {
            $this->newLine();
            $this->info('📧 Sending verification emails to unverified subscribers...');

            $emailsSent = 0;
            $emailsFailed = 0;

            foreach ($unverifiedSubscribers as $subscriber) {
                if (! $isDryRun) {
                    $sent = $resendService->sendVerificationEmail($subscriber);
                    if ($sent) {
                        $emailsSent++;
                        $this->info("📤 Email sent to: {$subscriber->email}");
                    } else {
                        $emailsFailed++;
                        $this->error("❌ Failed to send email to: {$subscriber->email}");
                    }
                } else {
                    $this->info("🔍 Would send verification email to: {$subscriber->email}");
                    $emailsSent++;
                }
            }

            $this->newLine();
            $this->info("📤 Verification emails sent: {$emailsSent}");
            if (! $isDryRun && $emailsFailed > 0) {
                $this->warn("❌ Failed emails: {$emailsFailed}");
            }
        }

        // Summary
        $this->newLine();
        $this->info('📈 Summary:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total subscribers', $totalCount],
                ['Added to Resend', $addedCount],
                ['Skipped (Resend)', $skippedCount],
                ['Unverified subscribers', $unverifiedCount],
                ['Verification emails sent', $emailsSent ?? 0],
                ['Failed emails', $emailsFailed ?? 0],
            ]
        );

        if ($isDryRun) {
            $this->newLine();
            $this->warn('🧪 This was a DRY RUN - no actual emails were sent or contacts added');
            $this->info('💡 Run without --dry-run to process for real');
        } else {
            $this->newLine();
            $this->info('🎉 Processing completed successfully!');
        }

        return 0;
    }
}
