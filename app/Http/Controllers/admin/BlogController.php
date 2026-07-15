<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Author;
use App\Models\BlogTag;
use App\Models\BlogTagMap;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with(['category', 'author'])->orderBy('created_at', 'desc')->paginate(10);
        return view('new_content.admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = BlogCategory::where('status', 1)->get();
        $authors = Author::all();
        $tags = BlogTag::all();
        return view('new_content.admin.blogs.create', compact('categories', 'authors', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'author_id' => 'nullable|exists:authors,id',
            'status' => 'required|in:published,draft,scheduled',
            'featured_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->except('_token', '_method', 'featured_image_file', 'tags', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup');

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('blogs', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        $blog = Blog::create($data);

        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup']);
        $blog->seo()->create($seoData);

        if ($request->has('tags')) {
            foreach ($request->tags as $tagId) {
                BlogTagMap::create(['blog_id' => $blog->id, 'blog_tag_id' => $tagId]);
            }
        }

        return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
    }

    public function edit(Blog $blog)
    {
        $blog->load('seo');
        $categories = BlogCategory::where('status', 1)->get();
        $authors = Author::all();
        $tags = BlogTag::all();
        $selectedTags = BlogTagMap::where('blog_id', $blog->id)->pluck('blog_tag_id')->toArray();
        return view('new_content.admin.blogs.edit', compact('blog', 'categories', 'authors', 'tags', 'selectedTags'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $blog->id,
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'author_id' => 'nullable|exists:authors,id',
            'status' => 'required|in:published,draft,scheduled',
            'featured_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->except('_token', '_method', 'featured_image_file', 'tags', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup');

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('blogs', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        $blog->update($data);

        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup']);
        if ($blog->seo) {
            $blog->seo->update($seoData);
        } else {
            $blog->seo()->create($seoData);
        }

        // Sync tags
        BlogTagMap::where('blog_id', $blog->id)->delete();
        if ($request->has('tags')) {
            foreach ($request->tags as $tagId) {
                BlogTagMap::create(['blog_id' => $blog->id, 'blog_tag_id' => $tagId]);
            }
        }

        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        BlogTagMap::where('blog_id', $blog->id)->delete();
        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully.');
    }
}
