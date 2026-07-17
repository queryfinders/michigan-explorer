<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingFeature;

class BookingFeatureController extends Controller
{
    public function index()
    {
        $features = BookingFeature::orderBy('sort_order')->paginate(20);
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
