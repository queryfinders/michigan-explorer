<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Sortable;
use App\Traits\Exportable;

class EventController extends Controller
{
    use Sortable, Exportable;
    public function index(Request $request)
    {
        $query = \App\Models\Event::with('category');
        
        // Filtering
        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->name}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('event_date')) {
            $query->whereDate('start_date', '<=', $request->event_date)
                  ->whereDate('end_date', '>=', $request->event_date);
        }

        $query = $this->applySorting($query, ['id', 'name', 'event_category_id', 'status', 'created_at'], 'created_at', 'desc');
        
        // Export
        if ($request->has('export')) {
            return $this->exportData($query, $request->export, 'events_export', function ($event) {
                return [
                    'ID' => $event->id,
                    'Event Name' => $event->name,
                    'Category' => $event->category ? $event->category->name : '',
                    'Location' => $event->venue_name ?? $event->city ?? '',
                    'Start Date' => $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') : '',
                    'End Date' => $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d') : '',
                    'Status' => $event->status ? 'Active' : 'Inactive',
                ];
            });
        }
        
        $events = $query->paginate(10);
        
        if ($request->ajax()) {
            return view('new_content.admin.events._table', compact('events'))->render();
        }
        
        return view('new_content.admin.events.index', compact('events'));
    }

    public function create()
    {
        $categories = \App\Models\EventCategory::where('status', 1)->get();
        return view('new_content.admin.events.create', compact('categories'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events',
            'event_category_id' => 'required|exists:event_categories,id',
            'status' => 'boolean',
            'featured_image' => 'nullable|image|max:4096',
            'featured_image_alt' => 'nullable|string|max:255',
            'video_file' => 'nullable|mimes:mp4,mov,ogg,qt|max:30000',
            'video_url' => 'nullable|url',
            'map_iframe' => 'nullable|string',
        ]);

        $data = $request->except('_token', '_method', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup', 'video_file', 'video_url', 'featured_image', 'faqs');
        
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('events/images', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('events/videos', 'public');
            $data['video'] = 'storage/' . $path;
        } elseif ($request->filled('video_url')) {
            $data['video'] = $request->input('video_url');
        }

        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        $event = \App\Models\Event::create($data);
        
        // Handle FAQs
        if ($request->has('faqs') && is_array($request->faqs)) {
            $faqs = [];
            foreach ($request->faqs as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $faqs[] = new \App\Models\EventFaq([
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'sort_order' => $faq['sort_order'] ?? 0,
                    ]);
                }
            }
            if (count($faqs) > 0) {
                $event->faqs()->saveMany($faqs);
            }
        }

        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description']);
        
        $schemas = [];
        $eventSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event->name,
            'description' => $request->meta_description ?? $event->short_description ?? '',
            'url' => route('web.events.show', $event->slug),
            'startDate' => $event->start_date ? \Carbon\Carbon::parse($event->start_date)->toIso8601String() : null,
            'endDate' => $event->end_date ? \Carbon\Carbon::parse($event->end_date)->toIso8601String() : null,
            'eventStatus' => 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'location' => [
                '@type' => 'Place',
                'name' => $event->venue_name ?? $event->name,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $event->address,
                    'addressLocality' => $event->city,
                    'addressRegion' => $event->state ?? 'MI',
                    'postalCode' => $event->zip,
                    'addressCountry' => 'US',
                ]
            ]
        ];
        if ($event->featured_image) {
            $eventSchema['image'] = url($event->featured_image);
        }
        if ($event->price) {
            $eventSchema['offers'] = [
                '@type' => 'Offer',
                'price' => $event->price,
                'priceCurrency' => 'USD',
                'url' => route('web.events.show', $event->slug),
                'availability' => 'https://schema.org/InStock'
            ];
        }
        $eventSchema['location']['address'] = array_filter($eventSchema['location']['address']);
        if (count($eventSchema['location']['address']) === 0) {
            unset($eventSchema['location']['address']);
        }
        $eventSchema['location'] = array_filter($eventSchema['location']);
        $eventSchema = array_filter($eventSchema);
        $schemas[] = $eventSchema;

        if ($event->faqs()->count() > 0) {
            $mainEntity = [];
            foreach ($event->faqs as $faq) {
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
        $event->seo()->create($seoData);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function edit(\App\Models\Event $event)
    {
        $event->load(['seo', 'faqs']);
        $categories = \App\Models\EventCategory::where('status', 1)->get();
        return view('new_content.admin.events.edit', compact('event', 'categories'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events,slug,' . $event->id,
            'event_category_id' => 'required|exists:event_categories,id',
            'status' => 'boolean',
            'featured_image' => 'nullable|image|max:4096',
            'featured_image_alt' => 'nullable|string|max:255',
            'video_file' => 'nullable|mimes:mp4,mov,ogg,qt|max:30000',
            'video_url' => 'nullable|url',
            'delete_video' => 'nullable|boolean',
            'map_iframe' => 'nullable|string',
        ]);

        $data = $request->except('_token', '_method', 'meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description', 'schema_markup', 'video_file', 'video_url', 'delete_video', 'featured_image', 'faqs');
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            if ($event->featured_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $event->featured_image));
            }
            $path = $request->file('featured_image')->store('events/images', 'public');
            $data['featured_image'] = 'storage/' . $path;
        }

        // Handle video deletion
        if ($request->input('delete_video') == '1') {
            if ($event->video && !str_starts_with($event->video, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $event->video));
            }
            $data['video'] = null;
        }

        // Handle video creation/updates
        if ($request->hasFile('video_file')) {
            if ($event->video && !str_starts_with($event->video, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $event->video));
            }
            $path = $request->file('video_file')->store('events/videos', 'public');
            $data['video'] = 'storage/' . $path;
        } elseif ($request->filled('video_url')) {
            if ($request->input('delete_video') != '1') {
                $data['video'] = $request->input('video_url');
            }
        }

        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        $event->update($data);
        
        // Handle FAQs
        $event->faqs()->delete();
        if ($request->has('faqs') && is_array($request->faqs)) {
            $faqs = [];
            foreach ($request->faqs as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $faqs[] = new \App\Models\EventFaq([
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'sort_order' => $faq['sort_order'] ?? 0,
                    ]);
                }
            }
            if (count($faqs) > 0) {
                $event->faqs()->saveMany($faqs);
            }
        }

        $seoData = $request->only(['meta_title', 'meta_description', 'canonical_url', 'og_title', 'og_description']);
        
        $schemas = [];
        $eventSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event->name,
            'description' => $request->meta_description ?? $event->short_description ?? '',
            'url' => route('web.events.show', $event->slug),
            'startDate' => $event->start_date ? \Carbon\Carbon::parse($event->start_date)->toIso8601String() : null,
            'endDate' => $event->end_date ? \Carbon\Carbon::parse($event->end_date)->toIso8601String() : null,
            'eventStatus' => 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'location' => [
                '@type' => 'Place',
                'name' => $event->venue_name ?? $event->name,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $event->address,
                    'addressLocality' => $event->city,
                    'addressRegion' => $event->state ?? 'MI',
                    'postalCode' => $event->zip,
                    'addressCountry' => 'US',
                ]
            ]
        ];
        if ($event->featured_image) {
            $eventSchema['image'] = url($event->featured_image);
        }
        if ($event->price) {
            $eventSchema['offers'] = [
                '@type' => 'Offer',
                'price' => $event->price,
                'priceCurrency' => 'USD',
                'url' => route('web.events.show', $event->slug),
                'availability' => 'https://schema.org/InStock'
            ];
        }
        $eventSchema['location']['address'] = array_filter($eventSchema['location']['address']);
        if (count($eventSchema['location']['address']) === 0) {
            unset($eventSchema['location']['address']);
        }
        $eventSchema['location'] = array_filter($eventSchema['location']);
        $eventSchema = array_filter($eventSchema);
        $schemas[] = $eventSchema;

        if ($event->faqs()->count() > 0) {
            $mainEntity = [];
            foreach ($event->faqs as $faq) {
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
        if ($event->seo) {
            $event->seo->update($seoData);
        } else {
            $event->seo()->create($seoData);
        }

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(\App\Models\Event $event)
    {
        if ($event->featured_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $event->featured_image));
        }
        if ($event->video && !str_starts_with($event->video, 'http')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $event->video));
        }
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    public function changeStatus($id, $status)
    {
        $event = \App\Models\Event::findOrFail($id);
        $event->status = $status;
        $event->save();

        return response()->json(['success' => true]);
    }
}
