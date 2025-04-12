<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRootUser
{
    /**
     * Handle an incoming request, ensuring only root users can access the route.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if the user is authenticated
        if (!$request->user()) {
            return redirect('/login');
        }

        // Get the root user email from config
        $rootUserEmail = config("auth.roles.{$role}");

        // If no root user configured or user's email doesn't match
        if (!$rootUserEmail || $request->user()->email !== $rootUserEmail) {
            abort(403, 'Te perdiste, herman@. Te acompaño de regreso a la página de inicio.');
        }

        return $next($request);
    }
} 