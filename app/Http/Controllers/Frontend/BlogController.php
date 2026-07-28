<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;

class BlogController extends Controller
{
    public function index(Request $request, $param1 = null, $param2 = null)
    {
        $categorySlug = null;
        $sort = null;

        if ($request->routeIs('web.blogs.sort')) {
            $sort = $param1;
        } elseif ($request->routeIs('web.blogs.category.sort')) {
            $categorySlug = $param1;
            $sort = $param2;
        } else {
            $categorySlug = $param1;
        }

        $query = Blog::with(['category', 'author'])
            ->where('status', 'published')
            ->where(function($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });

        // Search
        if ($request->has('q') && $request->q != '') {
            $query->where('title', 'like', '%' . $request->q . '%')
                  ->orWhere('content', 'like', '%' . $request->q . '%');
        }

        // Filtering by category
        $activeCategory = null;
        if ($categorySlug) {
            $category = BlogCategory::where('slug', $categorySlug)->firstOrFail();
            $query->where('blog_category_id', $category->id);
            $activeCategory = $category;
        } elseif ($request->has('category') && $request->category != '') {
            $catSlug = $request->get('category');
            $category = BlogCategory::where('slug', $catSlug)->first();
            if ($category) {
                $query->where('blog_category_id', $category->id);
                $activeCategory = $category;
            }
        }

        // Sorting
        $activeSort = $sort ?: $request->get('sort', 'latest');
        if ($activeSort == 'popular') {
            $query->orderBy('views', 'desc');
        } elseif ($activeSort == 'trending') {
            $query->orderBy('views', 'desc')->orderBy('created_at', 'desc');
        } elseif ($activeSort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            // Default latest (Show newly added blogs first)
            $query->orderBy('created_at', 'desc');
        }

        $blogs = $query->paginate(12);
        
        $featuredBlog = Blog::with(['category', 'author'])
            ->where('status', 'published')
            ->where('is_featured', 1)
            ->where(function($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderBy('published_at', 'desc')
            ->first();

        // Sidebar Widgets
        $categories = BlogCategory::where('status', 1)->withCount('blogs')->get();
        $tags = BlogTag::all();
        
        $recentBlogs = Blog::where('status', 'published')
            ->where(function($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $mostViewedBlogs = Blog::where('status', 'published')
            ->where(function($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $page = \App\Models\Page::with('seo')->where('slug', 'blogs')->first();

        $totalBlogs = Blog::where('status', 'published')
            ->where(function($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })->count();

        $totalViews = Blog::where('status', 'published')
            ->where(function($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })->sum('views');

        return view('web.blogs.index', compact('blogs', 'featuredBlog', 'categories', 'tags', 'recentBlogs', 'mostViewedBlogs', 'page', 'totalBlogs', 'totalViews', 'activeCategory', 'activeSort'));
    }

    public function show($slug)
    {
        $blog = Blog::with(['category', 'author', 'tags', 'seo', 'faqs'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
            
        // Increment view count
        $blog->increment('views');
            
        // Get related articles
        $relatedBlogs = Blog::where('blog_category_id', $blog->blog_category_id)
            ->where('id', '!=', $blog->id)
            ->where('status', 'published')
            ->where(function($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $categories = BlogCategory::where('status', 1)->withCount('blogs')->get();
        $tags = BlogTag::all();
        
        // Ensure recentBlogs and mostViewedBlogs are passed to show view as well if sidebar is used there
        $recentBlogs = Blog::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $mostViewedBlogs = Blog::where('status', 'published')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        return view('web.blogs.show', compact('blog', 'relatedBlogs', 'categories', 'tags', 'recentBlogs', 'mostViewedBlogs'));
    }
}
