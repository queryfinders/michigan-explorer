<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = \App\Models\Restaurant::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('new_content.admin.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        $categories = \App\Models\RestaurantCategory::where('status', 1)->get();
        return view('new_content.admin.restaurants.create', compact('categories'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:restaurants',
            'restaurant_category_id' => 'required|exists:restaurant_categories,id',
            'status' => 'boolean'
        ]);

        $restaurant = \App\Models\Restaurant::create($request->except('_token', '_method', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup'));
        
        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup']);
        $restaurant->seo()->create($seoData);

        return redirect()->route('restaurants.index')->with('success', 'Restaurant created successfully.');
    }

    public function edit(\App\Models\Restaurant $restaurant)
    {
        $restaurant->load('seo');
        $categories = \App\Models\RestaurantCategory::where('status', 1)->get();
        return view('new_content.admin.restaurants.edit', compact('restaurant', 'categories'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Restaurant $restaurant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:restaurants,slug,' . $restaurant->id,
            'restaurant_category_id' => 'required|exists:restaurant_categories,id',
            'status' => 'boolean'
        ]);

        $restaurant->update($request->except('_token', '_method', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup'));
        
        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup']);
        if ($restaurant->seo) {
            $restaurant->seo->update($seoData);
        } else {
            $restaurant->seo()->create($seoData);
        }

        return redirect()->route('restaurants.index')->with('success', 'Restaurant updated successfully.');
    }

    public function destroy(\App\Models\Restaurant $restaurant)
    {
        $restaurant->delete();
        return redirect()->route('restaurants.index')->with('success', 'Restaurant deleted successfully.');
    }
}
