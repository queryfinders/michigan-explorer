<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Sortable;

class AttractionController extends Controller
{
    use Sortable;
    public function index(Request $request)
    {
        $query = \App\Models\Attraction::with('category');
        $query = $this->applySorting($query, ['id', 'name', 'attraction_category_id', 'status', 'created_at'], 'created_at', 'desc');
        $attractions = $query->paginate(10);
        
        if ($request->ajax()) {
            return view('new_content.admin.attractions._table', compact('attractions'))->render();
        }
        
        return view('new_content.admin.attractions.index', compact('attractions'));
    }

    public function create()
    {
        $categories = \App\Models\AttractionCategory::where('status', 1)->get();
        return view('new_content.admin.attractions.create', compact('categories'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attractions',
            'attraction_category_id' => 'required|exists:attraction_categories,id',
            'status' => 'boolean',
            'video_file' => 'nullable|mimes:mp4,mov,ogg,qt|max:30000',
            'video_url' => 'nullable|url',
        ]);

        $data = $request->except('_token', '_method', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup', 'video_file', 'video_url', 'faqs', 'images');
        
        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('attractions/videos', 'public');
            $data['video'] = 'storage/' . $path;
        } elseif ($request->filled('video_url')) {
            $data['video'] = $request->input('video_url');
        }

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('attractions/images', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        $attraction = \App\Models\Attraction::create($data);
        
        // Handle FAQs
        if ($request->has('faqs') && is_array($request->faqs)) {
            $faqs = [];
            foreach ($request->faqs as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $faqs[] = new \App\Models\AttractionFaq([
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'sort_order' => $faq['sort_order'] ?? 0,
                    ]);
                }
            }
            if (count($faqs) > 0) {
                $attraction->faqs()->saveMany($faqs);
            }
        }

        // Handle Gallery Images
        if ($request->has('images') && is_array($request->images)) {
            foreach ($request->images as $index => $image) {
                if (isset($image['file']) && $image['file']->isValid()) {
                    $path = $image['file']->store('attractions/gallery', 'public');
                    $attraction->images()->create([
                        'image' => 'storage/' . $path,
                        'alt_text' => $image['alt_text'] ?? null,
                        'sort_order' => $image['sort_order'] ?? $index,
                    ]);
                }
            }
        }
        
        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description']);
        
        $schemas = [];
        $attractionSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'TouristAttraction',
            'name' => $attraction->name,
            'description' => $request->meta_description ?? $attraction->short_description ?? '',
            'url' => route('web.attractions.show', $attraction->slug),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $attraction->city,
                'addressRegion' => 'MI',
                'addressCountry' => 'US',
            ],
        ];
        if ($attraction->featured_image) {
            $attractionSchema['image'] = url($attraction->featured_image);
        }
        $attractionSchema['address'] = array_filter($attractionSchema['address']);
        if (count($attractionSchema['address']) === 1) unset($attractionSchema['address']);
        $attractionSchema = array_filter($attractionSchema);
        $schemas[] = $attractionSchema;

        if ($attraction->faqs()->count() > 0) {
            $mainEntity = [];
            foreach ($attraction->faqs as $faq) {
                $mainEntity[] = [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => html_entity_decode(strip_tags($faq->answer))
                    ]
                ];
            }
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $mainEntity
            ];
        }

        $seoData['schema_markup'] = json_encode($schemas, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $attraction->seo()->create($seoData);

        return redirect()->route('attractions.index')->with('success', 'Attraction created successfully.');
    }

    public function edit(\App\Models\Attraction $attraction)
    {
        $attraction->load('seo');
        $categories = \App\Models\AttractionCategory::where('status', 1)->get();
        return view('new_content.admin.attractions.edit', compact('attraction', 'categories'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Attraction $attraction)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attractions,slug,' . $attraction->id,
            'attraction_category_id' => 'required|exists:attraction_categories,id',
            'status' => 'boolean',
            'video_file' => 'nullable|mimes:mp4,mov,ogg,qt|max:30000',
            'video_url' => 'nullable|url',
            'delete_video' => 'nullable|boolean',
        ]);

        $data = $request->except('_token', '_method', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup', 'video_file', 'video_url', 'delete_video', 'faqs', 'images');
        
        // Handle video deletion
        if ($request->input('delete_video') == '1') {
            if ($attraction->video && !str_starts_with($attraction->video, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $attraction->video));
            }
            $data['video'] = null;
        }

        // Handle video creation/updates
        if ($request->hasFile('video_file')) {
            if ($attraction->video && !str_starts_with($attraction->video, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $attraction->video));
            }
            $path = $request->file('video_file')->store('attractions/videos', 'public');
            $data['video'] = 'storage/' . $path;
        } elseif ($request->filled('video_url')) {
            if ($request->input('delete_video') != '1') {
                $data['video'] = $request->input('video_url');
            }
        }

        // Handle hero image upload if exists (assuming it uses 'featured_image' input, not validated but might be there)
        if ($request->hasFile('featured_image')) {
            if ($attraction->featured_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $attraction->featured_image));
            }
            $path = $request->file('featured_image')->store('attractions/images', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        $attraction->update($data);

        // Handle FAQs
        $attraction->faqs()->delete();
        if ($request->has('faqs') && is_array($request->faqs)) {
            $faqs = [];
            foreach ($request->faqs as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $faqs[] = new \App\Models\AttractionFaq([
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'sort_order' => $faq['sort_order'] ?? 0,
                    ]);
                }
            }
            if (count($faqs) > 0) {
                $attraction->faqs()->saveMany($faqs);
            }
        }

        // Handle Gallery Images
        if ($request->has('images') && is_array($request->images)) {
            foreach ($request->images as $index => $image) {
                if (isset($image['file']) && $image['file']->isValid()) {
                    $path = $image['file']->store('attractions/gallery', 'public');
                    $attraction->images()->create([
                        'image' => 'storage/' . $path,
                        'alt_text' => $image['alt_text'] ?? null,
                        'sort_order' => $image['sort_order'] ?? $index,
                    ]);
                } else if (isset($image['id'])) {
                    // Update existing image alt/sort
                    $existing = $attraction->images()->find($image['id']);
                    if ($existing) {
                        $existing->update([
                            'alt_text' => $image['alt_text'] ?? null,
                            'sort_order' => $image['sort_order'] ?? $index,
                        ]);
                    }
                }
            }
        }

        // Handle Image Deletions
        if ($request->has('deleted_images')) {
            $deletedIds = explode(',', $request->input('deleted_images'));
            foreach ($deletedIds as $id) {
                if (empty($id)) continue;
                $img = $attraction->images()->find($id);
                if ($img) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $img->image));
                    $img->delete();
                }
            }
        }
        
        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description']);
        
        $schemas = [];
        $attractionSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'TouristAttraction',
            'name' => $attraction->name,
            'description' => $request->meta_description ?? $attraction->short_description ?? '',
            'url' => route('web.attractions.show', $attraction->slug),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $attraction->city,
                'addressRegion' => 'MI',
                'addressCountry' => 'US',
            ],
        ];
        if ($attraction->featured_image) {
            $attractionSchema['image'] = url($attraction->featured_image);
        }
        $attractionSchema['address'] = array_filter($attractionSchema['address']);
        if (count($attractionSchema['address']) === 1) unset($attractionSchema['address']);
        $attractionSchema = array_filter($attractionSchema);
        $schemas[] = $attractionSchema;

        if ($attraction->faqs()->count() > 0) {
            $mainEntity = [];
            foreach ($attraction->faqs as $faq) {
                $mainEntity[] = [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => html_entity_decode(strip_tags($faq->answer))
                    ]
                ];
            }
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $mainEntity
            ];
        }

        $seoData['schema_markup'] = json_encode($schemas, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($attraction->seo) {
            $attraction->seo->update($seoData);
        } else {
            $attraction->seo()->create($seoData);
        }

        return redirect()->route('attractions.index')->with('success', 'Attraction updated successfully.');
    }

    public function destroy(\App\Models\Attraction $attraction)
    {
        $attraction->delete();
        return redirect()->route('attractions.index')->with('success', 'Attraction deleted successfully.');
    }

    public function changeStatus($id, $status)
    {
        $attraction = \App\Models\Attraction::findOrFail($id);
        $attraction->status = $status;
        $attraction->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}
