<?php

namespace App\Http\Controllers;

use App\Jobs\SubscriberJoinJob;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return redirect('/')->with('success', 'Tu suscripción ha sido confirmada. ¡Gracias!');
    }
} 