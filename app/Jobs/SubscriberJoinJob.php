<?php

namespace App\Jobs;

use App\Mail\NewSubscriber;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SubscriberJoinJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Subscriber $subscriber)
    {
    }

    public function handle(): void
    {
        Mail::to($this->subscriber->email)
            ->send(new NewSubscriber($this->subscriber));
    }
}