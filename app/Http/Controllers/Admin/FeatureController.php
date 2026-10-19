<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantFeature;
use App\Traits\Sortable;

class FeatureController extends Controller
{
    use Sortable, \App\Traits\Exportable;
    public function index(Request $request)
    {
        $query = RestaurantFeature::query();
        
        // Filtering
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query = $this->applySorting($query, ['id', 'name', 'slug', 'sort_order', 'status', 'created_at'], 'sort_order', 'asc');
        
        // Export
        if ($request->has('export')) {
            return $this->exportData($query, $request->export, 'restaurant_features_export', function ($feature) {
                return [
                    'ID' => $feature->id,
                    'Name' => $feature->name,
                    'Slug' => $feature->slug,
                    'Icon Class' => $feature->icon_class,
                    'Sort Order' => $feature->sort_order,
                    'Status' => $feature->status ? 'Active' : 'Inactive',
                ];
            });
        }
        
        $features = $query->paginate(10);
        
        if ($request->ajax()) {
            return view('new_content.admin.features._table', compact('features'))->render();
        }
        return view('new_content.admin.features.index', compact('features'));
    }

    public function create()
    {
        return view('new_content.admin.features.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:restaurant_features,name',
            'slug'        => 'required|string|max:255|unique:restaurant_features,slug',
            'icon_class'  => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'boolean',
            'sort_order'  => 'integer'
        ]);

        RestaurantFeature::create($request->all());
        return redirect()->route('features.index')->with('success', 'Feature created successfully.');
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:restaurant_features,name',
            'slug'        => 'required|string|max:255|unique:restaurant_features,slug',
            'icon_class'  => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'boolean'
        ]);

        $feature = RestaurantFeature::create([
            'name'        => $request->name,
            'slug'        => $request->slug,
            'icon_class'  => $request->icon_class,
            'description' => $request->description,
            'status'      => $request->input('status', 1),
            'sort_order'  => 0
        ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'   => $feature->id,
                'name' => $feature->name
            ]
        ]);
    }

    public function edit(RestaurantFeature $feature)
    {
        return view('new_content.admin.features.edit', compact('feature'));
    }

    public function update(Request $request, RestaurantFeature $feature)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:restaurant_features,name,' . $feature->id,
            'slug'        => 'required|string|max:255|unique:restaurant_features,slug,' . $feature->id,
            'icon_class'  => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'boolean',
            'sort_order'  => 'integer'
        ]);

        $feature->update($request->all());
        return redirect()->route('features.index')->with('success', 'Feature updated successfully.');
    }

    public function destroy(RestaurantFeature $feature)
    {
        $feature->delete();
        return redirect()->route('features.index')->with('success', 'Feature deleted successfully.');
    }

    public function changeStatus($id, $status)
    {
        $feature = RestaurantFeature::findOrFail($id);
        $feature->status = $status;
        $feature->save();
        return redirect()->route('features.index')->with('success', 'Status updated successfully.');
    }
}
