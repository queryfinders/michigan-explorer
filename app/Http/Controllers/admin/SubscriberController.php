<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\Sortable;

class SubscriberController extends Controller
{
    use Sortable;
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

        $query = $this->applySorting($query, ['id', 'email', 'source', 'is_verified', 'created_at', 'verified_at'], 'created_at', 'desc');
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

    /**
     * Export subscribers as CSV or Excel (XLSX).
     */
    public function export($format)
    {
        $subscribers = Subscriber::orderByDesc('created_at')->get();

        if ($format === 'csv') {
            return $this->exportCsv($subscribers);
        }

        if ($format === 'excel') {
            return $this->exportExcel($subscribers);
        }

        abort(404);
    }

    protected function exportCsv($subscribers)
    {
        $fileName = 'subscribers_' . date('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($subscribers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Email', 'Source', 'Verified', 'Verified At', 'Active', 'Unsubscribed At', 'IP Address', 'User Agent', 'Created At']);

            foreach ($subscribers as $sub) {
                fputcsv($file, [
                    $sub->id,
                    $sub->email,
                    $sub->source,
                    $sub->is_verified ? 'Yes' : 'No',
                    $sub->verified_at ? $sub->verified_at->toDateTimeString() : '',
                    $sub->is_active ? 'Yes' : 'No',
                    $sub->unsubscribed_at ? $sub->unsubscribed_at->toDateTimeString() : '',
                    $sub->ip_address,
                    $sub->user_agent,
                    $sub->created_at->toDateTimeString()
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function exportExcel($subscribers)
    {
        // For standard server-side compatibility without installing massive third-party packages (like PhpSpreadsheet),
        // exporting as a clean Tab-Separated Values (TSV) file with .xls extension allows Microsoft Excel to open it natively!
        $fileName = 'subscribers_' . date('Y-m-d') . '.xls';
        $headers = [
            "Content-type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($subscribers) {
            $file = fopen('php://output', 'w');
            
            // Excel expects tab separators for clean display
            fwrite($file, "ID\tEmail\tSource\tVerified\tVerified At\tActive\tUnsubscribed At\tIP Address\tCreated At\n");

            foreach ($subscribers as $sub) {
                fwrite($file, implode("\t", [
                    $sub->id,
                    $sub->email,
                    $sub->source,
                    $sub->is_verified ? 'Yes' : 'No',
                    $sub->verified_at ? $sub->verified_at->toDateTimeString() : '',
                    $sub->is_active ? 'Yes' : 'No',
                    $sub->unsubscribed_at ? $sub->unsubscribed_at->toDateTimeString() : '',
                    $sub->ip_address,
                    $sub->created_at->toDateTimeString()
                ]) . "\n");
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
