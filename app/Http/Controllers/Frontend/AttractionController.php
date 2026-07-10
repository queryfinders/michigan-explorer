<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttractionController extends Controller
{
    public function index()
    {
        $attractions = \App\Models\Attraction::with('category')->where('status', 1)->paginate(12);
        return view('web.attractions.index', compact('attractions'));
    }

    public function show($slug)
    {
        $attraction = \App\Models\Attraction::where('slug', $slug)->where('status', 1)->firstOrFail();
        return view('web.attractions.show', compact('attraction'));
    }
}
