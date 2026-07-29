<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;


/**
 * Class HomeController
 *
 * Handles the logic for rendering the main homepage,
 * including dynamic content fetching and caching.
 */
class HomeController extends Controller
{
    /**
     * Display the application homepage.
     * Fetches top locations, events, guides, and dynamic search shortcuts.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $hotels = \Illuminate\Support\Facades\Cache::remember('home_hotels', 3600, function() {
            return \App\Models\Hotel::where('status', 1)->take(3)->get();
        });
        
        $restaurants = \Illuminate\Support\Facades\Cache::remember('home_restaurants', 3600, function() {
            return \App\Models\Restaurant::where('status', 1)->where('is_featured', 1)->take(3)->get();
        });
        
        $attractions = \Illuminate\Support\Facades\Cache::remember('home_attractions', 3600, function() {
            return \App\Models\Attraction::where('status', 1)->take(3)->get();
        });
        
        $events = \Illuminate\Support\Facades\Cache::remember('home_events', 3600, function() {
            return \App\Models\Event::where('status', 1)
                ->where('is_featured', 1)
                ->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', \Carbon\Carbon::now());
                })
                ->orderBy('start_date', 'asc')
                ->take(3)
                ->get();
        });
        
        $blogs = \Illuminate\Support\Facades\Cache::remember('home_blogs', 3600, function() {
            return \App\Models\Blog::where('status', 'published')->orderBy('published_at', 'desc')->take(3)->get();
        });

        $upcomingEventsWidget = \Illuminate\Support\Facades\Cache::remember('home_upcoming_events_widget', 1800, function() {
            return \App\Models\Event::with('category')
                ->where('status', 1)
                ->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', \Carbon\Carbon::now());
                })
                ->orderBy('start_date', 'asc')
                ->take(4)
                ->get();
        });

        $page = \App\Models\Page::with('seo')->where('slug', 'home')->first();

        $searchShortcuts = \Illuminate\Support\Facades\Cache::rememberForever('search_shortcuts', function () {
            return \App\Models\SearchShortcut::where('status', 1)->orderBy('sort_order', 'asc')->get();
        });

        // Fetch the active homepage banner promotion (not cached due to scheduling)
        $homepagePromotion = \App\Models\AffiliatePromotion::active()
            ->where('placement', 'homepage_banner')
            ->orderBy('priority')
            ->first();

        return view('web.pages.index', compact('hotels', 'restaurants', 'attractions', 'events', 'blogs', 'page', 'searchShortcuts', 'upcomingEventsWidget', 'homepagePromotion'));
    }
}
