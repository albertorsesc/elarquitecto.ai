<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use App\Models\Subscriber;
use App\Jobs\SubscriberJoinJob;

class SubscribersTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Queue::fake();
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
}
