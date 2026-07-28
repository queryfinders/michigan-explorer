<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ContactMessageController extends Controller
{
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

        $messages = $query->orderByDesc('created_at')->paginate(15);

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
