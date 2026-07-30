<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantCuisine;
use App\Traits\Sortable;

class CuisineController extends Controller
{
    use Sortable;
    public function index(Request $request)
    {
        $query = RestaurantCuisine::query();
        $query = $this->applySorting($query, ['id', 'name', 'slug', 'sort_order', 'status', 'created_at'], 'sort_order', 'asc');
        $cuisines = $query->paginate(10);
        
        if ($request->ajax()) {
            return view('new_content.admin.cuisines._table', compact('cuisines'))->render();
        }
        return view('new_content.admin.cuisines.index', compact('cuisines'));
    }

    public function create()
    {
        return view('new_content.admin.cuisines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:restaurant_cuisines,name',
            'slug'       => 'required|string|max:255|unique:restaurant_cuisines,slug',
            'status'     => 'boolean',
            'sort_order' => 'required|integer|unique:restaurant_cuisines,sort_order'
        ]);

        RestaurantCuisine::create($request->all());
        return redirect()->route('cuisines.index')->with('success', 'Cuisine created successfully.');
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:restaurant_cuisines,name',
            'slug'   => 'required|string|max:255|unique:restaurant_cuisines,slug',
            'status' => 'boolean'
        ]);

        $cuisine = RestaurantCuisine::create([
            'name'       => $request->name,
            'slug'       => $request->slug,
            'status'     => $request->input('status', 1),
            'sort_order' => 0
        ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'   => $cuisine->id,
                'name' => $cuisine->name
            ]
        ]);
    }

    public function edit(RestaurantCuisine $cuisine)
    {
        return view('new_content.admin.cuisines.edit', compact('cuisine'));
    }

    public function update(Request $request, RestaurantCuisine $cuisine)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:restaurant_cuisines,name,' . $cuisine->id,
            'slug'       => 'required|string|max:255|unique:restaurant_cuisines,slug,' . $cuisine->id,
            'status'     => 'boolean',
            'sort_order' => 'required|integer|unique:restaurant_cuisines,sort_order,' . $cuisine->id
        ]);

        $cuisine->update($request->all());
        return redirect()->route('cuisines.index')->with('success', 'Cuisine updated successfully.');
    }

    public function destroy(RestaurantCuisine $cuisine)
    {
        $cuisine->delete();
        return redirect()->route('cuisines.index')->with('success', 'Cuisine deleted successfully.');
    }

    public function changeStatus($id, $status)
    {
        $cuisine = RestaurantCuisine::findOrFail($id);
        $cuisine->status = $status;
        $cuisine->save();
        return response()->json(['success' => true, 'status' => $cuisine->status]);
    }
}
