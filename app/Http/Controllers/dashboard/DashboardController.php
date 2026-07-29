<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\AdminUser;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\Attraction;
use App\Models\Event;
use App\Models\Blog;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $counts = [
            'users' => AdminUser::count(),
            'hotels' => Hotel::count(),
            'restaurants' => Restaurant::count(),
            'attractions' => Attraction::count(),
            'events' => Event::count(),
            'blogs' => Blog::count(),
            'messages' => ContactMessage::count(),
        ];
        
        $latest_contact_messages = ContactMessage::orderByDesc('created_at')->limit(5)->get();

        return view('new_content.dashboard.dashboards', compact(
            'counts',
            'latest_contact_messages'
        ));
    }
}
