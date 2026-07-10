<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = \App\Models\Event::with('category')->where('status', 1)->orderBy('start_date', 'asc')->paginate(12);
        return view('web.events.index', compact('events'));
    }

    public function show($slug)
    {
        $event = \App\Models\Event::where('slug', $slug)->where('status', 1)->firstOrFail();
        return view('web.events.show', compact('event'));
    }
}
