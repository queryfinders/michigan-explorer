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
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return redirect()->route('settings.index')->with('success', 'Settings saved successfully.');
    }
}
