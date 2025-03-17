<?php

namespace App\Http\Controllers;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        // Cache the sitemap for 1 day to avoid regenerating it on every request
        return cache()->remember('sitemap', 60 * 24, function () {
            $sitemap = Sitemap::create()
                ->add(Url::create(route('home'))
                    ->setPriority(1.0)
                    ->setChangeFrequency('daily'));

            // Add Blog Index if route exists
            if ($this->routeExists('blog.index')) {
                $sitemap->add(
                    Url::create(route('blog.index'))
                        ->setPriority(0.9)
                        ->setChangeFrequency('daily')
                );
            }

            // Add Prompts Index if route exists
            if ($this->routeExists('prompts.index')) {
                $sitemap->add(
                    Url::create(route('prompts.index'))
                        ->setPriority(0.9)
                        ->setChangeFrequency('daily')
                );
            }

            // Add Blog Articles if they exist and the model is available
            if (class_exists('\App\Models\Article')) {
                try {
                    $articlesClass = '\App\Models\Article';

                    // Check if there's a public scope, otherwise get all
                    if (method_exists($articlesClass, 'scopePublic')) {
                        $articles = $articlesClass::public()->get();
                    } else {
                        $articles = $articlesClass::all();
                    }

                    foreach ($articles as $article) {
                        if ($this->routeExists('blog.show')) {
                            $sitemap->add(
                                Url::create(route('blog.show', $article))
                                    ->setPriority(0.7)
                                    ->setLastModificationDate($article->updated_at)
                                    ->setChangeFrequency('weekly')
                            );
                        }
                    }
                } catch (\Exception $e) {
                    // Silently fail if there's an issue with the Articles model
                    report($e);
                }
            }

            // Return the sitemap as XML
            return response($sitemap->render(), 200, [
                'Content-Type' => 'application/xml',
            ]);
        });
    }

    /**
     * Check if a route exists to avoid errors
     */
    protected function routeExists(string $name): bool
    {
        return app('router')->has($name);
    }
}
