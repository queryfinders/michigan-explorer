<!-- 6. Upcoming Events -->
<section class="section-padding">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-0">Upcoming Events</h2>
                <p class="section-subtitle mb-0 mt-2">Join vibrant festivals, concerts, and cultural gatherings.</p>
            </div>
            <a href="{{ route('web.events.index') }}" class="btn btn-outline-primary rounded-pill">View All Events</a>
        </div>
        
        <div class="row g-4">
            @if(isset($events) && $events->count() > 0)
                @foreach($events->take(3) as $event)
                <div class="col-lg-4 col-md-6">
                    <x-event-card :event="$event" />
                </div>
                @endforeach
            @else
                <!-- Static Fallback Data -->
                <div class="col-lg-4 col-md-6">
                    <x-event-card :event="(object)[
                        'name' => 'Summer Music & Food Festival',
                        'slug' => 'demo',
                        'description' => 'A lively summer celebration of local Michigan food trucks and live concerts on the waterfront.',
                        'featured_image' => 'images/festival_event_1783508290846.jpg',
                        'start_date' => now()->addDays(2),
                        'venue_name' => 'Hart Plaza',
                        'city' => 'Detroit',
                        'price' => 0.00,
                        'category' => (object)['name' => 'Music & Food', 'icon' => 'fas fa-music']
                    ]" />
                </div>
                <div class="col-lg-4 col-md-6">
                    <x-event-card :event="(object)[
                        'name' => 'Ann Arbor Art Fair',
                        'slug' => 'demo',
                        'description' => 'Browse beautiful paintings, sculptures, and crafts from hundreds of artists nationwide.',
                        'featured_image' => 'images/event_art_fair.jpg',
                        'start_date' => now()->addDays(14),
                        'venue_name' => 'Downtown Ann Arbor',
                        'city' => 'Ann Arbor',
                        'price' => 0.00,
                        'category' => (object)['name' => 'Arts & Culture', 'icon' => 'fas fa-palette']
                    ]" />
                </div>
                <div class="col-lg-4 col-md-6">
                    <x-event-card :event="(object)[
                        'name' => 'Mackinac Fudge Festival',
                        'slug' => 'demo',
                        'description' => 'A sweet celebration of the world-famous fudge shops on historic Mackinac Island.',
                        'featured_image' => 'images/event_fudge.jpg',
                        'start_date' => now()->addDays(28),
                        'venue_name' => 'Main Street',
                        'city' => 'Mackinac Island',
                        'price' => 15.00,
                        'category' => (object)['name' => 'Festivals', 'icon' => 'fas fa-candy-cane']
                    ]" />
                </div>
            @endif
        </div>
    </div>
</section>
