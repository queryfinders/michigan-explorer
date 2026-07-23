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
use Illuminate\Support\Facades\Cache;

/**
 * Class SearchShortcutController
 *
 * Manages the dynamic search shortcuts displayed on the global search page.
 * Provides CRUD functionality for administrators.
 */
class SearchShortcutController extends Controller
{
    /**
     * Display a listing of the search shortcuts.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $searchShortcuts = SearchShortcut::orderBy('sort_order', 'asc')->get();
        return view('new_content.admin.search_shortcuts.index', compact('searchShortcuts'));
    }

    /**
     * Show the form for creating a new search shortcut.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        // Fetch shared category data (cached)
        $categories = $this->getCategoryData();

        return view('new_content.admin.search_shortcuts.create', $categories);
    }

    /**
     * Store a newly created search shortcut in storage.
     *
     * @param StoreSearchShortcutRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSearchShortcutRequest $request)
    {
        SearchShortcut::create($request->validated());
        return redirect()->route('search-shortcuts.index')->with('success', 'Search Shortcut created successfully.');
    }

    /**
     * Show the form for editing the specified search shortcut.
     *
     * @param SearchShortcut $searchShortcut
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(SearchShortcut $searchShortcut)
    {
        // Merge cached categories with the current shortcut for the view
        $categories = $this->getCategoryData();

        return view('new_content.admin.search_shortcuts.edit', array_merge($categories, compact('searchShortcut')));
    }

    /**
     * Update the specified search shortcut in storage.
     *
     * @param UpdateSearchShortcutRequest $request
     * @param SearchShortcut $searchShortcut
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSearchShortcutRequest $request, SearchShortcut $searchShortcut)
    {
        $searchShortcut->update($request->validated());
        return redirect()->route('search-shortcuts.index')->with('success', 'Search Shortcut updated successfully.');
    }

    /**
     * Remove the specified search shortcut from storage.
     *
     * @param SearchShortcut $searchShortcut
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(SearchShortcut $searchShortcut)
    {
        $searchShortcut->delete();
        return redirect()->route('search-shortcuts.index')->with('success', 'Search Shortcut deleted successfully.');
    }

    /**
     * Toggle the status (active/inactive) of a search shortcut via AJAX.
     *
     * @param Request $request
     * @param SearchShortcut $searchShortcut
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request, SearchShortcut $searchShortcut)
    {
        $searchShortcut->status = $request->status;
        $searchShortcut->save();
        return response()->json(['success' => true]);
    }

    /**
     * Reorder the search shortcuts based on drag-and-drop actions.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request)
    {
        $orderedIds = $request->input('ordered_ids');

        if (is_array($orderedIds)) {
            // Use upsert to update all sort_order values in a single query
            $rows = collect($orderedIds)->map(fn ($id, $index) => ['id' => $id, 'sort_order' => $index])->toArray();
            SearchShortcut::upsert($rows, ['id'], ['sort_order']);
        }

        return response()->json(['success' => true]);
    }

    // =========================================================================
    // Private Helpers
    // =========================================================================

    /**
     * Fetch all category data needed for the shortcut form, with caching.
     * Caches for 1 hour to avoid repeated DB queries on every admin form load.
     *
     * @return array
     */
    private function getCategoryData(): array
    {
        return Cache::remember('shortcut_form_categories', 3600, function () {
            return [
                'hotelCategories'       => HotelCategory::orderBy('name')->get(),
                'restaurantCategories'  => RestaurantCategory::orderBy('name')->get(),
                'attractionCategories'  => AttractionCategory::orderBy('name')->get(),
                'eventCategories'       => EventCategory::orderBy('name')->get(),
                'blogCategories'        => BlogCategory::orderBy('name')->get(),
            ];
        });
    }
}
