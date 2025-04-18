<?php

namespace App\Jobs;

use App\Models\Subscriber;
use App\Notifications\NewSubscriberNotification;
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
    public function handle(): void
    {
        $totalSubscribers = Subscriber::count();

        Notification::route('slack', slack_channel())
            ->notify(new NewSubscriberNotification($this->subscriber, $totalSubscribers));

        // Additional subscriber onboarding actions would go here
        // For example, sending a welcome email, etc.
    }
}
