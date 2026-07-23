<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SearchShortcut
 *
 * Represents a dynamic search shortcut button displayed on the frontend.
 * Provides target URL resolution based on the configured ActionType.
 */
class SearchShortcut extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'icon',
        'icon_color',
        'action_type',
        'action_value',
        'open_in',
        'sort_order',
        'status',
        'click_count',
        'last_clicked_at',
    ];

    protected $casts = [
        'action_type' => \App\Enums\ActionType::class,
        'status' => 'boolean',
        'last_clicked_at' => 'datetime',
    ];

    /**
     * Get the dynamically generated target URL based on the action type and value.
     *
     * @return string
     */
    public function getTargetUrlAttribute()
    {
        $type = $this->action_type;
        $val = $this->action_value;
        
        return match ($type) {
            \App\Enums\ActionType::GLOBAL_SEARCH => route('web.search', ['q' => $val]),
            \App\Enums\ActionType::HOTELS => route('web.search', ['tab' => 'hotels']),
            \App\Enums\ActionType::RESTAURANTS => route('web.search', ['tab' => 'restaurants']),
            \App\Enums\ActionType::ATTRACTIONS => route('web.search', ['tab' => 'attractions']),
            \App\Enums\ActionType::EVENTS => route('web.search', ['tab' => 'events']),
            \App\Enums\ActionType::TRAVEL_GUIDES => route('web.search', ['tab' => 'travel_guides']),
            \App\Enums\ActionType::CITY => route('web.search', ['q' => $val]),
            \App\Enums\ActionType::HOTEL_CATEGORY => route('web.search', ['tab' => 'hotels', 'category' => $val]),
            \App\Enums\ActionType::RESTAURANT_CATEGORY => route('web.search', ['tab' => 'restaurants', 'category' => $val]),
            \App\Enums\ActionType::ATTRACTION_CATEGORY => route('web.search', ['tab' => 'attractions', 'category' => $val]),
            \App\Enums\ActionType::EVENT_CATEGORY => route('web.search', ['tab' => 'events', 'category' => $val]),
            \App\Enums\ActionType::BLOG_CATEGORY => route('web.search', ['tab' => 'travel_guides', 'category' => $val]),
            \App\Enums\ActionType::DESTINATION => route('web.search', ['q' => $val]), // Could route to a destination page later
            \App\Enums\ActionType::CUSTOM_URL => url($val),
            default => route('web.home'),
        };
    }
}
