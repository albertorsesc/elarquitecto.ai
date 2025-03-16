<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterRequest;
use App\Jobs\SubscriberJoinJob;
use App\Models\Subscriber;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class SubscribeController extends Controller
{
    public function post(NewsletterRequest $request) : RedirectResponse
    {
        $validated = $request->validated();

        $subscriber = Subscriber::create([
            'email' => $validated['email'],
            'hash' => md5($validated['email']),
        ]);

        SubscriberJoinJob::dispatch($subscriber);

        return redirect()->back()->with('success', '');
    }

    public function verify(string $hash) : Application|Redirector|RedirectResponse
    {
        $subscriber = Subscriber::where('hash', $hash)->firstOrFail();

        $subscriber->update([
            'hash' => null,
            'verified_at' => now()
        ]);

        return redirect('/')
            ->with('success', 'Verificación completada con éxito. ¡Bienvenido a nuestra comunidad!');
    }
}
