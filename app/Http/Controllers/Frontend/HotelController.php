<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = \App\Models\Hotel::with('category')->where('status', 1)->paginate(12);
        $totalHotelsCount = \App\Models\Hotel::where('status', 1)->count();
        $currentCategory = null;
        $featuredCategories = \App\Models\HotelCategory::withCount('hotels')->where('is_featured', 1)->take(10)->get();
        $allCategories = \App\Models\HotelCategory::withCount('hotels')->where('status', 1)->orderBy('name')->get();
        $page = \App\Models\Page::with('seo')->where('slug', 'hotels')->first();
        return view('web.hotels.index', compact('hotels', 'totalHotelsCount', 'currentCategory', 'featuredCategories', 'allCategories', 'page'));
    }

    public function category($slug)
    {
        $category = \App\Models\HotelCategory::where('slug', $slug)->first();
        if (!$category) {
            abort(404);
        }
        $hotels = \App\Models\Hotel::with('category')->where('hotel_category_id', $category->id)->where('status', 1)->paginate(12);
        $totalHotelsCount = \App\Models\Hotel::where('status', 1)->count();
        $currentCategory = $category;
        $featuredCategories = \App\Models\HotelCategory::withCount('hotels')->where('is_featured', 1)->take(10)->get();
        $allCategories = \App\Models\HotelCategory::withCount('hotels')->where('status', 1)->orderBy('name')->get();
        $page = \App\Models\Page::with('seo')->where('slug', 'hotels')->first();
        return view('web.hotels.index', compact('hotels', 'totalHotelsCount', 'currentCategory', 'featuredCategories', 'allCategories', 'page'));
    }

    public function show($slug)
    {
        $hotel = \App\Models\Hotel::with(['seo', 'category', 'amenities', 'images'])->where('slug', $slug)->where('status', 1)->first();
        
        if (!$hotel) {
            // Static Fallback Data for UI Demonstration
            $hotel = (object)[
                'name' => 'The Grand Resort & Spa',
                'slug' => 'demo',
                'city' => 'Mackinac Island',
                'location' => 'Mackinac Island, MI',
                'description' => 'Experience the pinnacle of luxury with breathtaking views, world-class amenities, and exquisite dining. This historic property offers an unforgettable getaway on the shores of Lake Huron.',
                'starting_price' => '399',
                'affiliate_url' => '#',
                'featured_image' => 'storage/demo/michigan_resort_exterior_1783683587847.png',
                'image' => null,
                'images' => collect([]),
                'category' => (object)['name' => 'Luxury Resort']
            ];
        }

        return view('web.hotels.show', compact('hotel'));
    }
}
