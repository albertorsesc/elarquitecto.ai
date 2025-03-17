<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share SEO data with the app.blade.php view
        Inertia::share('seo', function () {
            if (session()->has('seo')) {
                return session('seo');
            }

            // Default SEO data
            return [
                'title' => 'El Arquitecto A.I.',
                'description' => 'Democratizando I.A. para el beneficio de Latinoamérica',
                'keywords' => 'inteligencia artificial, IA, machine learning, español, Latinoamérica',
                'ogType' => 'website',
                'ogImage' => url('/logo.png'),
                'canonicalUrl' => url()->current(),
            ];
        });

        // Share the SEO data with the main layout view
        View::composer('app', function ($view) {
            $seo = null;

            if (session()->has('seo')) {
                $seo = session('seo');
            }

            $view->with('seo', $seo);
        });
    }
}