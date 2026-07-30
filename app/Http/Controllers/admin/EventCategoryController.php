<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Sortable;

class EventCategoryController extends Controller
{
    use Sortable;
    public function index(Request $request)
    {
        $query = \App\Models\EventCategory::query();
        $query = $this->applySorting($query, ['id', 'name', 'status', 'created_at'], 'created_at', 'desc');
        $categories = $query->paginate(10);
        
        if ($request->ajax()) {
            return view('new_content.admin.event_categories._table', compact('categories'))->render();
        }
        
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

    public function quickStore(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:event_categories'
        ]);

        $category = \App\Models\EventCategory::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status ?? 1
        ]);

        return response()->json(['success' => true, 'category' => $category]);
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

    public function changeStatus($id, $status)
    {
        $category = \App\Models\EventCategory::findOrFail($id);
        $category->status = $status;
        $category->save();

        return response()->json(['success' => true]);
    }
}
