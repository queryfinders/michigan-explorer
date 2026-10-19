<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::orderBy('group')->get();
        return view('new_content.admin.settings.index', compact('settings'));
    }

    // Since settings usually aren't created/deleted but updated in bulk:
    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:50',
            'contact_map_url' => 'nullable|string',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
        ], [
            'social_facebook.url' => 'Please enter a valid Facebook URL.',
            'social_twitter.url' => 'Please enter a valid X (Twitter) URL.',
            'social_instagram.url' => 'Please enter a valid Instagram URL.',
            'social_youtube.url' => 'Please enter a valid YouTube URL.',
        ]);

        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Settings saved successfully.']);
        }
        return redirect()->route('settings.index')->with('success', 'Settings saved successfully.');
    }
}
