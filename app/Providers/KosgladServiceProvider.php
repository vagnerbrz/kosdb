<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\KosgladApiService;

class KosgladServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind para permitir injeção
        $this->app->singleton(KosgladApiService::class, function ($app) {
            return new KosgladApiService();
        });
    }

    public function boot(): void
    {
        //
    }
}
