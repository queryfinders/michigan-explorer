<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 *
 * Registers and bootstraps global application services and observers.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register the observer to handle cache invalidation for dynamic search shortcuts
        \App\Models\SearchShortcut::observe(\App\Observers\SearchShortcutObserver::class);
    }
}