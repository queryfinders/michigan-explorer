<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventCategoryController extends Controller
{
    public function index()
    {
        $categories = \App\Models\EventCategory::orderBy('created_at', 'desc')->paginate(10);
        return view('new_content.admin.event_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('new_content.admin.event_categories.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:event_categories',
            'status' => 'boolean'
        ]);

        \App\Models\EventCategory::create($request->except('_token', '_method'));
        return redirect()->route('event-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(\App\Models\EventCategory $eventCategory)
    {
        return view('new_content.admin.event_categories.edit', compact('eventCategory'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\EventCategory $eventCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:event_categories,slug,' . $eventCategory->id,
            'status' => 'boolean'
        ]);

        $eventCategory->update($request->except('_token', '_method'));
        return redirect()->route('event-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(\App\Models\EventCategory $eventCategory)
    {
        $eventCategory->delete();
        return redirect()->route('event-categories.index')->with('success', 'Category deleted successfully.');
    }
}
