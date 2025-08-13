<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withSchedule(function ($schedule) {
        // Check for new newsletters every hour (after deployments)
        $schedule->command('newsletter:process', ['--adjust-time' => true])
            ->hourly()
            ->between('08:00', '23:00'); // Only during reasonable hours

        // Send newsletters 2 times a day: 7 AM and 7 PM
        $schedule->command('newsletter:send')->dailyAt('07:00'); // Morning send
        $schedule->command('newsletter:send')->dailyAt('19:00'); // Evening send
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
