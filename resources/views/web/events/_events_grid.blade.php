@if($events->isEmpty() && empty($filter))
    @php
        $demoEvents = collect([
            (object)[
                'name' => 'Grand Rapids Art Festival',
                'slug' => 'demo',
                'description' => 'Join us for the annual Grand Rapids Art Festival! This three-day event features incredible live music, delicious local food vendors, and stunning artwork.',
                'featured_image' => 'storage/demo/michigan_sleeping_bear_1783683642640.png',
                'start_date' => now()->addDays(2),
                'venue_name' => 'Calder Plaza',
                'city' => 'Grand Rapids',
                'price' => 0.00,
                'category' => (object)['name' => 'Arts & Culture', 'icon' => 'fas fa-palette']
            ],
            (object)[
                'name' => 'Detroit Jazz Fest',
                'slug' => 'demo',
                'description' => 'The Detroit Jazz Festival is a major free jazz festival held every year during Labor Day Weekend at Hart Plaza and Campus Martius Park in Detroit.',
                'featured_image' => 'storage/demo/michigan_hotel_lobby_1783683621508.png',
                'start_date' => now()->addDays(14),
                'venue_name' => 'Campus Martius',
                'city' => 'Detroit',
                'price' => 0.00,
                'category' => (object)['name' => 'Music', 'icon' => 'fas fa-music']
            ],
            (object)[
                'name' => 'Traverse City Cherry Festival',
                'slug' => 'demo',
                'description' => 'Celebrate the cherry harvest with a week of parades, pie-eating contests, air shows, and live entertainment on the shores of Grand Traverse Bay.',
                'featured_image' => 'storage/demo/michigan_lighthouse_1783683652511.png',
                'start_date' => now()->addDays(30),
                'venue_name' => 'Open Space Park',
                'city' => 'Traverse City',
                'price' => 15.00,
                'category' => (object)['name' => 'Festivals', 'icon' => 'fas fa-campground']
            ]
        ]);
    @endphp
    
    @foreach($demoEvents as $demoEvent)
    <div class="col-lg-4 col-md-6">
        <x-event-card :event="$demoEvent" />
    </div>
    @endforeach
@elseif($events->isEmpty())
    <div class="col-12 text-center py-5">
        <div class="text-muted mb-3"><i class="far fa-calendar-times fa-4x opacity-50"></i></div>
        <h3 class="fw-bold auto-style-7">No Events Found</h3>
        <p class="text-muted">There are no events matching your current filters.</p>
        <a href="{{ route('web.events.index') }}" class="btn btn-primary rounded-pill px-4 mt-3">Clear Filters</a>
    </div>
@else
    @foreach($events as $event)
    <div class="col-lg-4 col-md-6">
        <x-event-card :event="$event" />
    </div>
    @endforeach
    
    <!-- Infinite Scroll Pagination Metadata -->
    <div class="col-12 d-none" id="infinite-scroll-pagination-wrapper">
        @if($events->hasMorePages())
            <a href="{{ $events->appends(request()->query())->nextPageUrl() }}" id="next-page-link" rel="next">Next Page</a>
        @endif
    </div>
@endif
