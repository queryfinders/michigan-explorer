<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\Sortable;

class SubscriberController extends Controller
{
    use Sortable, \App\Traits\Exportable;
    /**
     * Display a listing of the subscribers.
     */
    public function index(Request $request)
    {
        $query = Subscriber::query();

        // 1. Search Query
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('email', 'like', "%{$search}%");
        }

        // 2. Filters
        if ($request->filled('verified')) {
            $verified = $request->input('verified');
            if ($verified === '1') {
                $query->where('is_verified', true);
            } elseif ($verified === '0') {
                $query->where('is_verified', false);
            }
        }

        if ($request->filled('active')) {
            $active = $request->input('active');
            if ($active === '1') {
                $query->where('is_active', true);
            } elseif ($active === '0') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('source')) {
            $source = $request->input('source');
            if (in_array($source, ['explorer_club', 'footer'])) {
                $query->where('source', $source);
            }
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $query = $this->applySorting($query, ['id', 'email', 'source', 'is_verified', 'created_at', 'verified_at'], 'created_at', 'desc');
        
        // Export using Exportable trait
        if ($request->has('export')) {
            return $this->exportData($query, $request->export, 'subscribers_export', function ($sub) {
                return [
                    'ID' => $sub->id,
                    'Email' => $sub->email,
                    'Source' => $sub->source,
                    'Verified' => $sub->is_verified ? 'Yes' : 'No',
                    'Verified At' => $sub->verified_at ? \Carbon\Carbon::parse($sub->verified_at)->format('Y-m-d H:i:s') : '',
                    'Active' => $sub->is_active ? 'Yes' : 'No',
                    'Unsubscribed At' => $sub->unsubscribed_at ? \Carbon\Carbon::parse($sub->unsubscribed_at)->format('Y-m-d H:i:s') : '',
                    'Created At' => $sub->created_at ? \Carbon\Carbon::parse($sub->created_at)->format('Y-m-d H:i:s') : '',
                ];
            });
        }
        
        $subscribers = $query->paginate(15);

        if ($request->ajax()) {
            return view('new_content.admin.subscribers._table', compact('subscribers'))->render();
        }

        return view('new_content.admin.subscribers.index', compact('subscribers'));
    }

    /**
     * Toggle the active status of a subscriber.
     */
    public function changeStatus($id, $status)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->update([
            'is_active' => (bool)$status,
            'unsubscribed_at' => $status ? null : Carbon::now()
        ]);

        return redirect()->back()->with('success', 'Subscriber status updated successfully.');
    }

    /**
     * Delete a subscriber.
     */
    public function destroy($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        return redirect()->route('subscribers.index')->with('success', 'Subscriber deleted successfully.');
    }

    /**
     * Handle bulk actions (activate, deactivate, delete).
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids');

        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('error', 'No subscribers selected.');
        }

        if ($action === 'activate') {
            Subscriber::whereIn('id', $ids)->update([
                'is_active' => true,
                'unsubscribed_at' => null
            ]);
            return redirect()->back()->with('success', 'Selected subscribers activated.');
        }

        if ($action === 'deactivate') {
            Subscriber::whereIn('id', $ids)->update([
                'is_active' => false,
                'unsubscribed_at' => Carbon::now()
            ]);
            return redirect()->back()->with('success', 'Selected subscribers deactivated.');
        }

        if ($action === 'delete') {
            Subscriber::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Selected subscribers deleted.');
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }

}
