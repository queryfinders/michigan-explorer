<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use App\Models\RestaurantImage;
use App\Models\RestaurantFaq;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('new_content.admin.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        $categories = RestaurantCategory::where('status', 1)->get();
        return view('new_content.admin.restaurants.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'slug'                  => 'required|string|max:255|unique:restaurants',
            'restaurant_category_id'=> 'required|exists:restaurant_categories,id',
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
            'map_iframe'            => 'nullable|string',
            'gallery_images.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'gallery_alts.*'        => 'nullable|string|max:255',
        ]);

        $data = $request->except('_token', '_method', 'featured_image_file', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup', 'gallery_images', 'gallery_alts', 'faqs');
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('restaurants', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        $restaurant = Restaurant::create($data);

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('restaurants/gallery', 'public');
                $restaurant->images()->create([
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
                    $restaurant->faqs()->create([
                        'question'   => $faq['question'],
                        'answer'     => $faq['answer'],
                        'sort_order' => $sortOrder++
                    ]);
                }
            }
        }

        // Generate JSON-LD Schema
        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description']);
        
        $restaurantSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => $restaurant->name,
            'description' => trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($request->short_description ?: $request->description), ENT_QUOTES, 'UTF-8'))),
            'url' => $request->website,
            'telephone' => $restaurant->phone,
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
        if ($restaurant->starting_price) {
            $restaurantSchema['priceRange'] = '$' . $restaurant->starting_price . '+';
        }
        if ($restaurant->featured_image) {
            $restaurantSchema['image'] = url($restaurant->featured_image);
        }
        $restaurantSchema['address'] = array_filter($restaurantSchema['address']);
        if (count($restaurantSchema['address']) === 1) unset($restaurantSchema['address']);
        $restaurantSchema = array_filter($restaurantSchema);

        $schemas = [$restaurantSchema];

        // FAQ schema
        if ($restaurant->faqs()->count() > 0) {
            $mainEntity = [];
            foreach ($restaurant->faqs as $faq) {
                $mainEntity[] = [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq->answer
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
        $restaurant->seo()->create($seoData);

        return redirect()->route('restaurants.index')->with('success', 'Restaurant created successfully.');
    }

    public function edit(Restaurant $restaurant)
    {
        $restaurant->load(['seo', 'images', 'faqs']);
        $categories = RestaurantCategory::where('status', 1)->get();
        return view('new_content.admin.restaurants.edit', compact('restaurant', 'categories'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'slug'                  => 'required|string|max:255|unique:restaurants,slug,' . $restaurant->id,
            'restaurant_category_id'=> 'required|exists:restaurant_categories,id',
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
            'map_iframe'            => 'nullable|string',
            'gallery_images.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'gallery_alts.*'        => 'nullable|string|max:255',
            'delete_gallery_ids'    => 'nullable|array',
        ]);

        $data = $request->except('_token', '_method', 'featured_image_file', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup', 'gallery_images', 'gallery_alts', 'delete_gallery_ids', 'faqs');
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('featured_image_file')) {
            // Delete old featured image if exists
            if ($restaurant->featured_image) {
                $oldPath = str_replace('storage/', '', $restaurant->featured_image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('featured_image_file')->store('restaurants', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        $restaurant->update($data);

        // Delete selected gallery images
        if ($request->has('delete_gallery_ids')) {
            $imagesToDelete = RestaurantImage::whereIn('id', $request->input('delete_gallery_ids'))->get();
            foreach ($imagesToDelete as $image) {
                $oldPath = str_replace('storage/', '', $image->image);
                Storage::disk('public')->delete($oldPath);
                $image->delete();
            }
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $currentCount = $restaurant->images()->count();
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('restaurants/gallery', 'public');
                $restaurant->images()->create([
                    'image'      => 'storage/' . $path,
                    'alt_text'   => $request->input('gallery_alts.' . $index, ''),
                    'sort_order' => $currentCount + $index,
                ]);
            }
        }

        // Handle FAQs
        $restaurant->faqs()->delete(); // Clear existing to prevent duplicates
        if ($request->has('faqs')) {
            $sortOrder = 0;
            foreach ($request->input('faqs') as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $restaurant->faqs()->create([
                        'question'   => $faq['question'],
                        'answer'     => $faq['answer'],
                        'sort_order' => $sortOrder++
                    ]);
                }
            }
        }

        // Generate JSON-LD Schema
        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description']);
        
        $restaurantSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => $restaurant->name,
            'description' => trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($request->short_description ?: $request->description), ENT_QUOTES, 'UTF-8'))),
            'url' => $restaurant->website,
            'telephone' => $restaurant->phone,
            'email' => $restaurant->email,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $restaurant->address,
                'addressLocality' => $restaurant->city,
                'addressRegion' => 'MI',
                'postalCode' => $restaurant->zip,
                'addressCountry' => 'US'
            ]
        ];
        if ($restaurant->starting_price) {
            $restaurantSchema['priceRange'] = '$' . $restaurant->starting_price . '+';
        }
        if ($restaurant->featured_image) {
            $restaurantSchema['image'] = url($restaurant->featured_image);
        }
        $restaurantSchema['address'] = array_filter($restaurantSchema['address']);
        if (count($restaurantSchema['address']) === 1) unset($restaurantSchema['address']);
        $restaurantSchema = array_filter($restaurantSchema);

        $schemas = [$restaurantSchema];

        // FAQ schema
        if ($restaurant->faqs()->count() > 0) {
            $mainEntity = [];
            foreach ($restaurant->faqs as $faq) {
                $mainEntity[] = [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq->answer
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
        
        if ($restaurant->seo) {
            $restaurant->seo->update($seoData);
        } else {
            $restaurant->seo()->create($seoData);
        }

        return redirect()->route('restaurants.index')->with('success', 'Restaurant updated successfully.');
    }

    public function destroy(Restaurant $restaurant)
    {
        // Delete gallery images files
        foreach ($restaurant->images as $image) {
            $oldPath = str_replace('storage/', '', $image->image);
            Storage::disk('public')->delete($oldPath);
        }
        // Delete featured image file
        if ($restaurant->featured_image) {
            $oldPath = str_replace('storage/', '', $restaurant->featured_image);
            Storage::disk('public')->delete($oldPath);
        }
        $restaurant->delete();
        return redirect()->route('restaurants.index')->with('success', 'Restaurant deleted successfully.');
    }
}
