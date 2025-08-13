<?php

namespace Tests\Feature;

use App\Jobs\SubscriberJoinJob;
use App\Jobs\SubscriberVerifiedJob;
use App\Models\Subscriber;
use App\Notifications\NewSubscriberNotification;
use App\Notifications\SubscriberVerifiedNotification;
use App\Services\ResendService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SubscribersTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Queue::fake();
        Notification::fake();
    }

    public function test_user_can_subscribe_with_valid_email(): void
    {
        $email = $this->faker->safeEmail();

        $response = $this->post('/subscribe', [
            'email' => $email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('subscribers', [
            'email' => $email,
        ]);

        Queue::assertPushed(SubscriberJoinJob::class, function ($job) use ($email) {
            return $job->subscriber->email === $email;
        });
    }

    public function test_user_cannot_subscribe_with_invalid_email(): void
    {
        $response = $this->post('/subscribe', [
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('subscribers', [
            'email' => 'invalid-email',
        ]);
    }

    public function test_user_cannot_subscribe_with_duplicate_email(): void
    {
        $email = $this->faker->safeEmail();

        // Create a subscriber with the same email
        Subscriber::create([
            'email' => $email,
            'hash' => md5($email),
        ]);

        $response = $this->post('/subscribe', [
            'email' => $email,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_verify_subscription(): void
    {
        $email = $this->faker->safeEmail();
        $hash = md5($email);

        $subscriber = Subscriber::create([
            'email' => $email,
            'hash' => $hash,
        ]);

        $response = $this->get("/subscribe/{$hash}");

        $response->assertRedirect('/');
        $response->assertSessionHas('success');

        $subscriber->refresh();
        $this->assertNull($subscriber->hash);
        $this->assertNotNull($subscriber->verified_at);
    }

    public function test_verification_fails_with_invalid_hash(): void
    {
        $response = $this->get('/subscribe/invalid-hash');

        $response->assertStatus(404);
    }

    public function test_notification_sent_when_user_subscribes(): void
    {
        $email = $this->faker->safeEmail();

        $response = $this->post('/subscribe', [
            'email' => $email,
        ]);

        $response->assertRedirect();

        // First, test that the job was dispatched
        Queue::assertPushed(SubscriberJoinJob::class, function ($job) use ($email) {
            return $job->subscriber->email === $email;
        });

        // Execute the job manually with mocked ResendService
        $subscriber = Subscriber::where('email', $email)->first();
        $mockResendService = $this->createMock(ResendService::class);
        $mockResendService->method('addContact')->willReturn(true);
        $mockResendService->method('sendVerificationEmail')->willReturn(true);

        (new SubscriberJoinJob($subscriber))->handle($mockResendService);

        // Assert that a notification was sent to Slack
        Notification::assertSentOnDemand(NewSubscriberNotification::class);
    }

    public function test_notification_sent_when_user_verifies_subscription(): void
    {
        $email = $this->faker->safeEmail();
        $hash = md5($email);

        $subscriber = Subscriber::create([
            'email' => $email,
            'hash' => $hash,
        ]);

        $response = $this->get("/subscribe/{$hash}");
        $response->assertRedirect('/');

        // Execute the job manually
        Queue::assertPushed(SubscriberVerifiedJob::class, function ($job) use ($email) {
            return $job->subscriber->email === $email;
        });

        // Execute the job manually with mocked ResendService
        $subscriber->refresh();
        $mockResendService = $this->createMock(ResendService::class);
        $mockResendService->method('sendWelcomeEmail')->willReturn(true);

        (new SubscriberVerifiedJob($subscriber))->handle($mockResendService);

        // Assert that a notification was sent to Slack
        Notification::assertSentOnDemand(SubscriberVerifiedNotification::class);
    }
}
