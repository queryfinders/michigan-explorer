<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $restaurants = \App\Models\Restaurant::with(['category', 'features', 'cuisines'])->where('status', 1)->paginate(9);
        $currentCategory = null;
        $featuredCategories = \App\Models\RestaurantCategory::withCount(['restaurants' => fn($q) => $q->where('status', 1)])->where('is_featured', 1)->take(7)->get();
        $allCategories    = \App\Models\RestaurantCategory::withCount(['restaurants' => fn($q) => $q->where('status', 1)])->where('status', 1)->orderBy('name')->get();
        $totalRestaurants = \App\Models\Restaurant::where('status', 1)->count();
        $page = \App\Models\Page::with('seo')->where('slug', 'restaurants')->first();

        if ($request->ajax()) {
            return view('web.restaurants._restaurants_grid', compact('restaurants'))->render();
        }

        return view('web.restaurants.index', compact('restaurants', 'currentCategory', 'featuredCategories', 'allCategories', 'totalRestaurants', 'page'));
    }

    public function category(Request $request, $slug)
    {
        $category = \App\Models\RestaurantCategory::where('slug', $slug)->first();
        if (!$category) {
            abort(404);
        }
        $restaurants = \App\Models\Restaurant::with(['category', 'features', 'cuisines'])->where('restaurant_category_id', $category->id)->where('status', 1)->paginate(9);
        $currentCategory = $category;
        $featuredCategories = \App\Models\RestaurantCategory::withCount(['restaurants' => fn($q) => $q->where('status', 1)])->where('is_featured', 1)->take(7)->get();
        $allCategories    = \App\Models\RestaurantCategory::withCount(['restaurants' => fn($q) => $q->where('status', 1)])->where('status', 1)->orderBy('name')->get();
        $totalRestaurants = \App\Models\Restaurant::where('status', 1)->count();
        $page = \App\Models\Page::with('seo')->where('slug', 'restaurants')->first();

        if ($request->ajax()) {
            return view('web.restaurants._restaurants_grid', compact('restaurants'))->render();
        }

        return view('web.restaurants.index', compact('restaurants', 'currentCategory', 'featuredCategories', 'allCategories', 'totalRestaurants', 'page'));
    }

    public function show($slug)
    {
        $restaurant = \App\Models\Restaurant::with(['seo', 'features', 'cuisines', 'images', 'faqs'])->where('slug', $slug)->where('status', 1)->first();
        
        if (!$restaurant) {
            // Static Fallback Data for UI Demonstration
            $restaurant = (object)[
                'name' => 'The Lakeside Bistro',
                'slug' => 'demo',
                'city' => 'Traverse City',
                'state' => 'MI',
                'zip' => '49684',
                'address' => '123 Marina Drive',
                'description' => 'Experience the finest waterfront dining in Traverse City. Our culinary team crafts exquisite dishes using locally sourced ingredients, perfectly paired with our award-winning wine selection.',
                'starting_price' => '45',
                'affiliate_url' => '#',
                'featured_image' => 'storage/demo/michigan_hotel_lobby_1783683621508.png', // Using existing local image as placeholder
                'image' => null,
                'category' => (object)['name' => 'Fine Dining']
            ];
        }

        // Query attractions in the same city as the restaurant dynamically
        $nearbyAttractions = \App\Models\Attraction::where('status', 1)
            ->where('city', $restaurant->city ?? '')
            ->take(2)
            ->get();

        // Fetch active restaurant_detail placement promotion
        $detailPromotion = \App\Models\AffiliatePromotion::forPlacement('restaurant_detail');

        return view('web.restaurants.show', compact('restaurant', 'nearbyAttractions', 'detailPromotion'));
    }
}
