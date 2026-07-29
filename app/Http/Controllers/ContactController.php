<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;
use App\Mail\AdminContactNotification;
use App\Mail\VisitorContactAutoReply;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Store a newly created contact inquiry.
     */
    public function store(ContactRequest $request)
    {
        $email = strtolower(trim($request->input('email')));

        // Create contact message record
        $contactMessage = ContactMessage::create([
            'full_name'  => $request->input('full_name'),
            'email'      => $email,
            'phone'      => $request->input('phone'),
            'subject'    => $request->input('subject') ?: 'General Inquiry',
            'message'    => $request->input('message'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status'     => 'new'
        ]);

        // Dispatch queued email notification to configured Admin email
        $adminEmail = env('CONTACT_NOTIFICATION_EMAIL', 'support@michiganexplorer.com');
        Mail::to($adminEmail)->queue(new AdminContactNotification($contactMessage));

        // Dispatch queued auto-reply confirmation to visitor
        Mail::to($contactMessage->email)->queue(new VisitorContactAutoReply($contactMessage));

        return response()->json([
            'success' => true,
            'message' => 'Your message has been received successfully.'
        ]);
    }
}
