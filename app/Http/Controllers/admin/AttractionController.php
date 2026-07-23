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
            'status' => 'boolean',
            'video_file' => 'nullable|mimes:mp4,mov,ogg,qt|max:30000',
            'video_url' => 'nullable|url',
        ]);

        $data = $request->except('_token', '_method', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup', 'video_file', 'video_url');
        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('attractions/videos', 'public');
            $data['video'] = 'storage/' . $path;
        } elseif ($request->filled('video_url')) {
            $data['video'] = $request->input('video_url');
        }

        $attraction = \App\Models\Attraction::create($data);
        
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
            'status' => 'boolean',
            'video_file' => 'nullable|mimes:mp4,mov,ogg,qt|max:30000',
            'video_url' => 'nullable|url',
            'delete_video' => 'nullable|boolean',
        ]);

        $data = $request->except('_token', '_method', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup', 'video_file', 'video_url', 'delete_video');
        // Handle video deletion
        if ($request->input('delete_video') == '1') {
            if ($attraction->video && !str_starts_with($attraction->video, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $attraction->video));
            }
            $data['video'] = null;
        }

        // Handle video creation/updates
        if ($request->hasFile('video_file')) {
            if ($attraction->video && !str_starts_with($attraction->video, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $attraction->video));
            }
            $path = $request->file('video_file')->store('attractions/videos', 'public');
            $data['video'] = 'storage/' . $path;
        } elseif ($request->filled('video_url')) {
            if ($request->input('delete_video') != '1') {
                $data['video'] = $request->input('video_url');
            }
        }

        $attraction->update($data);
        
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
