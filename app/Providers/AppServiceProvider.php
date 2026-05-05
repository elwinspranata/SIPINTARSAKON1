<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

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
        \Carbon\Carbon::setLocale('id');

        // Force HTTPS and Root URL if we are on ngrok
        if (str_contains(request()->url(), 'ngrok-free.dev')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        }

        \Illuminate\Support\Facades\Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });
    }
}
