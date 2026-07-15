<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = \App\Models\Hotel::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('new_content.admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        $categories = \App\Models\HotelCategory::where('status', 1)->get();
        $amenities = \App\Models\Amenity::where('status', 1)->orderBy('name')->get();
        return view('new_content.admin.hotels.create', compact('categories', 'amenities'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'slug'                  => 'required|string|max:255|unique:hotels',
            'hotel_category_id'     => 'required|exists:hotel_categories,id',
            'status'                => 'boolean',
            'is_featured'           => 'boolean',
            'starting_price'        => 'nullable|integer',
            'featured_image_file'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_image_alt'    => 'nullable|string|max:255',
            'email'                 => 'nullable|email',
            'website'               => 'nullable|url',
            'affiliate_url'         => 'nullable|url',
            'latitude'              => 'nullable|numeric',
            'longitude'             => 'nullable|numeric',
            'amenities'             => 'nullable|array',
            'gallery_images.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'gallery_alts.*'        => 'nullable|string|max:255',
        ]);

        $data = $request->except('_token', '_method', 'featured_image_file', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup', 'amenities', 'gallery_images', 'gallery_alts', 'faqs');
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('hotels', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        $hotel = \App\Models\Hotel::create($data);

        $hotel->amenities()->sync($request->input('amenities', []));

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('hotels/gallery', 'public');
                $hotel->images()->create([
                    'image'      => 'storage/' . $path,
                    'alt_text'   => $request->input('gallery_alts.' . $index, ''),
                    'sort_order' => $index,
                ]);
            }
        }

        // Handle FAQs
        if ($request->has('faqs')) {
            $sortOrder = 0;
            foreach ($request->input('faqs') as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $hotel->faqs()->create([
                        'question'   => $faq['question'],
                        'answer'     => $faq['answer'],
                        'sort_order' => $sortOrder++
                    ]);
                }
            }
        }

        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description']);
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Hotel',
            'name' => $data['name'] ?? $request->name,
            'description' => trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($request->short_description ?: $request->description), ENT_QUOTES, 'UTF-8'))),
            'url' => $request->website,
            'telephone' => $request->phone,
            'email' => $request->email,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $request->address,
                'addressLocality' => $request->city,
                'addressRegion' => 'MI',
                'postalCode' => $request->zip,
                'addressCountry' => 'US'
            ]
        ];
        if ($request->starting_price) {
            $schema['priceRange'] = '$' . $request->starting_price . '+';
        }
        if (isset($data['featured_image'])) {
            $schema['image'] = url($data['featured_image']);
        }
        $schema['address'] = array_filter($schema['address']);
        if (count($schema['address']) === 1) unset($schema['address']);
        $schema = array_filter($schema);
        
        $seoData['schema_markup'] = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $hotel->seo()->create($seoData);

        return redirect()->route('hotels.index')->with('success', 'Hotel created successfully.');
    }

    public function edit(\App\Models\Hotel $hotel)
    {
        $hotel->load(['seo', 'amenities', 'images']);
        $categories = \App\Models\HotelCategory::where('status', 1)->get();
        $amenities = \App\Models\Amenity::where('status', 1)->orderBy('name')->get();
        return view('new_content.admin.hotels.edit', compact('hotel', 'categories', 'amenities'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Hotel $hotel)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'slug'                  => 'required|string|max:255|unique:hotels,slug,' . $hotel->id,
            'hotel_category_id'     => 'required|exists:hotel_categories,id',
            'status'                => 'boolean',
            'is_featured'           => 'boolean',
            'starting_price'        => 'nullable|integer',
            'featured_image_file'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_image_alt'    => 'nullable|string|max:255',
            'email'                 => 'nullable|email',
            'website'               => 'nullable|url',
            'affiliate_url'         => 'nullable|url',
            'latitude'              => 'nullable|numeric',
            'longitude'             => 'nullable|numeric',
            'amenities'             => 'nullable|array',
            'gallery_images.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'gallery_alts.*'        => 'nullable|string|max:255',
            'delete_gallery_ids'    => 'nullable|array',
        ]);

        $data = $request->except('_token', '_method', 'featured_image_file', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup', 'amenities', 'gallery_images', 'gallery_alts', 'delete_gallery_ids', 'faqs');
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('hotels', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        $hotel->update($data);

        $hotel->amenities()->sync($request->input('amenities', []));

        // Delete selected gallery images
        if ($request->filled('delete_gallery_ids')) {
            foreach ($request->input('delete_gallery_ids') as $imgId) {
                $img = \App\Models\HotelImage::find($imgId);
                if ($img && $img->hotel_id == $hotel->id) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $img->image));
                    $img->delete();
                }
            }
        }

        // Add new gallery images
        if ($request->hasFile('gallery_images')) {
            $existingCount = $hotel->images()->count();
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('hotels/gallery', 'public');
                $hotel->images()->create([
                    'image'      => 'storage/' . $path,
                    'alt_text'   => $request->input('gallery_alts.' . $index, ''),
                    'sort_order' => $existingCount + $index,
                ]);
            }
        }

        // Handle FAQs
        if ($request->has('faqs')) {
            $submittedFaqIds = [];
            $sortOrder = 0;
            foreach ($request->input('faqs') as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    if (isset($faq['id']) && $faq['id']) {
                        $existingFaq = $hotel->faqs()->find($faq['id']);
                        if ($existingFaq) {
                            $existingFaq->update([
                                'question'   => $faq['question'],
                                'answer'     => $faq['answer'],
                                'sort_order' => $sortOrder++
                            ]);
                            $submittedFaqIds[] = $existingFaq->id;
                        }
                    } else {
                        $newFaq = $hotel->faqs()->create([
                            'question'   => $faq['question'],
                            'answer'     => $faq['answer'],
                            'sort_order' => $sortOrder++
                        ]);
                        $submittedFaqIds[] = $newFaq->id;
                    }
                }
            }
            $hotel->faqs()->whereNotIn('id', $submittedFaqIds)->delete();
        } else {
            $hotel->faqs()->delete();
        }

        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description']);
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Hotel',
            'name' => $data['name'] ?? $request->name,
            'description' => trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($request->short_description ?: $request->description), ENT_QUOTES, 'UTF-8'))),
            'url' => $request->website,
            'telephone' => $request->phone,
            'email' => $request->email,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $request->address,
                'addressLocality' => $request->city,
                'addressRegion' => 'MI',
                'postalCode' => $request->zip,
                'addressCountry' => 'US'
            ]
        ];
        if ($request->starting_price) {
            $schema['priceRange'] = '$' . $request->starting_price . '+';
        }
        if (isset($data['featured_image'])) {
            $schema['image'] = url($data['featured_image']);
        } elseif ($hotel->featured_image) {
            $schema['image'] = url($hotel->featured_image);
        }
        $schema['address'] = array_filter($schema['address']);
        if (count($schema['address']) === 1) unset($schema['address']); // Only contains @type
        $schema = array_filter($schema);
        
        $seoData['schema_markup'] = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($hotel->seo) {
            $hotel->seo->update($seoData);
        } else {
            $hotel->seo()->create($seoData);
        }

        return redirect()->route('hotels.index')->with('success', 'Hotel updated successfully.');
    }

    public function destroy(\App\Models\Hotel $hotel)
    {
        $hotel->delete();
        return redirect()->route('hotels.index')->with('success', 'Hotel deleted successfully.');
    }

    public function changeStatus($id, $status)
    {
        $hotel = \App\Models\Hotel::findOrFail($id);
        $hotel->status = $status == 1 ? 0 : 1;
        $hotel->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.', 'status' => $hotel->status]);
    }
}
