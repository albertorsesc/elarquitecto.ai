<?php

namespace App\Jobs;

use App\Models\Subscriber;
use App\Services\ResendService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriberJoinJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Subscriber $subscriber
    ) {}

    /**
     * Execute the job.
     *
     * No Slack notification is sent here — we only notify Slack after the
     * subscriber verifies their email so the channel stays free of noise
     * from bots, typos, and abandoned signups.
     */
    public function handle(ResendService $resendService): void
    {
        // Add contact to Resend audience
        $resendService->addContact($this->subscriber);

        // Send verification email
        $resendService->sendVerificationEmail($this->subscriber);
    }
}
