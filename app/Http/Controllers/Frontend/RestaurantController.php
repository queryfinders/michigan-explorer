<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = \App\Models\Restaurant::with('category')->where('status', 1)->paginate(12);
        $currentCategory = null;
        $featuredCategories = \App\Models\RestaurantCategory::where('is_featured', 1)->take(10)->get();
        $allCategories = \App\Models\RestaurantCategory::where('status', 1)->orderBy('name')->get();
        return view('web.restaurants.index', compact('restaurants', 'currentCategory', 'featuredCategories', 'allCategories'));
    }

    public function category($slug)
    {
        $category = \App\Models\RestaurantCategory::where('slug', $slug)->first();
        if (!$category) {
            abort(404);
        }
        $restaurants = \App\Models\Restaurant::with('category')->where('category_id', $category->id)->where('status', 1)->paginate(12);
        $currentCategory = $category;
        $featuredCategories = \App\Models\RestaurantCategory::where('is_featured', 1)->take(10)->get();
        $allCategories = \App\Models\RestaurantCategory::where('status', 1)->orderBy('name')->get();
        return view('web.restaurants.index', compact('restaurants', 'currentCategory', 'featuredCategories', 'allCategories'));
    }

    public function show($slug)
    {
        $restaurant = \App\Models\Restaurant::where('slug', $slug)->where('status', 1)->first();
        
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

        return view('web.restaurants.show', compact('restaurant'));
    }
}
