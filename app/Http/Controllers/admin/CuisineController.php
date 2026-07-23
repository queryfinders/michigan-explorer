<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantCuisine;

class CuisineController extends Controller
{
    public function index()
    {
        $cuisines = RestaurantCuisine::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10);
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
            'sort_order' => 'integer'
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
            'sort_order' => 'integer'
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
        $cuisine->status = $status == 1 ? 0 : 1;
        $cuisine->save();
        return redirect()->route('cuisines.index')->with('success', 'Status updated successfully.');
    }
}
