<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Pagination\Paginator;
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
        
        // Force Bootstrap 5 styling for pagination elements
        Paginator::useBootstrapFive();
    }
}