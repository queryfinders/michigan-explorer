<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Sortable;
use App\Traits\Exportable;

class HotelController extends Controller
{
    use Sortable, Exportable;
    public function index(Request $request)
    {
        $query = \App\Models\Hotel::with('category');
        
        // Filtering
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%");
            });
        }
        if ($request->filled('category')) {
            $query->where('hotel_category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        $query = $this->applySorting($query, ['id', 'name', 'hotel_category_id', 'is_featured', 'status', 'created_at'], 'created_at', 'desc');
        
        // Export
        if ($request->has('export')) {
            return $this->exportData($query, $request->export, 'hotels_export', function ($hotel) {
                return [
                    'ID' => $hotel->id,
                    'Name' => $hotel->name,
                    'Category' => $hotel->category ? $hotel->category->name : '',
                    'City' => $hotel->city,
                    'State' => $hotel->state,
                    'Featured' => $hotel->is_featured ? 'Yes' : 'No',
                    'Status' => $hotel->status ? 'Active' : 'Inactive',
                    'Created At' => $hotel->created_at ? $hotel->created_at->format('Y-m-d H:i:s') : '',
                ];
            });
        }
        
        $hotels = $query->paginate(10);
        
        if ($request->ajax()) {
            return view('new_content.admin.hotels._table', compact('hotels'))->render();
        }
        
        $categories = \App\Models\HotelCategory::where('status', 1)->get();
        return view('new_content.admin.hotels.index', compact('hotels', 'categories'));
    }

    public function create()
    {
        $categories = \App\Models\HotelCategory::where('status', 1)->get();
        $amenities = \App\Models\Amenity::where('status', 1)->orderBy('name')->get();
        $bookingFeatures = \App\Models\BookingFeature::where('is_active', 1)->orderBy('sort_order')->get();
        $hotelPolicies = \App\Models\HotelPolicy::where('is_active', 1)->orderBy('sort_order')->get();
        return view('new_content.admin.hotels.create', compact('categories', 'amenities', 'bookingFeatures', 'hotelPolicies'));
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
            'video_file'            => 'nullable|mimes:mp4,mov,ogg,qt|max:30000',
            'video_url'             => 'nullable|url',
            'featured_image_alt'    => 'nullable|string|max:255',
            'email'                 => 'nullable|email',
            'website'               => 'nullable|url',
            'affiliate_link_id'     => 'nullable|exists:affiliate_links,id',
            'map_iframe'            => 'nullable|string',
            'amenities'             => 'nullable|array',
            'booking_features'      => 'nullable|array',
            'hotel_policies'        => 'nullable|array',
            'gallery_images.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'gallery_alts.*'        => 'nullable|string|max:255',
        ]);

        $data = $request->except('_token', '_method', 'featured_image_file', 'video_file', 'video_url', 'meta_title', 'meta_description', 'og_title', 'og_description', 'schema_markup', 'amenities', 'booking_features', 'hotel_policies', 'gallery_images', 'gallery_alts', 'faqs', 'affiliate_url');
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('hotels', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('hotels/videos', 'public');
            $data['video'] = 'storage/' . $path;
        } elseif ($request->filled('video_url')) {
            $data['video'] = $request->input('video_url');
        }

        $hotel = \App\Models\Hotel::create($data);

        $hotel->amenities()->sync($request->input('amenities', []));
        $hotel->bookingFeatures()->sync($request->input('booking_features', []));

        // Handle hotel policies
        if ($request->has('hotel_policies')) {
            foreach ($request->input('hotel_policies') as $policyId => $value) {
                if (!empty($value)) {
                    $hotel->policyValues()->create([
                        'hotel_policy_id' => $policyId,
                        'value' => $value
                    ]);
                }
            }
        }

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

        $seoData = $request->only(['meta_title', 'meta_description', 'og_title', 'og_description']);
        
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
        $hotel->load(['seo', 'amenities', 'images', 'bookingFeatures', 'policyValues']);
        $categories = \App\Models\HotelCategory::where('status', 1)->get();
        $amenities = \App\Models\Amenity::where('status', 1)->orderBy('name')->get();
        $bookingFeatures = \App\Models\BookingFeature::where('is_active', 1)->orderBy('sort_order')->get();
        $hotelPolicies = \App\Models\HotelPolicy::where('is_active', 1)->orderBy('sort_order')->get();
        return view('new_content.admin.hotels.edit', compact('hotel', 'categories', 'amenities', 'bookingFeatures', 'hotelPolicies'));
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
            'video_file'            => 'nullable|mimes:mp4,mov,ogg,qt|max:30000',
            'video_url'             => 'nullable|url',
            'featured_image_alt'    => 'nullable|string|max:255',
            'email'                 => 'nullable|email',
            'website'               => 'nullable|url',
            'affiliate_url'         => 'nullable|url',
            'map_iframe'            => 'nullable|string',
            'amenities'             => 'nullable|array',
            'booking_features'      => 'nullable|array',
            'hotel_policies'        => 'nullable|array',
            'gallery_images.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'gallery_alts.*'            => 'nullable|string|max:255',
            'delete_gallery_ids'        => 'nullable|array',
            'existing_gallery_alts.*'   => 'nullable|string|max:255',
        ]);

        $data = $request->except('_token', '_method', 'featured_image_file', 'video_file', 'video_url', 'delete_video', 'meta_title', 'meta_description', 'og_title', 'og_description', 'schema_markup', 'amenities', 'booking_features', 'hotel_policies', 'gallery_images', 'gallery_alts', 'delete_gallery_ids', 'existing_gallery_alts', 'faqs');
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('hotels', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        // Handle video deletion
        if ($request->input('delete_video') == '1') {
            if ($hotel->video && !str_starts_with($hotel->video, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $hotel->video));
            }
            $data['video'] = null;
        }

        // Handle video creation/updates
        if ($request->hasFile('video_file')) {
            if ($hotel->video && !str_starts_with($hotel->video, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $hotel->video));
            }
            $path = $request->file('video_file')->store('hotels/videos', 'public');
            $data['video'] = 'storage/' . $path;
        } elseif ($request->filled('video_url')) {
            if ($request->input('delete_video') != '1') {
                $data['video'] = $request->input('video_url');
            }
        }

        $hotel->update($data);

        $hotel->amenities()->sync($request->input('amenities', []));
        $hotel->bookingFeatures()->sync($request->input('booking_features', []));

        // Handle hotel policies
        $hotel->policyValues()->delete(); // Clear existing to prevent duplicates
        if ($request->has('hotel_policies')) {
            foreach ($request->input('hotel_policies') as $policyId => $value) {
                if (!empty($value)) {
                    $hotel->policyValues()->create([
                        'hotel_policy_id' => $policyId,
                        'value' => $value
                    ]);
                }
            }
        }

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

        // Update alt text for existing gallery images
        if ($request->filled('existing_gallery_alts')) {
            foreach ($request->input('existing_gallery_alts') as $imgId => $altText) {
                $img = \App\Models\HotelImage::find($imgId);
                if ($img && $img->hotel_id == $hotel->id) {
                    $img->update(['alt_text' => $altText]);
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

        $seoData = $request->only(['meta_title', 'meta_description', 'og_title', 'og_description']);
        
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
        $hotel->status = $status;
        $hotel->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.', 'status' => $hotel->status]);
    }
}
