<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $q = $request->input('q');
        
        $results = collect();

        if ($q) {
            // Log search keyword
            $keyword = \App\Models\SearchKeyword::firstOrCreate(['keyword' => strtolower(trim($q))]);
            if (!$keyword->wasRecentlyCreated) {
                $keyword->increment('hits');
            }

            // Search Hotels
            $hotels = \App\Models\Hotel::where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
                ->where('status', 1)->take(5)->get();
            foreach($hotels as $h) {
                $results->push((object)[
                    'type' => 'Hotel',
                    'title' => $h->name,
                    'url' => route('web.hotels.show', $h->slug),
                    'description' => \Str::limit(strip_tags($h->description), 150),
                    'image' => $h->featured_image
                ]);
            }

            // Search Restaurants
            $restaurants = \App\Models\Restaurant::where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
                ->where('status', 1)->take(5)->get();
            foreach($restaurants as $r) {
                $results->push((object)[
                    'type' => 'Dining',
                    'title' => $r->name,
                    'url' => route('web.restaurants.show', $r->slug),
                    'description' => \Str::limit(strip_tags($r->description), 150),
                    'image' => $r->featured_image
                ]);
            }

            // Search Attractions
            $attractions = \App\Models\Attraction::where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
                ->where('status', 1)->take(5)->get();
            foreach($attractions as $a) {
                $results->push((object)[
                    'type' => 'Attraction',
                    'title' => $a->name,
                    'url' => route('web.attractions.show', $a->slug),
                    'description' => \Str::limit(strip_tags($a->description), 150),
                    'image' => $a->featured_image
                ]);
            }

            // Search Events
            $events = \App\Models\Event::where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
                ->where('status', 1)->take(5)->get();
            foreach($events as $e) {
                $results->push((object)[
                    'type' => 'Event',
                    'title' => $e->name,
                    'url' => route('web.events.show', $e->slug),
                    'description' => \Str::limit(strip_tags($e->description), 150),
                    'image' => $e->featured_image
                ]);
            }
        }

        return view('web.search', compact('results', 'q'));
    }
}
