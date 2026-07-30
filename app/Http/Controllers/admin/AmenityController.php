<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenity;
use Illuminate\Support\Str;
use App\Traits\Sortable;

class AmenityController extends Controller
{
    use Sortable;
    public function index(Request $request)
    {
        $query = Amenity::query();
        $query = $this->applySorting($query, ['id', 'name', 'slug', 'status'], 'name', 'asc');
        $amenities = $query->paginate(10);
        if ($request->ajax()) {
            return view('new_content.admin.amenities._table', compact('amenities'))->render();
        }
        return view('new_content.admin.amenities.index', compact('amenities'));
    }

    public function create()
    {
        return view('new_content.admin.amenities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:amenities',
            'icon'   => 'nullable|string|max:255',
            'status' => 'nullable|boolean'
        ]);

        // Auto-generate slug if not provided
        $slug = $request->input('slug') ?: \Illuminate\Support\Str::slug($request->name);
        // Ensure slug uniqueness
        $originalSlug = $slug;
        $count = 1;
        while (Amenity::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $amenity = Amenity::create([
            'name'   => $request->name,
            'slug'   => $slug,
            'icon'   => $request->input('icon', 'fa-star'),
            'status' => $request->input('status', 1),
        ]);

        // Return JSON for AJAX calls (from hotel form modal)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'amenity' => ['id' => $amenity->id, 'name' => $amenity->name, 'icon' => $amenity->icon]
            ]);
        }

        return redirect()->route('amenities.index')->with('success', 'Amenity created successfully.');
    }

    public function edit(Amenity $amenity)
    {
        return view('new_content.admin.amenities.edit', compact('amenity'));
    }

    public function update(Request $request, Amenity $amenity)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:amenities,name,' . $amenity->id,
            'slug' => 'required|string|max:255|unique:amenities,slug,' . $amenity->id,
            'icon' => 'required|string|max:255',
            'status' => 'boolean'
        ]);

        $amenity->update($request->all());

        return redirect()->route('amenities.index')->with('success', 'Amenity updated successfully.');
    }

    public function destroy(Amenity $amenity)
    {
        $amenity->delete();
        return redirect()->route('amenities.index')->with('success', 'Amenity deleted successfully.');
    }

    public function changeStatus($id, $status)
    {
        $amenity = Amenity::findOrFail($id);
        $amenity->status = $status;
        $amenity->save();

        return response()->json(['success' => 'Status changed successfully.']);
    }
}
