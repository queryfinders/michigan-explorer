<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\Sortable;

class PageController extends Controller
{
    use Sortable, \App\Traits\Exportable;
    public function index(Request $request)
    {
        $query = \App\Models\Page::query();
        
        // Filtering
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query = $this->applySorting($query, ['id', 'title', 'slug', 'status', 'created_at'], 'created_at', 'desc');
        
        // Export
        if ($request->has('export')) {
            return $this->exportData($query, $request->export, 'pages_export', function ($page) {
                return [
                    'ID' => $page->id,
                    'Title' => $page->title,
                    'Slug' => $page->slug,
                    'Status' => $page->status ? 'Active' : 'Inactive',
                    'Created At' => $page->created_at ? $page->created_at->format('Y-m-d H:i:s') : '',
                ];
            });
        }
        
        $pages = $query->paginate(10);
        
        if ($request->ajax()) {
            return view('new_content.admin.pages._table', compact('pages'))->render();
        }
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
            'status' => 'boolean',
            'banner_title' => 'nullable|string|max:255',
            'banner_subtitle' => 'nullable|string',
            'banner_button_text' => 'nullable|string|max:255',
            'banner_button_link' => 'nullable|string|max:255',
            'featured_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_image_alt' => 'nullable|string|max:255'
        ]);

        $data = $request->except('_token', '_method', 'featured_image_file', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup');

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('pages', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        $page = \App\Models\Page::create($data);
        
        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description']);
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $data['title'] ?? $request->title,
            'description' => trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($request->content ?: $request->meta_description), ENT_QUOTES, 'UTF-8'))),
            'url' => url('/' . ($data['slug'] ?? $request->slug)),
        ];

        $schema = array_filter($schema, function($value) {
            return !is_null($value) && $value !== '';
        });

        $seoData['schema_markup'] = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        $page->seo()->create($seoData);

        return redirect()->route('pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(\App\Models\Page $page)
    {
        $page->load('seo');
        return view('new_content.admin.pages.edit', compact('page'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'status' => 'boolean',
            'banner_title' => 'nullable|string|max:255',
            'banner_subtitle' => 'nullable|string',
            'banner_button_text' => 'nullable|string|max:255',
            'banner_button_link' => 'nullable|string|max:255',
            'featured_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_image_alt' => 'nullable|string|max:255'
        ]);

        $data = $request->except('_token', '_method', 'featured_image_file', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup');

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('pages', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        $page->update($data);
        
        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description']);
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $data['title'] ?? $request->title,
            'description' => trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($request->content ?: $request->meta_description), ENT_QUOTES, 'UTF-8'))),
            'url' => url('/' . ($data['slug'] ?? $request->slug)),
        ];

        $schema = array_filter($schema, function($value) {
            return !is_null($value) && $value !== '';
        });

        $seoData['schema_markup'] = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($page->seo) {
            $page->seo->update($seoData);
        } else {
            $page->seo()->create($seoData);
        }

        return redirect()->route('pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(\App\Models\Page $page)
    {
        $page->delete();
        return redirect()->route('pages.index')->with('success', 'Page deleted successfully.');
    }

    public function changeStatus($id, $status)
    {
        $page = \App\Models\Page::findOrFail($id);
        $page->status = $status;
        $page->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.', 'status' => $page->status]);
    }
}
