<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingFeature;
use App\Traits\Sortable;

class BookingFeatureController extends Controller
{
    use Sortable, \App\Traits\Exportable;
    public function index(Request $request)
    {
        $query = BookingFeature::query();
        
        // Filtering
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $query = $this->applySorting($query, ['id', 'name', 'icon', 'sort_order', 'is_active'], 'sort_order', 'asc');
        
        // Export
        if ($request->has('export')) {
            return $this->exportData($query, $request->export, 'hotel_booking_features_export', function ($feature) {
                return [
                    'ID' => $feature->id,
                    'Name' => $feature->name,
                    'Icon' => $feature->icon,
                    'Sort Order' => $feature->sort_order,
                    'Status' => $feature->is_active ? 'Active' : 'Inactive',
                ];
            });
        }
        
        $features = $query->paginate(20);
        
        if ($request->ajax()) {
            return view('new_content.admin.booking_features._table', compact('features'))->render();
        }
        return view('new_content.admin.booking_features.index', compact('features'));
    }

    public function create()
    {
        return view('new_content.admin.booking_features.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'icon'       => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'is_active'  => 'boolean'
        ]);

        $data = $request->except('_token');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        BookingFeature::create($data);

        return redirect()->route('booking-features.index')->with('success', 'Booking Feature created successfully.');
    }

    public function edit(string $id)
    {
        $feature = BookingFeature::findOrFail($id);
        return view('new_content.admin.booking_features.edit', compact('feature'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'icon'       => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'is_active'  => 'boolean'
        ]);

        $feature = BookingFeature::findOrFail($id);
        
        $data = $request->except('_token', '_method');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $feature->update($data);

        return redirect()->route('booking-features.index')->with('success', 'Booking Feature updated successfully.');
    }

    public function destroy(string $id)
    {
        $feature = BookingFeature::findOrFail($id);
        $feature->delete();

        return redirect()->route('booking-features.index')->with('success', 'Booking Feature deleted successfully.');
    }

    public function changeStatus($id, $status)
    {
        $feature = BookingFeature::findOrFail($id);
        $feature->is_active = $status;
        $feature->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}
