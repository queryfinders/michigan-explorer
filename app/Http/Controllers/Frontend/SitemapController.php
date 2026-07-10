<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];

        // Static Pages
        $urls[] = ['loc' => route('web.home'), 'lastmod' => date('Y-m-d')];
        $urls[] = ['loc' => route('web.hotels.index'), 'lastmod' => date('Y-m-d')];
        $urls[] = ['loc' => route('web.restaurants.index'), 'lastmod' => date('Y-m-d')];
        $urls[] = ['loc' => route('web.attractions.index'), 'lastmod' => date('Y-m-d')];
        $urls[] = ['loc' => route('web.events.index'), 'lastmod' => date('Y-m-d')];
        $urls[] = ['loc' => route('web.blogs.index'), 'lastmod' => date('Y-m-d')];

        // Dynamic Pages
        $hotels = \App\Models\Hotel::where('status', 1)->get();
        foreach($hotels as $h) {
            $urls[] = ['loc' => route('web.hotels.show', $h->slug), 'lastmod' => $h->updated_at->format('Y-m-d')];
        }

        $restaurants = \App\Models\Restaurant::where('status', 1)->get();
        foreach($restaurants as $r) {
            $urls[] = ['loc' => route('web.restaurants.show', $r->slug), 'lastmod' => $r->updated_at->format('Y-m-d')];
        }

        $attractions = \App\Models\Attraction::where('status', 1)->get();
        foreach($attractions as $a) {
            $urls[] = ['loc' => route('web.attractions.show', $a->slug), 'lastmod' => $a->updated_at->format('Y-m-d')];
        }

        $events = \App\Models\Event::where('status', 1)->get();
        foreach($events as $e) {
            $urls[] = ['loc' => route('web.events.show', $e->slug), 'lastmod' => $e->updated_at->format('Y-m-d')];
        }

        $blogs = \App\Models\Blog::where('status', 'published')->get();
        foreach($blogs as $b) {
            $urls[] = ['loc' => route('web.blogs.show', $b->slug), 'lastmod' => $b->updated_at->format('Y-m-d')];
        }

        return response()->view('web.sitemap', compact('urls'))->header('Content-Type', 'text/xml');
    }
}
