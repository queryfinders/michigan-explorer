<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = \App\Models\Event::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('new_content.admin.events.index', compact('events'));
    }

    public function create()
    {
        $categories = \App\Models\EventCategory::where('status', 1)->get();
        return view('new_content.admin.events.create', compact('categories'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events',
            'event_category_id' => 'required|exists:event_categories,id',
            'status' => 'boolean'
        ]);

        $event = \App\Models\Event::create($request->except('_token', '_method', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup'));
        
        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup']);
        $event->seo()->create($seoData);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function edit(\App\Models\Event $event)
    {
        $event->load('seo');
        $categories = \App\Models\EventCategory::where('status', 1)->get();
        return view('new_content.admin.events.edit', compact('event', 'categories'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events,slug,' . $event->id,
            'event_category_id' => 'required|exists:event_categories,id',
            'status' => 'boolean'
        ]);

        $event->update($request->except('_token', '_method', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup'));
        
        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup']);
        if ($event->seo) {
            $event->seo->update($seoData);
        } else {
            $event->seo()->create($seoData);
        }

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(\App\Models\Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}
