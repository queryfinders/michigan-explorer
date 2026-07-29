<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $total_messages_count = ContactMessage::count();
        $latest_contact_messages = ContactMessage::orderByDesc('created_at')->limit(5)->get();

        return view('new_content.dashboard.dashboards', compact(
            'total_messages_count',
            'latest_contact_messages'
        ));
    }
}
