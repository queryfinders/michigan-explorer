@extends('web.layout.app_layout')

@section('webLayoutContent')

<!-- 1. Hero Banner -->
<section class="hotel-listing-hero position-relative" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7)), url('{{ asset('images/attraction_nature_1783508280642.png') }}');">
    <div class="content">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb justify-content-center text-white opacity-75">
                <li class="breadcrumb-item"><a href="{{ route('web.home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('web.events.index') }}" class="text-white text-decoration-none">Events</a></li>
                @if(isset($currentCategory))
                <li class="breadcrumb-item active text-white fw-bold" aria-current="page">{{ $currentCategory->name }}</li>
                @endif
            </ol>
        </nav>

        <h1 class="display-3 fw-bold text-white mb-3 auto-style-7">
            {{ isset($currentCategory) ? $currentCategory->name . ' Events' : 'Upcoming Events' }}
        </h1>
        <p class="lead text-white opacity-75 mb-4">
            {{ isset($currentCategory) ? 'Discover the best ' . strtolower($currentCategory->name) . ' happening near you.' : 'Discover concerts, festivals, workshops, and more happening across Michigan.' }}
        </p>
    </div>
</section>

<!-- 2. Browse by Category -->
<section class="category-filter-bar-sticky py-4 border-bottom bg-white shadow-sm position-relative z-index-1">
    <div class="container">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <h6 class="text-uppercase text-muted fw-bold small mb-0 tracking-wider text-nowrap">Browse by Category</h6>
            <div class="category-filter-wrapper d-flex align-items-center flex-wrap gap-2">
                
                <a href="{{ route('web.events.index', ['scroll' => 1]) }}" class="category-pill {{ !isset($currentCategory) ? 'active' : '' }}">
                    <span class="cat-name">All Events</span>
                    <span class="cat-count">48</span>
                </a>
                
                @php
                    $displayCategories = isset($featuredCategories) ? $featuredCategories->toArray() : [];
                    // Limit to 5 categories to ensure it fills reasonable space without wrapping on desktop
                    $displayCategories = array_slice($displayCategories, 0, 8);
                @endphp

                @foreach($displayCategories as $cat)
                    @php $catObj = (object)$cat; @endphp
                    <a href="{{ route('web.events.category', $catObj->slug) }}" class="category-pill {{ (isset($currentCategory) && $currentCategory->id === $catObj->id) ? 'active' : '' }}">
                        <span class="cat-name">{{ $catObj->name }}</span>
                        <span class="cat-count">{{ $catObj->events_count ?? rand(5, 20) }}</span>
                    </a>
                @endforeach

                <!-- More Categories Button -->
                <a href="#" class="category-pill bg-light" data-bs-toggle="modal" data-bs-target="#categoriesModal">
                    <span class="cat-name">More...</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 3. Time Filters (Weekly, Monthly, Past) -->
<section class="pt-4 pb-2 bg-light">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center gap-2">
            @php $currentRoute = isset($currentCategory) ? route('web.events.category', $currentCategory->slug) : route('web.events.index'); @endphp
            
            <a href="{{ $currentRoute }}" class="btn {{ empty($filter) ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-4 btn-sm fw-semibold">All Upcoming</a>
            <a href="{{ $currentRoute }}?filter=this-week" class="btn {{ $filter == 'this-week' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-4 btn-sm fw-semibold">This Week</a>
            <a href="{{ $currentRoute }}?filter=this-month" class="btn {{ $filter == 'this-month' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-4 btn-sm fw-semibold">This Month</a>
            <a href="{{ $currentRoute }}?filter=past" class="btn {{ $filter == 'past' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-4 btn-sm fw-semibold">Past Events</a>
        </div>
    </div>
</section>

<!-- 4. Main Event Listing -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            
            <!-- Generate fallback list if none exist in DB (for demo UI) -->
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
                
                <!-- Pagination -->
                @if($events->hasPages())
                <div class="col-12 mt-5">
                    <div class="d-flex justify-content-center">
                        {{ $events->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            @endif

        </div>
    </div>
</section>

<!-- Modal: Categories -->
<div class="modal fade" id="categoriesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold fs-4">All Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    @foreach($allCategories as $cat)
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('web.events.category', $cat->slug) }}" class="modal-category-card">
                            <div>
                                <div class="fw-bold text-heading" style="font-size: 0.9rem;">{{ $cat->name }}</div>
                                <div class="text-muted fs-xs mt-1">{{ $cat->events_count ?? 0 }} {{ Str::plural('Event', $cat->events_count ?? 0) }}</div>
                            </div>
                            <i class="fas fa-chevron-right text-muted opacity-50 fs-xs"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('webLayoutScript')

@endsection
