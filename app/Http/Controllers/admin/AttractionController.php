<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttractionController extends Controller
{
    public function index()
    {
        $attractions = \App\Models\Attraction::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('new_content.admin.attractions.index', compact('attractions'));
    }

    public function create()
    {
        $categories = \App\Models\AttractionCategory::where('status', 1)->get();
        return view('new_content.admin.attractions.create', compact('categories'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attractions',
            'attraction_category_id' => 'required|exists:attraction_categories,id',
            'status' => 'boolean'
        ]);

        $attraction = \App\Models\Attraction::create($request->except('_token', '_method', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup'));
        
        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup']);
        $attraction->seo()->create($seoData);

        return redirect()->route('attractions.index')->with('success', 'Attraction created successfully.');
    }

    public function edit(\App\Models\Attraction $attraction)
    {
        $attraction->load('seo');
        $categories = \App\Models\AttractionCategory::where('status', 1)->get();
        return view('new_content.admin.attractions.edit', compact('attraction', 'categories'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Attraction $attraction)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attractions,slug,' . $attraction->id,
            'attraction_category_id' => 'required|exists:attraction_categories,id',
            'status' => 'boolean'
        ]);

        $attraction->update($request->except('_token', '_method', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup'));
        
        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup']);
        if ($attraction->seo) {
            $attraction->seo->update($seoData);
        } else {
            $attraction->seo()->create($seoData);
        }

        return redirect()->route('attractions.index')->with('success', 'Attraction updated successfully.');
    }

    public function destroy(\App\Models\Attraction $attraction)
    {
        $attraction->delete();
        return redirect()->route('attractions.index')->with('success', 'Attraction deleted successfully.');
    }
}
