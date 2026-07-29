<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\Attraction;
use App\Models\Event;
use App\Models\Blog;
use App\Models\SearchKeyword;

/**
 * Class SearchController
 *
 * Handles global search functionality, autocomplete suggestions, and search shortcut tracking.
 * Provides filtered search results across Hotels, Restaurants, Attractions, Events, and Blogs.
 */
class SearchController extends Controller
{
    /**
     * Track a click on a search shortcut and redirect to its target URL.
     *
     * @param int $id The ID of the search shortcut.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function trackShortcut($id)
    {
        $shortcut = \App\Models\SearchShortcut::findOrFail($id);

        // Combine increment + timestamp update into a single query
        $shortcut->increment('click_count', 1, ['last_clicked_at' => now()]);

        return redirect()->to($shortcut->target_url);
    }

    /**
     * Provide autocomplete suggestions based on the user's search query.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autocomplete(Request $request)
    {
        $q = trim($request->input('keyword', $request->input('q', '')));

        // Reject empty or too-short queries immediately
        if (empty($q) || strlen($q) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Reusable mapper: reads pre-selected lightweight columns only
        $mapItem = function ($item, $routeName, $isBlog = false) {
            $img = $item->featured_image ?? $item->image ?? null;
            return [
                'id'       => $item->id,
                'title'    => $isBlog ? $item->title : $item->name,
                'url'      => route($routeName, $item->slug),
                'image'    => $img
                     ? (str_starts_with($img, 'http') ? $img : asset($img))
                     : asset('website/assets/images/placeholder.jpg'),
                'location' => $isBlog
                     ? ($item->category->name ?? 'Article')
                     : ($item->city ?? 'Michigan'),
            ];
        };

        // Hotels — select only the lightweight columns needed for the autocomplete card
        $hotelsQuery = Hotel::select(['id', 'name', 'slug', 'featured_image', 'city'])
            ->where('status', 1)
            ->where('name', 'like', "%{$q}%");
        $hotelsCount = (clone $hotelsQuery)->count();
        if ($hotelsCount > 0) {
            $results['Hotels'] = [
                'items'        => $hotelsQuery->take(3)->get()->map(fn ($item) => $mapItem($item, 'web.hotels.show')),
                'has_more'     => $hotelsCount > 3,
                'view_all_url' => route('web.search', ['tab' => 'hotels', 'keyword' => $q]),
                'icon'         => 'fas fa-hotel',
            ];
        }

        // Restaurants
        $restQuery = Restaurant::select(['id', 'name', 'slug', 'featured_image', 'city'])
            ->where('status', 1)
            ->where('name', 'like', "%{$q}%");
        $restCount = (clone $restQuery)->count();
        if ($restCount > 0) {
            $results['Restaurants'] = [
                'items'        => $restQuery->take(3)->get()->map(fn ($item) => $mapItem($item, 'web.restaurants.show')),
                'has_more'     => $restCount > 3,
                'view_all_url' => route('web.search', ['tab' => 'restaurants', 'keyword' => $q]),
                'icon'         => 'fas fa-utensils',
            ];
        }

        // Attractions
        $attrQuery = Attraction::select(['id', 'name', 'slug', 'featured_image', 'city'])
            ->where('status', 1)
            ->where('name', 'like', "%{$q}%");
        $attrCount = (clone $attrQuery)->count();
        if ($attrCount > 0) {
            $results['Attractions'] = [
                'items'        => $attrQuery->take(3)->get()->map(fn ($item) => $mapItem($item, 'web.attractions.show')),
                'has_more'     => $attrCount > 3,
                'view_all_url' => route('web.search', ['tab' => 'attractions', 'keyword' => $q]),
                'icon'         => 'fas fa-map-marked-alt',
            ];
        }

        // Events
        $eventQuery = Event::select(['id', 'name', 'slug', 'featured_image', 'city'])
            ->where('status', 1)
            ->where('name', 'like', "%{$q}%");
        $eventCount = (clone $eventQuery)->count();
        if ($eventCount > 0) {
            $results['Events'] = [
                'items'        => $eventQuery->take(3)->get()->map(fn ($item) => $mapItem($item, 'web.events.show')),
                'has_more'     => $eventCount > 3,
                'view_all_url' => route('web.search', ['tab' => 'events', 'keyword' => $q]),
                'icon'         => 'fas fa-calendar-alt',
            ];
        }

        // Travel Guides (Blogs) — eager-load only the category name needed for the location label
        $blogQuery = Blog::with(['category:id,name'])
            ->select(['id', 'title', 'slug', 'featured_image', 'blog_category_id'])
            ->where('status', 'published')
            ->where('title', 'like', "%{$q}%");
        $blogCount = (clone $blogQuery)->count();
        if ($blogCount > 0) {
            $results['Travel Guides'] = [
                'items'        => $blogQuery->take(3)->get()->map(fn ($item) => $mapItem($item, 'web.blogs.show', true)),
                'has_more'     => $blogCount > 3,
                'view_all_url' => route('web.search', ['tab' => 'travel_guides', 'keyword' => $q]),
                'icon'         => 'fas fa-book-open',
            ];
        }

        return response()->json($results);
    }

    /**
     * Display the global search results page.
     * Handles keyword tracking, counts per category, and filtering logic.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // Handle explicit clear
        if ($request->has('clear')) {
            session()->forget('last_search_params');
            return redirect()->route('web.search');
        }

        // If there are query parameters in the URL, save them to session and redirect to /search
        if (count($request->query()) > 0) {
            session(['last_search_params' => $request->query()]);
            return redirect()->route('web.search');
        }

        $searchParams = session('last_search_params', []);
        $request->merge($searchParams);

        $q   = $request->input('keyword') ?? $request->input('q');
        $tab = $request->input('tab', 'all');

        // Normalize keyword into 'q' so all sub-queries catch it properly
        if ($q) {
            $request->merge(['q' => $q]);
        }

        // Log the search keyword for analytics; increment if it already exists
        if ($q) {
            $keyword = SearchKeyword::firstOrCreate(['keyword' => strtolower(trim($q))]);
            if (!$keyword->wasRecentlyCreated) {
                $keyword->increment('hits');
            }
        }

        // DRY closure for keyword-based WHERE conditions on name/description
        $keywordScope = fn ($query) => $query->where(function ($q2) use ($q) {
            $q2->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%");
        });

        // Get total counts for tab badges
        $counts = [
            'hotels'      => Hotel::where('status', 1)->when($q, $keywordScope)->count(),
            'restaurants' => Restaurant::where('status', 1)->when($q, $keywordScope)->count(),
            'attractions' => Attraction::where('status', 1)->when($q, $keywordScope)->count(),
            'events'      => Event::where('status', 1)->when($q, $keywordScope)->count(),
            'blogs'       => Blog::where('status', 'published')->when($q, function ($query) use ($q) {
                $query->where(function ($q2) use ($q) {
                    $q2->where('title', 'like', "%{$q}%")->orWhere('content', 'like', "%{$q}%");
                });
            })->count(),
        ];
        $counts['all'] = array_sum($counts);

        $results = collect();
        $filters = [];
        
        if ($tab == 'all') {
            $results->put('hotels', $this->searchHotels($request)->take(6)->get());
            $results->put('restaurants', $this->searchRestaurants($request)->take(6)->get());
            $results->put('attractions', $this->searchAttractions($request)->take(6)->get());
            $results->put('events', $this->searchEvents($request)->take(6)->get());
            $results->put('blogs', $this->searchBlogs($request)->take(6)->get());
        } elseif ($tab == 'hotels') {
            $results = $this->searchHotels($request)->paginate(12)->withQueryString();
            $filters = $this->getHotelFilters();
        } elseif ($tab == 'restaurants') {
            $results = $this->searchRestaurants($request)->paginate(12)->withQueryString();
            $filters = $this->getRestaurantFilters();
        } elseif ($tab == 'attractions') {
            $results = $this->searchAttractions($request)->paginate(12)->withQueryString();
            $filters = $this->getAttractionFilters();
        } elseif ($tab == 'events') {
            $results = $this->searchEvents($request)->paginate(12)->withQueryString();
            $filters = $this->getEventFilters();
        } elseif ($tab == 'travel_guides') {
            $results = $this->searchBlogs($request)->paginate(12)->withQueryString();
            $filters = $this->getBlogFilters();
        }

        // Show cached recommendations when a specific tab returns zero results
        $recommendations = collect();
        if ($tab !== 'all' && $results->isEmpty()) {
            $recommendations = Cache::remember("search_recommendations_{$tab}_v2", 3600, function () use ($tab) {
                return $this->getRecommendations($tab);
            });
        }

        return view('web.search', compact('results', 'q', 'tab', 'counts', 'filters', 'recommendations'));
    }

    /**
     * Build the search query for Hotels based on request filters.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function searchHotels(Request $request)
    {
        $q = $request->input('q');
        $query = Hotel::with(['category', 'amenities', 'bookingFeatures'])->where('status', 1);

        if ($q) {
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%");
            });
        }
        
        if ($request->filled('city')) {
            $query->whereIn('city', (array)$request->input('city'));
        }
        if ($request->filled('category')) {
            $query->whereIn('hotel_category_id', (array)$request->input('category'));
        }
        if ($request->filled('min_price')) {
            $query->where('starting_price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('starting_price', '<=', $request->input('max_price'));
        }
        if ($request->filled('amenities')) {
            $query->whereHas('amenities', function($q) use ($request) {
                $q->whereIn('amenities.id', (array)$request->input('amenities'));
            });
        }
        if ($request->filled('booking_features')) {
            $query->whereHas('bookingFeatures', function($q) use ($request) {
                $q->whereIn('booking_features.id', (array)$request->input('booking_features'));
            });
        }
        if ($request->input('featured')) {
            $query->where('is_featured', 1);
        }

        $this->applySorting($query, $request->input('sort'), 'hotel');
        return $query;
    }

    /**
     * Build the search query for Restaurants based on request filters.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function searchRestaurants(Request $request)
    {
        $q = $request->input('q');
        $query = Restaurant::with(['category'])->where('status', 1);
        
        if ($q) {
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%");
            });
        }
        
        if ($request->filled('city')) {
            $query->whereIn('city', (array)$request->input('city'));
        }
        if ($request->filled('category')) {
            $query->whereIn('restaurant_category_id', (array)$request->input('category'));
        }
        if ($request->filled('cuisine')) {
            $query->where('cuisine', 'like', "%{$request->input('cuisine')}%");
        }
        
        if ($request->input('featured')) {
            $query->where('is_featured', 1);
        }

        $this->applySorting($query, $request->input('sort'), 'restaurant');
        return $query;
    }

    /**
     * Build the search query for Attractions based on request filters.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function searchAttractions(Request $request)
    {
        $q = $request->input('q');
        $query = Attraction::with(['category'])->where('status', 1);
        
        if ($q) {
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%");
            });
        }
        
        if ($request->filled('city')) {
            $query->whereIn('city', (array)$request->input('city'));
        }
        if ($request->filled('category')) {
            $query->whereIn('attraction_category_id', (array)$request->input('category'));
        }
        if ($request->input('featured')) {
            $query->where('is_featured', 1);
        }
        // Assuming family_friendly / indoor exist, skipping complex logic if not in model

        $this->applySorting($query, $request->input('sort'), 'attraction');
        return $query;
    }

    /**
     * Build the search query for Events based on request filters.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function searchEvents(Request $request)
    {
        $q = $request->input('q');
        $query = Event::with(['category'])->where('status', 1);
        
        if ($q) {
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%");
            });
        }
        
        if ($request->filled('city')) {
            $query->whereIn('city', (array)$request->input('city'));
        }
        if ($request->filled('category')) {
            $query->whereIn('event_category_id', (array)$request->input('category'));
        }
        if ($request->filled('date')) {
            $query->whereDate('start_date', '>=', $request->input('date'));
        }
        if ($request->filled('type')) {
            if ($request->input('type') == 'free') {
                $query->where('price', 0);
            } elseif ($request->input('type') == 'paid') {
                $query->where('price', '>', 0);
            }
        }
        if ($request->input('upcoming')) {
            $query->where('start_date', '>=', now());
        }

        $this->applySorting($query, $request->input('sort'), 'event');
        return $query;
    }

    /**
     * Build the search query for Blogs (Travel Guides) based on request filters.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function searchBlogs(Request $request)
    {
        $q = $request->input('q');
        $query = Blog::with(['category'])->where('status', 'published');
        
        if ($q) {
            $query->where(function($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")->orWhere('content', 'like', "%{$q}%");
            });
        }
        
        if ($request->filled('category')) {
            $query->whereIn('blog_category_id', (array)$request->input('category'));
        }
        
        $this->applySorting($query, $request->input('sort'), 'blog');
        return $query;
    }

    /**
     * Apply sorting logic to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $sort
     * @param string $modelType
     * @return void
     */
    private function applySorting($query, $sort, $modelType = 'generic')
    {
        $dateColumn = ($modelType == 'blog') ? 'published_at' : 'created_at';
        $titleColumn = ($modelType == 'blog') ? 'title' : 'name';
        
        switch ($sort) {
            case 'newest':
                $query->orderBy($dateColumn, 'desc');
                break;
            case 'featured':
                if ($modelType != 'blog') $query->orderBy('is_featured', 'desc');
                $query->orderBy($dateColumn, 'desc');
                break;
            case 'price_low':
                if ($modelType == 'hotel') $query->orderBy('starting_price', 'asc');
                break;
            case 'price_high':
                if ($modelType == 'hotel') $query->orderBy('starting_price', 'desc');
                break;
            case 'alphabetical':
                $query->orderBy($titleColumn, 'asc');
                break;
            default:
                $query->orderBy($dateColumn, 'desc');
                break;
        }
    }

    /**
     * Get cached recommended items when a tab has no results.
     *
     * @param string $tab
     * @return \Illuminate\Support\Collection
     */
    private function getRecommendations(string $tab)
    {
        switch ($tab) {
            case 'hotels':
                $recs = Hotel::with(['category'])->where('status', 1)->where('is_featured', 1)->inRandomOrder()->take(12)->get();
                return $recs->isEmpty() ? Hotel::with(['category'])->where('status', 1)->latest()->take(12)->get() : $recs;

            case 'restaurants':
                $recs = Restaurant::with(['category'])->where('status', 1)->where('is_featured', 1)->inRandomOrder()->take(12)->get();
                return $recs->isEmpty() ? Restaurant::with(['category'])->where('status', 1)->latest()->take(12)->get() : $recs;

            case 'attractions':
                $recs = Attraction::with(['category'])->where('status', 1)->where('is_featured', 1)->inRandomOrder()->take(12)->get();
                return $recs->isEmpty() ? Attraction::with(['category'])->where('status', 1)->latest()->take(12)->get() : $recs;

            case 'events':
                $recs = Event::with(['category'])->where('status', 1)->where('start_date', '>=', now())->orderBy('start_date', 'asc')->take(12)->get();
                return $recs->isEmpty() ? Event::with(['category'])->where('status', 1)->latest()->take(12)->get() : $recs;

            case 'travel_guides':
                return Blog::with(['category'])->where('status', 'published')->latest('published_at')->take(12)->get();

            default:
                return collect();
        }
    }

    /**
     * Get cached hotel filter options (cities, categories, amenities, booking features).
     *
     * @return array
     */
    private function getHotelFilters()
    {
        return Cache::remember('search_filters_hotels', 3600, function () {
            return [
                'cities'           => Hotel::where('status', 1)->whereNotNull('city')->distinct()->pluck('city'),
                'categories'       => \App\Models\HotelCategory::where('status', 1)->get(),
                'amenities'        => \App\Models\Amenity::where('status', 1)->get(),
                'booking_features' => \App\Models\BookingFeature::where('is_active', 1)->get(),
            ];
        });
    }

    /**
     * Get cached restaurant filter options (cities, categories).
     *
     * @return array
     */
    private function getRestaurantFilters()
    {
        return Cache::remember('search_filters_restaurants', 3600, function () {
            return [
                'cities'     => Restaurant::where('status', 1)->whereNotNull('city')->distinct()->pluck('city'),
                'categories' => \App\Models\RestaurantCategory::where('status', 1)->get(),
            ];
        });
    }

    /**
     * Get cached attraction filter options (cities, categories).
     *
     * @return array
     */
    private function getAttractionFilters()
    {
        return Cache::remember('search_filters_attractions', 3600, function () {
            return [
                'cities'     => Attraction::where('status', 1)->whereNotNull('city')->distinct()->pluck('city'),
                'categories' => \App\Models\AttractionCategory::where('status', 1)->get(),
            ];
        });
    }

    /**
     * Get cached event filter options (cities, categories).
     *
     * @return array
     */
    private function getEventFilters()
    {
        return Cache::remember('search_filters_events', 3600, function () {
            return [
                'cities'     => Event::where('status', 1)->whereNotNull('city')->distinct()->pluck('city'),
                'categories' => \App\Models\EventCategory::where('status', 1)->get(),
            ];
        });
    }

    /**
     * Get cached blog/travel guide filter options (categories).
     *
     * @return array
     */
    private function getBlogFilters()
    {
        return Cache::remember('search_filters_blogs', 3600, function () {
            return [
                'categories' => \App\Models\BlogCategory::where('status', 1)->get(),
            ];
        });
    }
}
