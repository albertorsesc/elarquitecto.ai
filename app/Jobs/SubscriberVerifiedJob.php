<?php

namespace App\Jobs;

use App\Models\Subscriber;
use App\Notifications\SubscriberVerifiedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SubscriberVerifiedJob implements ShouldQueue
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
        $totalVerifiedSubscribers = Subscriber::whereNotNull('verified_at')->count();

        Notification::route('slack', slack_channel())
            ->notify(new SubscriberVerifiedNotification($this->subscriber, $totalVerifiedSubscribers));
    }
}
