<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = \App\Models\ContactMessage::orderBy('created_at', 'desc')->paginate(15);
        return view('new_content.admin.contact_messages.index', compact('messages'));
    }

    public function show($id)
    {
        $message = \App\Models\ContactMessage::findOrFail($id);
        if (!$message->is_read) {
            $message->update(['is_read' => 1]);
        }
        return view('new_content.admin.contact_messages.show', compact('message'));
    }

    public function destroy($id)
    {
        \App\Models\ContactMessage::findOrFail($id)->delete();
        return redirect()->route('contact-messages.index')->with('success', 'Message deleted successfully.');
    }
}
