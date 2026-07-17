<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\Attraction;
use App\Models\Event;
use App\Models\Blog;
use App\Models\SearchKeyword;

class SearchController extends Controller
{
    public function trackShortcut($id)
    {
        $shortcut = \App\Models\SearchShortcut::findOrFail($id);
        
        $shortcut->increment('click_count');
        $shortcut->update(['last_clicked_at' => now()]);
        
        return redirect()->to($shortcut->target_url);
    }

    public function autocomplete(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (empty($q) || strlen($q) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Helper function for mapping
        $mapItem = function($item, $routeName, $isBlog = false) {
            $img = $item->featured_image ?? $item->image ?? null;
            return [
                'id' => $item->id,
                'title' => $isBlog ? $item->title : $item->name,
                'url' => route($routeName, $item->slug),
                'image' => $img ? (str_starts_with($img, 'http') ? $img : asset($img)) : asset('website/assets/images/placeholder.jpg'),
                'location' => $isBlog ? ($item->category->name ?? 'Article') : ($item->city ?? 'Michigan'),
            ];
        };

        // Hotels
        $hotelsQuery = Hotel::where('status', 1)->where('name', 'like', "%{$q}%");
        $hotelsCount = (clone $hotelsQuery)->count();
        if ($hotelsCount > 0) {
            $results['Hotels'] = [
                'items' => $hotelsQuery->take(3)->get()->map(fn($item) => $mapItem($item, 'web.hotels.show')),
                'has_more' => $hotelsCount > 3,
                'view_all_url' => route('web.search', ['tab' => 'hotels', 'q' => $q]),
                'icon' => 'fas fa-hotel'
            ];
        }

        // Restaurants
        $restQuery = Restaurant::where('status', 1)->where('name', 'like', "%{$q}%");
        $restCount = (clone $restQuery)->count();
        if ($restCount > 0) {
            $results['Restaurants'] = [
                'items' => $restQuery->take(3)->get()->map(fn($item) => $mapItem($item, 'web.restaurants.show')),
                'has_more' => $restCount > 3,
                'view_all_url' => route('web.search', ['tab' => 'restaurants', 'q' => $q]),
                'icon' => 'fas fa-utensils'
            ];
        }

        // Attractions
        $attrQuery = Attraction::where('status', 1)->where('name', 'like', "%{$q}%");
        $attrCount = (clone $attrQuery)->count();
        if ($attrCount > 0) {
            $results['Attractions'] = [
                'items' => $attrQuery->take(3)->get()->map(fn($item) => $mapItem($item, 'web.attractions.show')),
                'has_more' => $attrCount > 3,
                'view_all_url' => route('web.search', ['tab' => 'attractions', 'q' => $q]),
                'icon' => 'fas fa-map-marked-alt'
            ];
        }

        // Events
        $eventQuery = Event::where('status', 1)->where('name', 'like', "%{$q}%");
        $eventCount = (clone $eventQuery)->count();
        if ($eventCount > 0) {
            $results['Events'] = [
                'items' => $eventQuery->take(3)->get()->map(fn($item) => $mapItem($item, 'web.events.show')),
                'has_more' => $eventCount > 3,
                'view_all_url' => route('web.search', ['tab' => 'events', 'q' => $q]),
                'icon' => 'fas fa-calendar-alt'
            ];
        }

        // Travel Guides (Blogs)
        $blogQuery = Blog::with('category')->where('status', 'published')->where('title', 'like', "%{$q}%");
        $blogCount = (clone $blogQuery)->count();
        if ($blogCount > 0) {
            $results['Travel Guides'] = [
                'items' => $blogQuery->take(3)->get()->map(fn($item) => $mapItem($item, 'web.blogs.show', true)),
                'has_more' => $blogCount > 3,
                'view_all_url' => route('web.search', ['tab' => 'travel_guides', 'q' => $q]),
                'icon' => 'fas fa-book-open'
            ];
        }

        return response()->json($results);
    }

    public function index(Request $request)
    {
        $q = $request->input('q');
        $tab = $request->input('tab', 'all');

        if ($q) {
            $keyword = SearchKeyword::firstOrCreate(['keyword' => strtolower(trim($q))]);
            if (!$keyword->wasRecentlyCreated) {
                $keyword->increment('hits');
            }
        }

        // Get total counts for tabs
        $counts = [
            'hotels' => Hotel::where('status', 1)->when($q, function($query, $q) { return $query->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%"); })->count(),
            'restaurants' => Restaurant::where('status', 1)->when($q, function($query, $q) { return $query->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%"); })->count(),
            'attractions' => Attraction::where('status', 1)->when($q, function($query, $q) { return $query->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%"); })->count(),
            'events' => Event::where('status', 1)->when($q, function($query, $q) { return $query->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%"); })->count(),
            'blogs' => Blog::where('status', 'published')->when($q, function($query, $q) { return $query->where('title', 'like', "%{$q}%")->orWhere('content', 'like', "%{$q}%"); })->count(),
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

        $recommendations = collect();
        if ($tab != 'all' && $results->isEmpty()) {
            $recommendations = \Illuminate\Support\Facades\Cache::remember("search_recommendations_{$tab}_v2", 3600, function() use ($tab) {
                if ($tab == 'hotels') {
                    $recs = \App\Models\Hotel::with(['category'])->where('status', 1)->where('is_featured', 1)->inRandomOrder()->take(4)->get();
                    return $recs->isEmpty() ? \App\Models\Hotel::with(['category'])->where('status', 1)->latest()->take(4)->get() : $recs;
                }
                if ($tab == 'restaurants') {
                    $recs = \App\Models\Restaurant::with(['category'])->where('status', 1)->where('is_featured', 1)->inRandomOrder()->take(4)->get();
                    return $recs->isEmpty() ? \App\Models\Restaurant::with(['category'])->where('status', 1)->latest()->take(4)->get() : $recs;
                }
                if ($tab == 'attractions') {
                    $recs = \App\Models\Attraction::with(['category'])->where('status', 1)->where('is_featured', 1)->inRandomOrder()->take(4)->get();
                    return $recs->isEmpty() ? \App\Models\Attraction::with(['category'])->where('status', 1)->latest()->take(4)->get() : $recs;
                }
                if ($tab == 'events') {
                    $recs = \App\Models\Event::with(['category'])->where('status', 1)->where('start_date', '>=', now())->orderBy('start_date', 'asc')->take(4)->get();
                    return $recs->isEmpty() ? \App\Models\Event::with(['category'])->where('status', 1)->latest()->take(4)->get() : $recs;
                }
                if ($tab == 'travel_guides') {
                    return \App\Models\Blog::with(['category'])->where('status', 'published')->latest()->take(4)->get();
                }
                return collect();
            });
        }

        return view('web.search', compact('results', 'q', 'tab', 'counts', 'filters', 'recommendations'));
    }

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

    private function getHotelFilters()
    {
        return [
            'cities' => Hotel::where('status', 1)->whereNotNull('city')->distinct()->pluck('city'),
            'categories' => \App\Models\HotelCategory::where('status', 1)->get(),
            'amenities' => \App\Models\Amenity::where('status', 1)->get(),
            'booking_features' => \App\Models\BookingFeature::where('is_active', 1)->get(),
        ];
    }
    
    private function getRestaurantFilters()
    {
        return [
            'cities' => Restaurant::where('status', 1)->whereNotNull('city')->distinct()->pluck('city'),
            'categories' => \App\Models\RestaurantCategory::where('status', 1)->get(),
        ];
    }

    private function getAttractionFilters()
    {
        return [
            'cities' => Attraction::where('status', 1)->whereNotNull('city')->distinct()->pluck('city'),
            'categories' => \App\Models\AttractionCategory::where('status', 1)->get(),
        ];
    }

    private function getEventFilters()
    {
        return [
            'cities' => Event::where('status', 1)->whereNotNull('city')->distinct()->pluck('city'),
            'categories' => \App\Models\EventCategory::where('status', 1)->get(),
        ];
    }

    private function getBlogFilters()
    {
        return [
            'categories' => \App\Models\BlogCategory::where('status', 1)->get(),
        ];
    }
}
