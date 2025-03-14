<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ImageCache;

class ImageCacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('image-cache', function ($app) {
            return new ImageCache();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
