@extends('web.layout.app_layout')

@section('title', 'Michigan Explorer - Luxury Travel & Tourism')

@section('webLayoutContent')

<!-- 1. PREMIUM HERO SECTION -->
<section class="hero-premium position-relative overflow-hidden">
    <div class="hero-bg-parallax">
        <div class="hero-bg-zoom" role="img" aria-label="{{ $page->featured_image_alt ?? 'Michigan Explorer Banner' }}" style="background-image: url('{{ $page && $page->featured_image ? asset($page->featured_image) : asset('images/hero_banner_1783508250640.png') }}');"></div>
    </div>

    <div class="container position-relative z-index-1 text-white py-5 my-5">
        
        <!-- Typography with Stagger Animation -->
        <div class="text-center mb-3">
            <h1 class="display-3 fw-bold mb-4 font-heading text-shadow-md" data-aos="fade-up" data-aos-duration="1000">
                {{ $page->banner_title ?? 'Discover the True Beauty of Michigan' }}
            </h1>
            <p class="lead fs-5 mx-auto hero-subtitle text-shadow-sm lh-18 mx-auto" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                {{ $page->banner_subtitle ?? 'Experience luxury stays, amazing restaurants, breathtaking attractions, exciting events, and unforgettable adventures across Michigan.' }}
            </p>
        </div>

        <!-- Alpine.js Smart Search Component -->
        <div x-data="smartSearch()" class="smart-search-container" data-aos="zoom-in" data-aos-delay="400" data-aos-duration="1000" @click.away="isOpen = false">
            
            <form action="{{ route('web.search') }}" method="GET" class="smart-search-box" @submit="onSubmit">
                <i class="fas fa-search smart-search-icon" x-show="!isLoading"></i>
                <div class="search-loader" x-show="isLoading" class="d-none"></div>
                
                <input 
                    type="text" 
                    name="keyword" 
                    class="smart-search-input" 
                    placeholder="Search hotels, restaurants, attractions, events or destinations..." 
                    autocomplete="off"
                    x-model="keyword"
                    @input.debounce.300ms="fetchSuggestions"
                    @focus="isOpen = true; if(keyword.length > 0) fetchSuggestions()"
                    @keydown.down.prevent="navigate(1)"
                    @keydown.up.prevent="navigate(-1)"
                    @keydown.enter.prevent="selectCurrent"
                    @keydown.escape="isOpen = false"
                >
                
                <button type="submit" class="smart-search-btn d-none d-sm-block">Search</button>
                <button type="submit" class="smart-search-btn d-block d-sm-none px-3 rounded-circle btn-icon-50 p-0"><i class="fas fa-search"></i></button>
            </form>

            <!-- Autocomplete Dropdown -->
            <div class="autocomplete-dropdown" :class="{ 'show': isOpen && keyword.length > 0 }">
                
                <div x-show="!isLoading && Object.keys(groupedResults).length === 0 && keyword.length > 0" class="p-4 text-center text-muted">
                    <i class="fas fa-search-minus fs-2 mb-2 text-muted opacity-50"></i>
                    <p class="mb-0">No results found for "<span x-text="keyword" class="fw-bold"></span>"</p>
                </div>

                <template x-for="(group, category) in groupedResults" :key="category">
                    <div class="autocomplete-group">
                        <div class="autocomplete-group-title" x-text="category"></div>
                        <template x-for="item in group.items" :key="item.id">
                            <a :href="item.url" class="autocomplete-item d-flex align-items-center" :class="{ 'active': activeIndex === item.index }" @mouseenter="activeIndex = item.index">
                                <img :src="item.image" alt="" class="rounded me-3 shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-dark lh-1 mb-1" x-html="highlight(item.title)"></div>
                                    <div class="small text-muted lh-1"><i :class="group.icon" class="me-1"></i><span x-text="item.location"></span></div>
                                </div>
                            </a>
                        </template>
                        <template x-if="group.has_more">
                            <div class="text-center p-2 border-top bg-light">
                                <a :href="group.view_all_url" class="small fw-bold text-primary text-decoration-none">View All <span x-text="category"></span> &rarr;</a>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
        
        <!-- Popular Searches (Chips) -->
        @if(isset($searchShortcuts) && $searchShortcuts->count() > 0)
        <div class="text-center pt-4" data-aos="fade-up" data-aos-delay="600">
            <p class="small text-white mb-3 fw-bold text-uppercase tracking-wider text-shadow-dark">Popular Searches</p>
            <div class="popular-chips-wrapper justify-content-center">
                @foreach($searchShortcuts as $shortcut)
                <a href="{{ route('web.search_shortcuts.track', $shortcut->id) }}" target="{{ $shortcut->open_in == 'new_tab' ? '_blank' : '_self' }}" class="premium-chip">
                    @if($shortcut->icon)
                        <i class="{{ $shortcut->icon }} {{ $shortcut->icon_color }}"></i> 
                    @endif
                    {{ $shortcut->title }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator" @click="window.scrollTo({top: window.innerHeight, behavior: 'smooth'})">
        <span class="d-block mb-1 small text-uppercase tracking-wider">Explore More</span>
        <i class="fas fa-arrow-down fs-4"></i>
    </div>
</section>


<!-- 3. Featured Hotels (Component-Driven) -->
<section class="section-padding bg-light overflow-hidden">
    <div class="container">
        
        <x-section-header 
            title="Featured Hotels" 
            subtitle="Discover handpicked hotels, luxury resorts, boutique stays, and budget-friendly accommodations across Michigan."
            actionUrl="{{ route('web.hotels.index') }}"
            actionText="View All Hotels"
        />
        
        <div class="row g-4 mt-2">
            @if(isset($hotels) && $hotels->count() > 0)
                @foreach($hotels->take(3) as $hotel)
                <div class="col-lg-4 col-md-6">
                    <x-hotel-card :hotel="$hotel" :featured="false" :compact="true" />
                </div>
                @endforeach
            @else
                <!-- Static Fallback Data -->
                @for($i=1; $i<=3; $i++)
                <div class="col-lg-4 col-md-6">
                    <x-hotel-card :hotel="(object)[
                        'name' => 'The Grand Hotel Resort',
                        'city' => 'Mackinac Island',
                        'description' => 'Experience the pinnacle of luxury with breathtaking views and world-class amenities.',
                        'starting_price' => '399',
                        'affiliate_url' => '#'
                    ]" :featured="false" :compact="true" />
                </div>
                @endfor
            @endif
        </div>
        
    </div>
</section>


<!-- 4. Featured Restaurants (Component-Driven) -->
<section class="overflow-hidden">
    <div class="container">
        
        <x-section-header 
            title="Featured Restaurants" 
            subtitle="Discover Michigan's best local restaurants, cafés, fine dining experiences, waterfront dining, family restaurants, and hidden culinary gems."
            actionUrl="{{ route('web.restaurants.index') }}"
            actionText="View All Restaurants"
        />
        
        <div class="row g-4 mt-2">
            @if(isset($restaurants) && $restaurants->count() > 0)
                @foreach($restaurants->take(3) as $restaurant)
                <div class="col-lg-4 col-md-6">
                    <x-restaurant-card :restaurant="$restaurant" :featured="false" :compact="true" />
                </div>
                @endforeach
            @else
                <!-- Static Fallback Data -->
                @for($i=1; $i<=3; $i++)
                <div class="col-lg-4 col-md-6">
                    <x-restaurant-card :restaurant="(object)[
                        'name' => $i === 1 ? 'Lakeside Prime Steakhouse' : 'The Harbor Cafe',
                        'city' => 'Traverse City',
                        'description' => 'Savor exquisite culinary masterpieces with breathtaking waterfront views.',
                        'starting_price' => '45',
                        'affiliate_url' => route('web.restaurants.show', 'demo'),
                        'category' => (object)['name' => $i === 1 ? 'Fine Dining' : 'Cafe']
                    ]" :featured="false" :compact="true" />
                </div>
                @endfor
            @endif
        </div>
        
    </div>
</section>

<!-- 5. Featured Attractions -->
<section class="section-padding bg-light pb-0">
    <div class="container">
        <h2 class="section-title">Must-See Attractions</h2>
        <p class="section-subtitle">Discover the hidden gems and natural wonders of the Great Lakes state.</p>
        
        <div class="row g-4">
             @if(isset($attractions) && $attractions->count() > 0)
                @foreach($attractions->take(3) as $index => $attraction)
                <div class="col-lg-4 col-md-6">
                    <x-attraction-card :attraction="$attraction" :featured="false" />
                </div>
                @endforeach
            @else
                <!-- Static Fallback Data -->
                @for($i=1; $i<=3; $i++)
                <div class="col-lg-4 col-md-6">
                    <x-attraction-card :attraction="(object)[
                        'name' => $i === 1 ? 'Pictured Rocks National Lakeshore' : 'Sleeping Bear Dunes',
                        'slug' => 'demo',
                        'city' => $i === 1 ? 'Munising' : 'Empire',
                        'description' => $i === 1 ? 'Experience majestic sandstone cliffs, pristine waterfalls, and turquoise waters.' : 'Experience towering sand dunes and spectacular views of Lake Michigan at this national lakeshore.',
                        'distance' => '2.5 miles away',
                        'travel_time_car' => '10 min drive',
                        'travel_time_walk' => '45 min walk',
                    ]" :featured="false" />
                </div>
                @endfor
            @endif
        </div>
    </div>
</section>

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
                        'featured_image' => 'images/festival_event_1783508290846.png',
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
                        'featured_image' => 'images/event_art_fair.png',
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
                        'featured_image' => 'images/event_fudge.png',
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

<!-- 7. Affiliate Promotions -->
<section class="py-0">
    <div class="container-fluid px-0">
        <div class="card border-0 rounded-0 text-white position-relative promo-banner-wrapper">
    <img src="{{ asset('images/promo_banner_1783508311655.png') }}" class="promo-bg-img" loading="lazy" alt="Promo Background">
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-primary"></div>
            <div class="container position-relative z-index-1">
                <div class="row">
                    <div class="col-lg-6">
                        <span class="badge bg-secondary mb-3 fs-6 px-3 py-2 rounded-pill">Special Promotion</span>
                        <h2 class="display-4 fw-bold mb-4 text-white font-heading">Save 20% on Romantic Lakefront Escapes</h2>
                        <p class="fs-4 mb-5 text-light">Book your next getaway through our exclusive affiliate partners and enjoy premium upgrades.</p>
                        <a href="#" class="btn btn-secondary btn-lg rounded-pill px-5">Claim Offer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 8. Latest Travel Guides (Blogs) -->
<section class="section-padding bg-light">
    <div class="container">
        <h2 class="section-title">Latest Travel Guides</h2>
        <p class="section-subtitle">Tips, itineraries, and stories from local experts.</p>
        
        <div class="row g-4">
            @if(isset($blogs) && $blogs->count() > 0)
                @foreach($blogs->take(3) as $blog)
                <div class="col-lg-4 col-md-6">
                    <div class="premium-card border-0">
                        <div class="img-wrapper">
                            <img src="{{ $blog->featured_image ? asset($blog->featured_image) : asset('images/travel_guide_1783508300840.png') }}" class="card-img-top" alt="{{ $blog->title }}">
                        </div>
                        <div class="card-body bg-white rounded-bottom-4">
                            <span class="text-primary fw-bold small text-uppercase mb-2 d-block">{{ $blog->category->name ?? 'Travel' }}</span>
                            <h3 class="card-title">{{ $blog->title }}</h3>
                            <p class="text-muted small mb-4">By {{ $blog->author ? $blog->author->name : 'Admin' }} | {{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</p>
                            <a href="{{ route('web.blogs.show', $blog->slug) }}" class="text-primary fw-bold text-decoration-none">Read Full Guide <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Static Fallback Data -->
                @for($i=1; $i<=3; $i++)
                <div class="col-lg-4 col-md-6">
                    <div class="premium-card border-0">
                        <div class="img-wrapper">
                            <img src="{{ asset('images/travel_guide_1783508300840.png') }}" class="card-img-top" alt="Travel Guide">
                        </div>
                        <div class="card-body bg-white rounded-bottom-4">
                            <span class="text-primary fw-bold small text-uppercase mb-2 d-block">Travel Itinerary</span>
                            <h3 class="card-title">The Ultimate 5-Day Upper Peninsula Road Trip</h3>
                            <p class="text-muted small mb-4">By Explorer Team | July 15, 2026</p>
                            <a href="#" class="text-primary fw-bold text-decoration-none">Read Full Guide <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                @endfor
            @endif
        </div>
    </div>
</section>

<!-- 9. Newsletter Strip -->
<section class="py-5 bg-primary">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-6 text-white text-center text-lg-start mb-4 mb-lg-0">
                <h3 class="fw-bold mb-2 text-white font-heading">Join the Explorer Club</h3>
                <p class="mb-0 text-white fs-5">Get the best travel secrets and exclusive deals delivered to your inbox.</p>
            </div>
            <div class="col-lg-5">
                <form action="#">
                    <div class="input-group input-group-lg shadow-lg rounded-pill overflow-hidden bg-white p-1">
                        <input type="email" class="form-control border-0 shadow-none px-4" placeholder="Enter your email address" required>
                        <button class="btn btn-primary rounded-pill px-4" type="submit">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
