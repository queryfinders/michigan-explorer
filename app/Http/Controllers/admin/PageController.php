<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = \App\Models\Page::orderBy('created_at', 'desc')->paginate(10);
        return view('new_content.admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('new_content.admin.pages.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages',
            'status' => 'boolean'
        ]);

        \App\Models\Page::create($request->except('_token', '_method'));
        return redirect()->route('pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(\App\Models\Page $page)
    {
        return view('new_content.admin.pages.edit', compact('page'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'status' => 'boolean'
        ]);

        $page->update($request->except('_token', '_method'));
        return redirect()->route('pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(\App\Models\Page $page)
    {
        $page->delete();
        return redirect()->route('pages.index')->with('success', 'Page deleted successfully.');
    }
}
