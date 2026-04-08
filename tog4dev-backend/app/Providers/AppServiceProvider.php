<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

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
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }

        $replitDomain = env('REPLIT_DEV_DOMAIN');
        if ($replitDomain) {
            $port = request()->server('SERVER_PORT', 3000);
            URL::forceRootUrl("https://{$replitDomain}:{$port}");
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);
    }
}
