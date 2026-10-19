<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotelPolicy;
use App\Traits\Sortable;

class HotelPolicyController extends Controller
{
    use Sortable, \App\Traits\Exportable;
    public function index(Request $request)
    {
        $query = HotelPolicy::query();
        
        // Filtering
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $query = $this->applySorting($query, ['id', 'name', 'input_type', 'sort_order', 'is_active'], 'sort_order', 'asc');
        
        // Export
        if ($request->has('export')) {
            return $this->exportData($query, $request->export, 'hotel_policies_export', function ($policy) {
                return [
                    'ID' => $policy->id,
                    'Name' => $policy->name,
                    'Input Type' => $policy->input_type,
                    'Sort Order' => $policy->sort_order,
                    'Status' => $policy->is_active ? 'Active' : 'Inactive',
                ];
            });
        }
        
        $policies = $query->paginate(20);
        
        if ($request->ajax()) {
            return view('new_content.admin.hotel_policies._table', compact('policies'))->render();
        }
        return view('new_content.admin.hotel_policies.index', compact('policies'));
    }

    public function create()
    {
        return view('new_content.admin.hotel_policies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'input_type' => 'required|in:text,textarea',
            'sort_order' => 'required|integer',
            'is_active'  => 'boolean'
        ]);

        $data = $request->except('_token');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        HotelPolicy::create($data);

        return redirect()->route('hotel-policies.index')->with('success', 'Hotel Policy created successfully.');
    }

    public function edit(string $id)
    {
        $policy = HotelPolicy::findOrFail($id);
        return view('new_content.admin.hotel_policies.edit', compact('policy'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'input_type' => 'required|in:text,textarea',
            'sort_order' => 'required|integer',
            'is_active'  => 'boolean'
        ]);

        $policy = HotelPolicy::findOrFail($id);
        
        $data = $request->except('_token', '_method');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $policy->update($data);

        return redirect()->route('hotel-policies.index')->with('success', 'Hotel Policy updated successfully.');
    }

    public function destroy(string $id)
    {
        $policy = HotelPolicy::findOrFail($id);
        $policy->delete();

        return redirect()->route('hotel-policies.index')->with('success', 'Hotel Policy deleted successfully.');
    }

    public function changeStatus($id, $status)
    {
        $policy = HotelPolicy::findOrFail($id);
        $policy->is_active = $status;
        $policy->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}
