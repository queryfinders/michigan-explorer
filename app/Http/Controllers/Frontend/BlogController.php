<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = \App\Models\Blog::with('category')->where('status', 'published')->orderBy('published_at', 'desc')->paginate(12);
        return view('web.blogs.index', compact('blogs'));
    }

    public function show($slug)
    {
        $blog = \App\Models\Blog::where('slug', $slug)->where('status', 'published')->firstOrFail();
        return view('web.blogs.show', compact('blog'));
    }
}
