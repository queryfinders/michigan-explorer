<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = \App\Models\Blog::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('new_content.admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = \App\Models\BlogCategory::where('status', 1)->get();
        return view('new_content.admin.blogs.create', compact('categories'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|in:published,draft,scheduled'
        ]);

        \App\Models\Blog::create($request->except('_token', '_method'));
        return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
    }

    public function edit(\App\Models\Blog $blog)
    {
        $categories = \App\Models\BlogCategory::where('status', 1)->get();
        return view('new_content.admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $blog->id,
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|in:published,draft,scheduled'
        ]);

        $blog->update($request->except('_token', '_method'));
        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(\App\Models\Blog $blog)
    {
        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully.');
    }
}
