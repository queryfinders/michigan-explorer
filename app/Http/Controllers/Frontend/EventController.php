<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Event::with('category')->where('status', 1);

        $filter = $request->get('filter');
        $now = Carbon::now();

        if ($filter == 'this-week') {
            $query->whereBetween('start_date', [$now, $now->copy()->addDays(7)]);
        } elseif ($filter == 'this-month') {
            $query->whereBetween('start_date', [$now, $now->copy()->addDays(30)]);
        } elseif ($filter == 'past') {
            $query->where('end_date', '<', $now);
        } else {
            // Default "All Upcoming"
            $query->where(function($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            });
        }

        $events = $query->orderBy('start_date', 'asc')->paginate(12);
        
        $currentCategory = null;
        $featuredCategories = \App\Models\EventCategory::where('is_featured', 1)->take(10)->get();
        $allCategories = \App\Models\EventCategory::where('status', 1)->orderBy('name')->get();
        $page = \App\Models\Page::with('seo')->where('slug', 'events')->first();
        
        return view('web.events.index', compact('events', 'currentCategory', 'featuredCategories', 'allCategories', 'filter', 'page'));
    }

    public function category(Request $request, $slug)
    {
        $category = \App\Models\EventCategory::where('slug', $slug)->first();
        if (!$category) {
            abort(404);
        }

        $query = \App\Models\Event::with('category')->where('event_category_id', $category->id)->where('status', 1);

        $filter = $request->get('filter');
        $now = Carbon::now();

        if ($filter == 'this-week') {
            $query->whereBetween('start_date', [$now, $now->copy()->addDays(7)]);
        } elseif ($filter == 'this-month') {
            $query->whereBetween('start_date', [$now, $now->copy()->addDays(30)]);
        } elseif ($filter == 'past') {
            $query->where('end_date', '<', $now);
        } else {
            // Default "All Upcoming"
            $query->where(function($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            });
        }

        $events = $query->orderBy('start_date', 'asc')->paginate(12);

        $currentCategory = $category;
        $featuredCategories = \App\Models\EventCategory::where('is_featured', 1)->take(10)->get();
        $allCategories = \App\Models\EventCategory::where('status', 1)->orderBy('name')->get();
        $page = \App\Models\Page::with('seo')->where('slug', 'events')->first();
        
        return view('web.events.index', compact('events', 'currentCategory', 'featuredCategories', 'allCategories', 'filter', 'page'));
    }

    public function show($slug)
    {
        $event = \App\Models\Event::with(['category', 'seo'])->where('slug', $slug)->where('status', 1)->first();
        
        if (!$event) {
            // Static Fallback Data for UI Demonstration
            $event = (object)[
                'name' => 'Grand Rapids Art Festival',
                'slug' => 'demo',
                'city' => 'Grand Rapids',
                'state' => 'MI',
                'zip' => '49503',
                'address' => 'Downtown Grand Rapids',
                'venue_name' => 'Calder Plaza',
                'description' => '<p>Join us for the annual Grand Rapids Art Festival! This three-day event features incredible live music, delicious local food vendors, and stunning artwork from over 200 regional artists.</p><p>Experience hands-on art activities for the whole family, live demonstrations, and the vibrant atmosphere of downtown Grand Rapids in the summer.</p>',
                'featured_image' => 'storage/demo/michigan_sleeping_bear_1783683642640.png',
                'phone' => '(616) 459-2787',
                'website' => 'https://www.festivalgr.org/',
                'start_date' => Carbon::now()->addDays(2)->setHour(10)->setMinute(0),
                'end_date' => Carbon::now()->addDays(4)->setHour(20)->setMinute(0),
                'price' => 0.00,
                'category' => (object)['name' => 'Arts & Culture', 'icon' => 'fas fa-palette', 'slug' => 'arts-culture']
            ];
        }
        
        // Find other events near this one
        $moreEvents = \App\Models\Event::where('city', $event->city)
                        ->where('status', 1)
                        ->where('id', '!=', $event->id ?? 0)
                        ->take(3)
                        ->get();
        
        return view('web.events.show', compact('event', 'moreEvents'));
    }
}
