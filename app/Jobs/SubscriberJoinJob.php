<?php

namespace App\Jobs;

use App\Models\Subscriber;
use App\Notifications\NewSubscriberNotification;
use App\Services\ResendService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

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
     */
    public function handle(ResendService $resendService): void
    {
        $totalSubscribers = Subscriber::count();

        Notification::route('slack', slack_channel())
            ->notify(new NewSubscriberNotification($this->subscriber, $totalSubscribers));

        // Add contact to Resend audience
        $resendService->addContact($this->subscriber);

        // Send verification email
        $resendService->sendVerificationEmail($this->subscriber);
    }
}
