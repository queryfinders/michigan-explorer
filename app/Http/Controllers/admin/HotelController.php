<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = \App\Models\Hotel::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('new_content.admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        $categories = \App\Models\HotelCategory::where('status', 1)->get();
        return view('new_content.admin.hotels.create', compact('categories'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:hotels',
            'hotel_category_id' => 'required|exists:hotel_categories,id',
            'status' => 'boolean'
        ]);

        \App\Models\Hotel::create($request->all());
        return redirect()->route('hotels.index')->with('success', 'Hotel created successfully.');
    }

    public function edit(\App\Models\Hotel $hotel)
    {
        $categories = \App\Models\HotelCategory::where('status', 1)->get();
        return view('new_content.admin.hotels.edit', compact('hotel', 'categories'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Hotel $hotel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:hotels,slug,' . $hotel->id,
            'hotel_category_id' => 'required|exists:hotel_categories,id',
            'status' => 'boolean'
        ]);

        $hotel->update($request->all());
        return redirect()->route('hotels.index')->with('success', 'Hotel updated successfully.');
    }

    public function destroy(\App\Models\Hotel $hotel)
    {
        $hotel->delete();
        return redirect()->route('hotels.index')->with('success', 'Hotel deleted successfully.');
    }
}
