<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttractionCategoryController extends Controller
{
    public function index()
    {
        $categories = \App\Models\AttractionCategory::orderBy('created_at', 'desc')->paginate(10);
        return view('new_content.admin.attraction_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('new_content.admin.attraction_categories.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attraction_categories',
            'status' => 'boolean'
        ]);

        \App\Models\AttractionCategory::create($request->all());
        return redirect()->route('attraction-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(\App\Models\AttractionCategory $attractionCategory)
    {
        return view('new_content.admin.attraction_categories.edit', compact('attractionCategory'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\AttractionCategory $attractionCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attraction_categories,slug,' . $attractionCategory->id,
            'status' => 'boolean'
        ]);

        $attractionCategory->update($request->all());
        return redirect()->route('attraction-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(\App\Models\AttractionCategory $attractionCategory)
    {
        $attractionCategory->delete();
        return redirect()->route('attraction-categories.index')->with('success', 'Category deleted successfully.');
    }
}
