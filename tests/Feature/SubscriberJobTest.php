<?php

namespace Tests\Feature;

use App\Jobs\SubscriberJoinJob;
use App\Mail\NewSubscriber;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SubscriberJobTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_subscriber_job_dispatches_correctly(): void
    {
        Queue::fake();

        $email = $this->faker->safeEmail();
        $subscriber = Subscriber::create([
            'email' => $email,
            'hash' => md5($email),
        ]);

        SubscriberJoinJob::dispatch($subscriber);

        Queue::assertPushed(SubscriberJoinJob::class, function ($job) use ($subscriber) {
            return $job->subscriber->id === $subscriber->id;
        });
    }

    public function test_subscriber_job_sends_email(): void
    {
        Mail::fake();

        $email = $this->faker->safeEmail();
        $subscriber = Subscriber::create([
            'email' => $email,
            'hash' => md5($email),
        ]);

        $job = new SubscriberJoinJob($subscriber);
        $job->handle();

        Mail::assertSent(NewSubscriber::class, function ($mail) use ($subscriber) {
            return $mail->hasTo($subscriber->email) &&
                   $mail->user->id === $subscriber->id;
        });
    }

    public function test_subscriber_job_processes_through_queue(): void
    {
        Queue::fake();
        Mail::fake();

        $email = $this->faker->safeEmail();
        $subscriber = Subscriber::create([
            'email' => $email,
            'hash' => md5($email),
        ]);

        SubscriberJoinJob::dispatch($subscriber);

        Queue::assertPushed(SubscriberJoinJob::class);
    }
}