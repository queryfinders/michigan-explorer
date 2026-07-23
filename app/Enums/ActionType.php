<?php

namespace App\Enums;

/**
 * Enum ActionType
 *
 * Defines the types of actions or destinations that a dynamic Search Shortcut can route to.
 */
enum ActionType: string
{
    case GLOBAL_SEARCH = 'global_search';
    case HOTELS = 'hotels';
    case RESTAURANTS = 'restaurants';
    case ATTRACTIONS = 'attractions';
    case EVENTS = 'events';
    case TRAVEL_GUIDES = 'travel_guides';
    case CITY = 'city';
    case HOTEL_CATEGORY = 'hotel_category';
    case RESTAURANT_CATEGORY = 'restaurant_category';
    case ATTRACTION_CATEGORY = 'attraction_category';
    case EVENT_CATEGORY = 'event_category';
    case BLOG_CATEGORY = 'blog_category';
    case DESTINATION = 'destination';
    case CUSTOM_URL = 'custom_url';

    /**
     * Get the human-readable label for the action type.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::GLOBAL_SEARCH => 'Global Search',
            self::HOTELS => 'Hotels',
            self::RESTAURANTS => 'Restaurants',
            self::ATTRACTIONS => 'Attractions',
            self::EVENTS => 'Events',
            self::TRAVEL_GUIDES => 'Travel Guides',
            self::CITY => 'City',
            self::HOTEL_CATEGORY => 'Hotel Category',
            self::RESTAURANT_CATEGORY => 'Restaurant Category',
            self::ATTRACTION_CATEGORY => 'Attraction Category',
            self::EVENT_CATEGORY => 'Event Category',
            self::BLOG_CATEGORY => 'Blog Category',
            self::DESTINATION => 'Destination',
            self::CUSTOM_URL => 'Custom URL',
        };
    }
}
