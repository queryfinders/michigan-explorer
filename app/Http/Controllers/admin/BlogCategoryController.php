<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = \App\Models\BlogCategory::orderBy('created_at', 'desc')->paginate(10);
        return view('new_content.admin.blog_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('new_content.admin.blog_categories.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories',
            'icon' => 'nullable|string|max:255',
            'status' => 'boolean'
        ]);

        \App\Models\BlogCategory::create($request->except('_token', '_method'));
        return redirect()->route('blog-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(\App\Models\BlogCategory $blogCategory)
    {
        return view('new_content.admin.blog_categories.edit', compact('blogCategory'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\BlogCategory $blogCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug,' . $blogCategory->id,
            'icon' => 'nullable|string|max:255',
            'status' => 'boolean'
        ]);

        $blogCategory->update($request->except('_token', '_method'));
        return redirect()->route('blog-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(\App\Models\BlogCategory $blogCategory)
    {
        $blogCategory->delete();
        return redirect()->route('blog-categories.index')->with('success', 'Category deleted successfully.');
    }

    public function changeStatus($id, $status)
    {
        $category = \App\Models\BlogCategory::findOrFail($id);
        $category->status = $status == 1 ? 0 : 1;
        $category->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.', 'status' => $category->status]);
    }
}
