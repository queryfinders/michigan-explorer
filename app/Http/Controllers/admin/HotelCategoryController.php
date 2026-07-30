<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Sortable;

class HotelCategoryController extends Controller
{
    use Sortable;
    public function index(Request $request)
    {
        $query = \App\Models\HotelCategory::query();
        $query = $this->applySorting($query, ['id', 'name', 'status', 'created_at'], 'created_at', 'desc');
        $categories = $query->paginate(10);
        
        if ($request->ajax()) {
            return view('new_content.admin.hotel_categories._table', compact('categories'))->render();
        }
        
        return view('new_content.admin.hotel_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('new_content.admin.hotel_categories.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:hotel_categories,name',
            'slug' => 'required|string|max:255|unique:hotel_categories,slug',
            'status' => 'boolean'
        ]);

        \App\Models\HotelCategory::create($request->all());
        return redirect()->route('hotel-categories.index')->with('success', 'Category created successfully.');
    }

    public function quickStore(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:hotel_categories,name',
            'slug' => 'required|string|max:255|unique:hotel_categories,slug',
            'status' => 'boolean'
        ]);

        $category = \App\Models\HotelCategory::create($request->all());
        return response()->json(['success' => true, 'category' => $category]);
    }

    public function edit(\App\Models\HotelCategory $hotelCategory)
    {
        return view('new_content.admin.hotel_categories.edit', compact('hotelCategory'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\HotelCategory $hotelCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:hotel_categories,name,' . $hotelCategory->id,
            'slug' => 'required|string|max:255|unique:hotel_categories,slug,' . $hotelCategory->id,
            'status' => 'boolean'
        ]);

        $hotelCategory->update($request->all());
        return redirect()->route('hotel-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(\App\Models\HotelCategory $hotelCategory)
    {
        $hotelCategory->delete();
        return redirect()->route('hotel-categories.index')->with('success', 'Category deleted successfully.');
    }

    public function changeStatus($id, $status)
    {
        $category = \App\Models\HotelCategory::findOrFail($id);
        $category->status = $status;
        $category->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.', 'status' => $category->status]);
    }
}
