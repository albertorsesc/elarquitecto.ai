<?php

namespace App\Jobs;

use App\Models\Newsletter;
use App\Models\Subscriber;
use App\Services\NewsletterService;
use App\Services\ResendService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $maxExceptions = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Newsletter $newsletter
    ) {}

    /**
     * Execute the job.
     */
    public function handle(NewsletterService $newsletterService, ResendService $resendService): void
    {
        // Check if newsletter is still valid for sending
        if (! $this->newsletter->isReadyToSend()) {
            Log::warning("Newsletter {$this->newsletter->id} is not ready to send, skipping");

            return;
        }

        // Mark as sending
        $this->newsletter->markAsSending();

        try {
            // Get all verified subscribers who haven't unsubscribed
            $subscribers = Subscriber::whereNotNull('verified_at')
                ->whereNull('unsubscribed_at')
                ->get();
            $totalSubscribers = $subscribers->count();

            if ($totalSubscribers === 0) {
                Log::warning("No verified subscribers found for newsletter {$this->newsletter->id}");
                $this->newsletter->markAsFailed();

                return;
            }

            // Update total recipients
            $this->newsletter->update(['total_recipients' => $totalSubscribers]);

            // Render newsletter content
            $htmlContent = $newsletterService->renderNewsletterContent($this->newsletter);

            $sentCount = 0;
            $failedCount = 0;

            // Send emails in batches to avoid overwhelming the email service
            foreach ($subscribers->chunk(50) as $subscriberBatch) {
                foreach ($subscriberBatch as $subscriber) {
                    try {
                        $success = $this->sendNewsletterEmail($resendService, $subscriber, $htmlContent);

                        if ($success) {
                            $sentCount++;
                        } else {
                            $failedCount++;
                        }

                    } catch (\Exception $e) {
                        Log::error("Failed to send newsletter {$this->newsletter->id} to {$subscriber->email}: {$e->getMessage()}");
                        $failedCount++;
                    }

                    // Update progress periodically
                    if (($sentCount + $failedCount) % 10 === 0) {
                        $this->newsletter->update([
                            'sent_count' => $sentCount,
                            'failed_count' => $failedCount,
                        ]);
                    }
                }

                // Small delay between batches to be respectful to the email service
                usleep(100000); // 100ms
            }

            // Final update
            $this->newsletter->update([
                'sent_count' => $sentCount,
                'failed_count' => $failedCount,
            ]);

            // Mark as sent if we managed to send to at least some subscribers
            if ($sentCount > 0) {
                $this->newsletter->markAsSent();
                Log::info("Newsletter {$this->newsletter->id} sent successfully to {$sentCount} subscribers (failed: {$failedCount})");
            } else {
                $this->newsletter->markAsFailed();
                Log::error("Newsletter {$this->newsletter->id} failed to send to any subscribers");
            }

        } catch (\Exception $e) {
            Log::error("Critical error sending newsletter {$this->newsletter->id}: {$e->getMessage()}");
            $this->newsletter->markAsFailed();
            throw $e; // Re-throw to trigger job retry
        }
    }

    private function sendNewsletterEmail(ResendService $resendService, Subscriber $subscriber, string $htmlContent): bool
    {
        try {
            $response = $resendService->resend->emails->send([
                'from' => 'no-reply@elarquitecto.ai',
                'to' => [$subscriber->email],
                'subject' => $this->newsletter->title,
                'html' => view('emails.newsletter', [
                    'newsletter' => $this->newsletter,
                    'subscriber' => $subscriber,
                    'content' => $htmlContent,
                ])->render(),
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send newsletter email to {$subscriber->email}: {$e->getMessage()}");

            return false;
        }
    }
}
