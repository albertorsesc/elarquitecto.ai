<?php

namespace App\Services;

use App\Models\Subscriber;
use Resend;

class ResendService
{
    private $resend;

    private string $audienceId;

    public function __construct()
    {
        $this->resend = Resend::client(config('services.resend.api_key'));
        $this->audienceId = config('services.resend.audience_id');
    }

    public function addContact(Subscriber $subscriber): bool
    {
        try {
            $this->resend->contacts->create(
                $this->audienceId,
                [
                    'email' => $subscriber->email,
                    'unsubscribed' => false,
                ]
            );

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to add contact to Resend', [
                'email' => $subscriber->email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function updateContact(Subscriber $subscriber, array $data): bool
    {
        try {
            $this->resend->contacts->update([
                'audienceId' => $this->audienceId,
                'email' => $subscriber->email,
            ] + $data);

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to update contact in Resend', [
                'email' => $subscriber->email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function removeContact(Subscriber $subscriber): bool
    {
        try {
            $this->resend->contacts->remove([
                'audienceId' => $this->audienceId,
                'email' => $subscriber->email,
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to remove contact from Resend', [
                'email' => $subscriber->email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function getContact(Subscriber $subscriber): ?array
    {
        try {
            $response = $this->resend->contacts->get([
                'audienceId' => $this->audienceId,
                'email' => $subscriber->email,
            ]);

            return $response->toArray();
        } catch (\Exception $e) {
            \Log::error('Failed to get contact from Resend', [
                'email' => $subscriber->email,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function sendWelcomeEmail(Subscriber $subscriber): bool
    {
        try {
            $this->resend->emails->send([
                'from' => 'no-reply@elarquitecto.ai',
                'to' => [$subscriber->email],
                'subject' => '¡Bienvenido a El Arquitecto AI! 🤖',
                'html' => view('emails.welcome', ['subscriber' => $subscriber])->render(),
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email via Resend', [
                'email' => $subscriber->email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function sendVerificationEmail(Subscriber $subscriber): bool
    {
        try {
            $this->resend->emails->send([
                'from' => 'no-reply@elarquitecto.ai',
                'to' => [$subscriber->email],
                'subject' => 'Confirma tu suscripción a El Arquitecto AI',
                'html' => view('emails.verification', ['subscriber' => $subscriber])->render(),
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email via Resend', [
                'email' => $subscriber->email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function unsubscribeContact(Subscriber $subscriber): bool
    {
        try {
            // Mark contact as unsubscribed in Resend
            $this->resend->contacts->update([
                'audienceId' => $this->audienceId,
                'email' => $subscriber->email,
                'unsubscribed' => true,
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to unsubscribe contact in Resend', [
                'email' => $subscriber->email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
