<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantCategoryController extends Controller
{
    public function index()
    {
        $categories = \App\Models\RestaurantCategory::orderBy('created_at', 'desc')->paginate(10);
        return view('new_content.admin.restaurant_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('new_content.admin.restaurant_categories.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:restaurant_categories',
            'status' => 'boolean'
        ]);

        \App\Models\RestaurantCategory::create($request->all());
        return redirect()->route('restaurant-categories.index')->with('success', 'Category created successfully.');
    }

    public function quickStore(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:restaurant_categories',
            'status' => 'boolean'
        ]);

        $category = \App\Models\RestaurantCategory::create($request->all());
        return response()->json(['success' => true, 'category' => $category]);
    }

    public function edit(\App\Models\RestaurantCategory $restaurantCategory)
    {
        return view('new_content.admin.restaurant_categories.edit', compact('restaurantCategory'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\RestaurantCategory $restaurantCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:restaurant_categories,slug,' . $restaurantCategory->id,
            'status' => 'boolean'
        ]);

        $restaurantCategory->update($request->all());
        return redirect()->route('restaurant-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(\App\Models\RestaurantCategory $restaurantCategory)
    {
        $restaurantCategory->delete();
        return redirect()->route('restaurant-categories.index')->with('success', 'Category deleted successfully.');
    }
}
