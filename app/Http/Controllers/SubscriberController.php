<?php

namespace App\Http\Controllers;

use App\Jobs\SubscriberJoinJob;
use App\Jobs\SubscriberVerifiedJob;
use App\Models\Subscriber;
use App\Services\ResendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SubscriberController extends Controller
{
    /**
     * Store a newly created subscriber in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:subscribers,email',
        ]);

        $subscriber = Subscriber::create([
            'email' => $validated['email'],
            'hash' => md5($validated['email']),
        ]);

        // Dispatch a job to handle subscriber join process
        SubscriberJoinJob::dispatch($subscriber);

        return redirect()->back()->with('success', 'Gracias por suscribirte! Revisa tu correo para confirmar tu suscripción.');
    }

    /**
     * Verify a subscriber's email.
     */
    public function verify(string $hash)
    {
        $subscriber = Subscriber::where('hash', $hash)->firstOrFail();

        // Explicitly set the verified_at timestamp to ensure it's properly updated
        $subscriber->hash = null;
        $subscriber->verified_at = now();
        $subscriber->save();

        // Dispatch a job to handle subscriber verification
        SubscriberVerifiedJob::dispatch($subscriber);

        return redirect('/')->with('success', 'Tu suscripción ha sido confirmada. ¡Gracias!');
    }

    /**
     * Unsubscribe a subscriber using a signed URL.
     */
    public function unsubscribe(Request $request, string $email)
    {
        // Verify the signed URL to prevent abuse
        if (! URL::hasValidSignature($request)) {
            abort(403, 'Enlace de cancelación inválido o expirado.');
        }

        $subscriber = Subscriber::where('email', $email)->first();

        if (! $subscriber) {
            return redirect('/')->with('error', 'No encontramos tu suscripción.');
        }

        if ($subscriber->unsubscribed_at) {
            return redirect('/')->with('info', 'Ya habías cancelado tu suscripción anteriormente.');
        }

        // Mark as unsubscribed in our database
        $subscriber->update([
            'unsubscribed_at' => now(),
        ]);

        // Unsubscribe from Resend as well
        $resendService = app(ResendService::class);
        $success = $resendService->unsubscribeContact($subscriber);

        if ($success) {
            return redirect('/')->with('success', 'Te has desuscrito exitosamente. Lamentamos verte partir 😢');
        } else {
            return redirect('/')->with('warning', 'Te hemos desuscrito de nuestros correos localmente, pero hubo un problema actualizando el servicio de email. Por favor contacta soporte si sigues recibiendo correos.');
        }
    }

    /**
     * Generate a secure unsubscribe URL for a subscriber.
     */
    public static function generateUnsubscribeUrl(Subscriber $subscriber): string
    {
        return URL::temporarySignedRoute(
            'subscribers.unsubscribe',
            now()->addDays(30), // URL valid for 30 days
            ['email' => $subscriber->email]
        );
    }
}
