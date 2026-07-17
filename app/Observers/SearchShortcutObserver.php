<?php

namespace App\Observers;

use App\Models\SearchShortcut;
use Illuminate\Support\Facades\Cache;

class SearchShortcutObserver
{
    /**
     * Handle the SearchShortcut "created" event.
     */
    public function created(SearchShortcut $searchShortcut): void
    {
        Cache::forget('search_shortcuts');
    }

    /**
     * Handle the SearchShortcut "updated" event.
     */
    public function updated(SearchShortcut $searchShortcut): void
    {
        Cache::forget('search_shortcuts');
    }

    /**
     * Handle the SearchShortcut "deleted" event.
     */
    public function deleted(SearchShortcut $searchShortcut): void
    {
        Cache::forget('search_shortcuts');
    }

    /**
     * Handle the SearchShortcut "restored" event.
     */
    public function restored(SearchShortcut $searchShortcut): void
    {
        Cache::forget('search_shortcuts');
    }

    /**
     * Handle the SearchShortcut "force deleted" event.
     */
    public function forceDeleted(SearchShortcut $searchShortcut): void
    {
        Cache::forget('search_shortcuts');
    }
}
