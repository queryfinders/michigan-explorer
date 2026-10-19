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
use App\Traits\Sortable;
use App\Traits\Exportable;

class BlogController extends Controller
{
    use Sortable, Exportable;
    public function index(Request $request)
    {
        $query = Blog::with(['category', 'author']);
        
        // Filtering
        if ($request->filled('title')) {
            $query->where('title', 'like', "%{$request->title}%");
        }
        if ($request->filled('category')) {
            $query->where('blog_category_id', $request->category);
        }
        if ($request->filled('author')) {
            $query->where('author_id', $request->author);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('published_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('published_at', '<=', $request->date_to);
        }

        $query = $this->applySorting($query, ['id', 'title', 'blog_category_id', 'status', 'published_at', 'created_at'], 'created_at', 'desc');
        
        // Export
        if ($request->has('export')) {
            return $this->exportData($query, $request->export, 'blogs_export', function ($blog) {
                return [
                    'ID' => $blog->id,
                    'Title' => $blog->title,
                    'Category' => $blog->category ? $blog->category->name : '',
                    'Author' => $blog->author ? $blog->author->name : 'Michigan Explorer',
                    'Status' => ucfirst($blog->status),
                    'Published Date' => $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('Y-m-d H:i:s') : '',
                ];
            });
        }
        
        $blogs = $query->paginate(10);
        
        if ($request->ajax()) {
            return view('new_content.admin.blogs._table', compact('blogs'))->render();
        }
        
        $categories = BlogCategory::where('status', 1)->get();
        $authors = Author::all();
        return view('new_content.admin.blogs.index', compact('blogs', 'categories', 'authors'));
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
        $data['published_at'] = $request->input('published_at') ?: now();

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('blogs', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        $blog = Blog::create($data);

        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup']);
        if (empty($seoData['schema_markup'])) {
            $blogUrl = route('web.blogs.show', $blog->slug);
            $heroImage = $blog->featured_image 
                ? (Str::startsWith($blog->featured_image, ['http://', 'https://']) ? $blog->featured_image : asset($blog->featured_image))
                : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1600';

            $schema = [
                "@context" => "https://schema.org",
                "@type" => "BlogPosting",
                "@id" => $blogUrl . "#blogposting",
                "mainEntityOfPage" => [
                    "@type" => "WebPage",
                    "@id" => $blogUrl
                ],
                "headline" => $blog->title,
                "alternativeHeadline" => $request->input('meta_title') ?: $blog->title,
                "description" => $request->input('meta_description') ?: Str::limit(strip_tags($blog->content), 160),
                "image" => [
                    "@type" => "ImageObject",
                    "url" => $heroImage,
                    "width" => 1200,
                    "height" => 630
                ],
                "author" => [
                    "@type" => "Person",
                    "name" => $blog->author ? $blog->author->name : 'Michigan Explorer',
                    "url" => $blog->author && $blog->author->facebook ? (Str::startsWith($blog->author->facebook, ['http://', 'https://']) ? $blog->author->facebook : 'https://' . $blog->author->facebook) : route('web.home')
                ],
                "publisher" => [
                    "@type" => "Organization",
                    "name" => "Michigan Explorer",
                    "url" => route('web.home'),
                    "logo" => [
                        "@type" => "ImageObject",
                        "url" => asset('images/logo.png'),
                        "width" => 512,
                        "height" => 512
                    ]
                ],
                "datePublished" => \Carbon\Carbon::parse($blog->published_at ?? $blog->created_at)->toIso8601String(),
                "dateModified" => \Carbon\Carbon::parse($blog->updated_at ?? $blog->created_at)->toIso8601String(),
                "url" => $blogUrl,
                "articleSection" => $blog->category ? $blog->category->name : 'Travel',
                "keywords" => $blog->tags ? $blog->tags->pluck('name')->toArray() : [],
                "wordCount" => str_word_count(strip_tags($blog->content)),
                "inLanguage" => "en",
                "isAccessibleForFree" => true,
                "genre" => "Blog",
                "articleBody" => strip_tags($blog->content)
            ];
            $seoData['schema_markup'] = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        $blog->seo()->create($seoData);

        if ($request->has('tags')) {
            foreach ($request->tags as $tagVal) {
                if (is_numeric($tagVal)) {
                    $tag = BlogTag::find($tagVal);
                    if ($tag) {
                        BlogTagMap::create(['blog_id' => $blog->id, 'blog_tag_id' => $tag->id]);
                        continue;
                    }
                }
                $tag = BlogTag::firstOrCreate(
                    ['slug' => Str::slug($tagVal)],
                    ['name' => $tagVal]
                );
                BlogTagMap::create(['blog_id' => $blog->id, 'blog_tag_id' => $tag->id]);
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

        if ($data['status'] === 'published') {
            $data['published_at'] = $request->input('published_at') ?: ($blog->published_at ?: now());
        } elseif ($data['status'] === 'scheduled') {
            $data['published_at'] = $request->input('published_at') ?: now();
        } else {
            $data['published_at'] = null;
        }

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('blogs', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        $blog->update($data);

        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup']);
        if (empty($seoData['schema_markup'])) {
            $blogUrl = route('web.blogs.show', $blog->slug);
            $heroImage = $blog->featured_image 
                ? (Str::startsWith($blog->featured_image, ['http://', 'https://']) ? $blog->featured_image : asset($blog->featured_image))
                : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1600';

            $schema = [
                "@context" => "https://schema.org",
                "@type" => "BlogPosting",
                "@id" => $blogUrl . "#blogposting",
                "mainEntityOfPage" => [
                    "@type" => "WebPage",
                    "@id" => $blogUrl
                ],
                "headline" => $blog->title,
                "alternativeHeadline" => $request->input('meta_title') ?: $blog->title,
                "description" => $request->input('meta_description') ?: Str::limit(strip_tags($blog->content), 160),
                "image" => [
                    "@type" => "ImageObject",
                    "url" => $heroImage,
                    "width" => 1200,
                    "height" => 630
                ],
                "author" => [
                    "@type" => "Person",
                    "name" => $blog->author ? $blog->author->name : 'Michigan Explorer',
                    "url" => $blog->author && $blog->author->facebook ? (Str::startsWith($blog->author->facebook, ['http://', 'https://']) ? $blog->author->facebook : 'https://' . $blog->author->facebook) : route('web.home')
                ],
                "publisher" => [
                    "@type" => "Organization",
                    "name" => "Michigan Explorer",
                    "url" => route('web.home'),
                    "logo" => [
                        "@type" => "ImageObject",
                        "url" => asset('images/logo.png'),
                        "width" => 512,
                        "height" => 512
                    ]
                ],
                "datePublished" => \Carbon\Carbon::parse($blog->published_at ?? $blog->created_at)->toIso8601String(),
                "dateModified" => \Carbon\Carbon::parse($blog->updated_at ?? $blog->created_at)->toIso8601String(),
                "url" => $blogUrl,
                "articleSection" => $blog->category ? $blog->category->name : 'Travel',
                "keywords" => $blog->tags ? $blog->tags->pluck('name')->toArray() : [],
                "wordCount" => str_word_count(strip_tags($blog->content)),
                "inLanguage" => "en",
                "isAccessibleForFree" => true,
                "genre" => "Blog",
                "articleBody" => strip_tags($blog->content)
            ];
            $seoData['schema_markup'] = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        if ($blog->seo) {
            $blog->seo->update($seoData);
        } else {
            $blog->seo()->create($seoData);
        }

        // Sync tags
        BlogTagMap::where('blog_id', $blog->id)->delete();
        if ($request->has('tags')) {
            foreach ($request->tags as $tagVal) {
                if (is_numeric($tagVal)) {
                    $tag = BlogTag::find($tagVal);
                    if ($tag) {
                        BlogTagMap::create(['blog_id' => $blog->id, 'blog_tag_id' => $tag->id]);
                        continue;
                    }
                }
                $tag = BlogTag::firstOrCreate(
                    ['slug' => Str::slug($tagVal)],
                    ['name' => $tagVal]
                );
                BlogTagMap::create(['blog_id' => $blog->id, 'blog_tag_id' => $tag->id]);
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
        $authorData['email'] = \Illuminate\Support\Str::slug($authorName) . '_' . time() . '@example.com';
        $author = Author::create($authorData);
        return $author->id;
    }

    public function changeStatus($id, $status)
    {
        $blog = \App\Models\Blog::findOrFail($id);
        $blog->status = $status == 1 ? 'published' : 'draft';
        
        if ($blog->status === 'published' && !$blog->published_at) {
            $blog->published_at = now();
        }
        
        $blog->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.', 'status' => $blog->status]);
    }
}
