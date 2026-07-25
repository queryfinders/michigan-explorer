<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttractionController extends Controller
{
    public function index()
    {
        $attractions = \App\Models\Attraction::with('category')->where('status', 1)->paginate(12);
        $currentCategory = null;
        $featuredCategories = \App\Models\AttractionCategory::withCount('attractions')->where('is_featured', 1)->take(10)->get();
        $allCategories = \App\Models\AttractionCategory::withCount('attractions')->where('status', 1)->orderBy('name')->get();
        $totalAttractionsCount = \App\Models\Attraction::where('status', 1)->count();
        $page = \App\Models\Page::with('seo')->where('slug', 'attractions')->first();
        return view('web.attractions.index', compact('attractions', 'currentCategory', 'featuredCategories', 'allCategories', 'totalAttractionsCount', 'page'));
    }

    public function category($slug)
    {
        $category = \App\Models\AttractionCategory::where('slug', $slug)->first();
        if (!$category) {
            abort(404);
        }
        $attractions = \App\Models\Attraction::with('category')->where('attraction_category_id', $category->id)->where('status', 1)->paginate(12);
        $currentCategory = $category;
        $featuredCategories = \App\Models\AttractionCategory::withCount('attractions')->where('is_featured', 1)->take(10)->get();
        $allCategories = \App\Models\AttractionCategory::withCount('attractions')->where('status', 1)->orderBy('name')->get();
        $totalAttractionsCount = \App\Models\Attraction::where('status', 1)->count();
        $page = \App\Models\Page::with('seo')->where('slug', 'attractions')->first();
        return view('web.attractions.index', compact('attractions', 'currentCategory', 'featuredCategories', 'allCategories', 'totalAttractionsCount', 'page'));
    }

    public function show($slug)
    {
        $attraction = \App\Models\Attraction::with('seo')->where('slug', $slug)->where('status', 1)->first();
        if (!$attraction) {
            // Static Fallback Data for UI Demonstration
            $attraction = (object)[
                'name' => 'Sleeping Bear Dunes National Lakeshore',
                'slug' => 'demo',
                'city' => 'Empire',
                'state' => 'MI',
                'zip' => '49630',
                'address' => '9922 Front Street',
                'description' => '<p>Experience towering sand dunes and spectacular views of Lake Michigan at this national lakeshore. Miles of sandy beaches, lush forests, clear inland lakes, and unique flora and fauna make up the natural world of Sleeping Bear Dunes.</p><p>High perched dunes afford spectacular views across the lake. An island lighthouse, US Life-Saving Service stations, coastal villages, and picturesque farmsteads reflect the rich maritime, agricultural, and recreational history of the area.</p>',
                'featured_image' => 'storage/demo/michigan_sleeping_bear_1783683642640.png',
                'phone' => '(231) 326-4700',
                'website' => 'https://www.nps.gov/slbe/index.htm',
                'visitor_information' => '<strong>Hours:</strong> Open 24 hours year-round.<br><strong>Fees:</strong> $25 per vehicle for a 7-day pass.',
                'category' => (object)['name' => 'Parks & Nature']
            ];
        }
        $nearbyHotels = \App\Models\Hotel::where('city', $attraction->city)->where('status', 1)->take(4)->get();
        $nearbyRestaurants = \App\Models\Restaurant::where('city', $attraction->city)->where('status', 1)->take(4)->get();
        
        return view('web.attractions.show', compact('attraction', 'nearbyHotels', 'nearbyRestaurants'));
    }
}
