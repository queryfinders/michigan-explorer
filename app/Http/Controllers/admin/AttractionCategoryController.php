<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Sortable;

class AttractionCategoryController extends Controller
{
    use Sortable;
    public function index(Request $request)
    {
        $query = \App\Models\AttractionCategory::query();
        $query = $this->applySorting($query, ['id', 'name', 'status', 'created_at'], 'created_at', 'desc');
        $categories = $query->paginate(10);
        
        if ($request->ajax()) {
            return view('new_content.admin.attraction_categories._table', compact('categories'))->render();
        }
        
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
    
    public function quickStore(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attraction_categories'
        ]);

        $category = \App\Models\AttractionCategory::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status ?? 1
        ]);

        return response()->json(['success' => true, 'category' => $category]);
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

    public function changeStatus($id, $status)
    {
        $category = \App\Models\AttractionCategory::findOrFail($id);
        $category->status = $status;
        $category->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}
