<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchShortcut;
use App\Models\HotelCategory;
use App\Models\RestaurantCategory;
use App\Models\AttractionCategory;
use App\Models\EventCategory;
use App\Models\BlogCategory;
use App\Http\Requests\Admin\StoreSearchShortcutRequest;
use App\Http\Requests\Admin\UpdateSearchShortcutRequest;
use Illuminate\Http\Request;

class SearchShortcutController extends Controller
{
    public function index()
    {
        $searchShortcuts = SearchShortcut::orderBy('sort_order', 'asc')->get();
        return view('new_content.admin.search_shortcuts.index', compact('searchShortcuts'));
    }

    public function create()
    {
        $hotelCategories = HotelCategory::all();
        $restaurantCategories = RestaurantCategory::all();
        $attractionCategories = AttractionCategory::all();
        $eventCategories = EventCategory::all();
        $blogCategories = BlogCategory::all();
        
        return view('new_content.admin.search_shortcuts.create', compact(
            'hotelCategories', 
            'restaurantCategories', 
            'attractionCategories', 
            'eventCategories', 
            'blogCategories'
        ));
    }

    public function store(StoreSearchShortcutRequest $request)
    {
        SearchShortcut::create($request->validated());
        return redirect()->route('search-shortcuts.index')->with('success', 'Search Shortcut created successfully.');
    }

    public function edit(SearchShortcut $searchShortcut)
    {
        $hotelCategories = HotelCategory::all();
        $restaurantCategories = RestaurantCategory::all();
        $attractionCategories = AttractionCategory::all();
        $eventCategories = EventCategory::all();
        $blogCategories = BlogCategory::all();

        return view('new_content.admin.search_shortcuts.edit', compact(
            'searchShortcut',
            'hotelCategories', 
            'restaurantCategories', 
            'attractionCategories', 
            'eventCategories', 
            'blogCategories'
        ));
    }

    public function update(UpdateSearchShortcutRequest $request, SearchShortcut $searchShortcut)
    {
        $searchShortcut->update($request->validated());
        return redirect()->route('search-shortcuts.index')->with('success', 'Search Shortcut updated successfully.');
    }

    public function destroy(SearchShortcut $searchShortcut)
    {
        $searchShortcut->delete();
        return redirect()->route('search-shortcuts.index')->with('success', 'Search Shortcut deleted successfully.');
    }

    public function changeStatus(Request $request, SearchShortcut $searchShortcut)
    {
        $searchShortcut->status = $request->status;
        $searchShortcut->save();
        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $orderedIds = $request->input('ordered_ids');
        if (is_array($orderedIds)) {
            foreach ($orderedIds as $index => $id) {
                SearchShortcut::where('id', $id)->update(['sort_order' => $index]);
            }
        }
        return response()->json(['success' => true]);
    }
}
