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
            'content' => 'required|string',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'author_id' => 'nullable',
            'status' => 'nullable|in:published,draft,scheduled',
            'published_at' => 'nullable|date',
            'featured_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_image_alt' => 'nullable|string|max:255',
            'author_name' => 'nullable|string|max:255',
            'author_designation' => 'nullable|string|max:255',
            'author_avatar_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'author_avatar_alt' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|string|max:255',
            'twitter_url' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255'
        ]);

        $authorId = $this->handleAuthorSave($request);

        $data = $request->except([
            '_token', '_method', 'featured_image_file', 'tags', 
            'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup',
            'author_name', 'author_designation', 'author_avatar_file', 'author_avatar_alt', 
            'facebook_url', 'twitter_url', 'linkedin_url', 'instagram_url', 'faqs'
        ]);

        $data['author_id'] = $authorId;
        $data['status'] = $request->input('status', 'published');

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

        // Handle FAQs
        if ($request->has('faqs')) {
            foreach ($request->input('faqs') as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $blog->faqs()->create([
                        'question' => $faq['question'],
                        'answer'   => $faq['answer'],
                    ]);
                }
            }
        }

        return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
    }

    public function edit(Blog $blog)
    {
        $blog->load(['seo', 'faqs']);
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
            'content' => 'required|string',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'author_id' => 'nullable',
            'status' => 'nullable|in:published,draft,scheduled',
            'published_at' => 'nullable|date',
            'featured_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_image_alt' => 'nullable|string|max:255',
            'author_name' => 'nullable|string|max:255',
            'author_designation' => 'nullable|string|max:255',
            'author_avatar_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'author_avatar_alt' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|string|max:255',
            'twitter_url' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255'
        ]);

        $authorId = $this->handleAuthorSave($request);

        $data = $request->except([
            '_token', '_method', 'featured_image_file', 'tags', 
            'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup',
            'author_name', 'author_designation', 'author_avatar_file', 'author_avatar_alt', 
            'facebook_url', 'twitter_url', 'linkedin_url', 'instagram_url', 'faqs'
        ]);

        $data['author_id'] = $authorId;
        $data['status'] = $request->input('status', 'published');

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

        // Handle FAQs
        $blog->faqs()->delete();
        if ($request->has('faqs')) {
            foreach ($request->input('faqs') as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $blog->faqs()->create([
                        'question' => $faq['question'],
                        'answer'   => $faq['answer'],
                    ]);
                }
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

    private function handleAuthorSave(Request $request)
    {
        $authorId = $request->input('author_id');
        $authorName = $request->input('author_name');

        if (empty($authorName)) {
            return $authorId === 'new' ? null : $authorId;
        }

        $authorData = [
            'name' => $authorName,
            'designation' => $request->input('author_designation'),
            'avatar_alt' => $request->input('author_avatar_alt'),
            'facebook' => $request->input('facebook_url'),
            'twitter' => $request->input('twitter_url'),
            'linkedin' => $request->input('linkedin_url'),
            'instagram' => $request->input('instagram_url'),
        ];

        if ($request->hasFile('author_avatar_file')) {
            $path = $request->file('author_avatar_file')->store('authors', 'public');
            $authorData['avatar'] = 'storage/' . $path;
        }

        if ($authorId && $authorId !== 'new') {
            $author = Author::find($authorId);
            if ($author) {
                $author->update($authorData);
                return $author->id;
            }
        }

        // Create new author
        $author = Author::create($authorData);
        return $author->id;
    }
}
