<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $page = \App\Models\Page::with('seo')->where('slug', 'contact')->first();
        return view('web.contact.index', compact('page'));
    }

    public function submit(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string'
        ]);

        \App\Models\ContactMessage::create($request->all());

        return redirect()->back()->with('success', 'Your message has been sent successfully. We will get back to you soon!');
    }
}
