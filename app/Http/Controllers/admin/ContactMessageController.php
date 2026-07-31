<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\Sortable;

class ContactMessageController extends Controller
{
    use Sortable, \App\Traits\Exportable;
    /**
     * Display a listing of contact messages.
     */
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        // 1. Search Query (Name, Email, Subject)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // 2. Filters (Status)
        if ($request->filled('status')) {
            $status = $request->input('status');
            if (in_array($status, ['new', 'read', 'replied', 'closed'])) {
                $query->where('status', $status);
            }
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $query = $this->applySorting($query, ['id', 'full_name', 'email', 'phone', 'subject', 'created_at'], 'created_at', 'desc');
        
        // Export using Exportable trait
        if ($request->has('export')) {
            return $this->exportData($query, $request->export, 'contact_messages_export', function ($message) {
                return [
                    'ID' => $message->id,
                    'Name' => $message->full_name,
                    'Email' => $message->email,
                    'Phone' => $message->phone,
                    'Subject' => $message->subject,
                    'Status' => ucfirst($message->status),
                    'Received At' => $message->created_at ? \Carbon\Carbon::parse($message->created_at)->format('Y-m-d H:i:s') : '',
                ];
            });
        }
        
        $messages = $query->paginate(15);

        if ($request->ajax()) {
            return view('new_content.admin.contact_messages._table', compact('messages'))->render();
        }

        return view('new_content.admin.contact_messages.index', compact('messages'));
    }

    /**
     * Show a detailed view of a contact message.
     */
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        // Auto transition status to 'read' if it is currently 'new'
        if ($message->status === 'new') {
            $message->update(['status' => 'read']);
        }

        // Simple User Agent Parsing for display
        $userAgent = $message->user_agent;
        $browser = 'Unknown';
        if (preg_match('/chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/edge|edg/i', $userAgent)) {
            $browser = 'Edge';
        }

        return view('new_content.admin.contact_messages.show', compact('message', 'browser'));
    }

    /**
     * Update the status of a message manually.
     */
    public function updateStatus(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);
        $status = $request->input('status');

        if (in_array($status, ['new', 'read', 'replied', 'closed'])) {
            $updateData = ['status' => $status];
            if ($status === 'replied') {
                $updateData['replied_at'] = Carbon::now();
            }
            $message->update($updateData);
            return redirect()->back()->with('success', 'Message status updated successfully.');
        }

        return redirect()->back()->with('error', 'Invalid status selected.');
    }

    /**
     * Delete a contact message.
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('contact-messages.index')->with('success', 'Message deleted successfully.');
    }
}
