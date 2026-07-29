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
        <div x-data="smartSearch()" id="heroSearchContainer" class="smart-search-container" data-aos="zoom-in" data-aos-delay="400" data-aos-duration="1000" @click.away="isOpen = false">
            
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
    <div class="scroll-indicator d-inline-flex align-items-center gap-2" onclick="window.scrollTo({top: window.innerHeight, behavior: 'smooth'})">
        <span>Explore More</span>
        <i class="fas fa-chevron-down"></i>
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

{{-- Latest Upcoming Event Strip: below Featured Hotels --}}
@if(isset($upcomingEventsWidget) && $upcomingEventsWidget->count() > 0)
@php $latestStripEv = $upcomingEventsWidget->first(); @endphp
<section class="section-padding-upcoming bg-light overflow-hidden">
<div class="container">
    <a href="{{ route('web.events.show', $latestStripEv->slug) }}" class="text-decoration-none d-block ev-bottom-strip rounded-4 overflow-hidden" style="background: #fff; border: 1.5px solid #e9ecef; box-shadow: 0 2px 16px rgba(0,0,0,0.06);">
        <div class="d-flex align-items-stretch" style="min-height: 120px;">
            {{-- Thumbnail --}}
            <div class="flex-shrink-0 position-relative overflow-hidden" style="width: 190px;">
                @if($latestStripEv->featured_image)
                <img src="{{ asset($latestStripEv->featured_image) }}" alt="{{ $latestStripEv->featured_image_alt ?? $latestStripEv->name }}"
                     class="ev-strip-img w-100 h-100 position-absolute top-0 start-0" style="object-fit: cover;">
                @else
                <div class="w-100 h-100 d-flex align-items-center justify-content-center position-absolute top-0 start-0" style="background: #fff3cd;">
                    <i class="fas fa-calendar-star text-warning fs-2"></i>
                </div>
                @endif
            </div>
            {{-- Content --}}
            <div class="px-4 py-3 d-flex flex-column justify-content-center flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="fw-bold text-uppercase" style="background: #fff3cd; color: #e67e00; font-size: 0.68rem; letter-spacing: 0.07em; padding: 3px 10px; border-radius: 100px;">
                        <i class="fas fa-bolt me-1"></i> Latest Upcoming
                    </span>
                    @if($latestStripEv->category)
                    <span class="text-muted" style="font-size: 0.75rem;">{{ $latestStripEv->category->name }}</span>
                    @endif
                </div>
                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem; line-height: 1.3;">{{ $latestStripEv->name }}</h5>
                <div class="d-flex flex-wrap gap-3 text-muted" style="font-size: 0.8rem;">
                    @if($latestStripEv->start_date)
                    <span><i class="fas fa-calendar-alt me-1 text-warning"></i>{{ \Carbon\Carbon::parse($latestStripEv->start_date)->format('l, M j, Y') }}</span>
                    @endif
                    @if($latestStripEv->venue_name)
                    <span><i class="fas fa-map-marker-alt me-1 text-primary"></i>{{ $latestStripEv->venue_name }}</span>
                    @endif
                    @if($latestStripEv->city)
                    <span><i class="fas fa-city me-1 text-primary"></i>{{ $latestStripEv->city }}, MI</span>
                    @endif
                </div>
            </div>
            {{-- CTA --}}
            <div class="flex-shrink-0 d-flex align-items-center px-4">
                <span class="btn btn-warning rounded-pill px-4 fw-bold btn-sm">
                    See Event <i class="fas fa-arrow-right ms-1"></i>
                </span>
            </div>
        </div>
    </a>
</div>
</section>
@endif


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
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
            <div>
                <h2 class="section-title mb-0">Must-See Attractions</h2>
                <p class="section-subtitle mb-0 mt-2">Discover the hidden gems and natural wonders of the Great Lakes state.</p>
            </div>
            <a href="{{ route('web.attractions.index') }}" class="btn btn-outline-primary rounded-pill">View All Attractions</a>
        </div>
        
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
@php
    $promo = $homepagePromotion ?? null;
    $promoBg = $promo
        ? asset($promo->desktop_image)
        : asset('images/promo_banner_1783508311655.png');
    $promoMobileBg = $promo && $promo->mobile_image
        ? asset($promo->mobile_image)
        : $promoBg;
    $promoBadge    = $promo ? $promo->badge_text : 'Special Promotion';
    $promoTitle    = $promo ? $promo->title      : 'Save 20% on Romantic Lakefront Escapes';
    $promoSubtitle = $promo ? $promo->subtitle   : 'Book your next getaway through our exclusive affiliate partners and enjoy premium upgrades.';
    $promoCtaText  = $promo ? $promo->cta_text   : 'Claim Offer';
    $promoCtaHref  = $promo
        ? route('affiliate.redirect', ['type' => 'promotion', 'id' => $promo->id])
        : '#';
@endphp
<section class="py-0">
    <div class="container-fluid px-0">
        <div class="card border-0 rounded-0 text-white position-relative promo-banner-wrapper">
            {{-- Desktop image --}}
            <img src="{{ $promoBg }}" class="promo-bg-img d-none d-md-block" loading="lazy" alt="{{ $promoTitle }}">
            {{-- Mobile image (portrait) --}}
            <img src="{{ $promoMobileBg }}" class="promo-bg-img d-block d-md-none" loading="lazy" alt="{{ $promoTitle }}">
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-primary"></div>
            <div class="container position-relative z-index-1">
                <div class="row">
                    <div class="col-lg-6">
                        <span class="badge bg-secondary mb-3 fs-6 px-3 py-2 rounded-pill">{{ $promoBadge }}</span>
                        <h2 class="display-4 fw-bold mb-4 text-white font-heading">{{ $promoTitle }}</h2>
                        <p class="fs-4 mb-5 text-light">{{ $promoSubtitle }}</p>
                        <a href="{{ $promoCtaHref }}" class="btn btn-secondary btn-lg rounded-pill px-5" @if($promo) target="_blank" @endif>{{ $promoCtaText }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 8. Latest Travel Guides (Blogs) -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
            <div>
                <h2 class="section-title mb-0">Latest Travel Guides</h2>
                <p class="section-subtitle mb-0 mt-2">Tips, itineraries, and stories from local experts.</p>
            </div>
            <a href="{{ route('web.blogs.index') }}" class="btn btn-outline-primary rounded-pill">View All Guides</a>
        </div>
        
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

<!-- Upcoming Events Widget: Large Banner + Side Cards -->
@if(isset($upcomingEventsWidget) && $upcomingEventsWidget->count() > 0)
@php
    $featuredEv  = $upcomingEventsWidget->first();
    $sideEvs     = $upcomingEventsWidget->skip(1)->take(3);
    $bottomEv    = $upcomingEventsWidget->skip(1)->first(); // single latest after featured
@endphp
<style>
    /* Hover: main banner image zoom */
    .ev-banner-link .ev-banner-img {
        transition: transform 0.5s cubic-bezier(.25,.8,.25,1);
    }
    .ev-banner-link:hover .ev-banner-img {
        transform: scale(1.06);
    }
    /* Hover: side cards glow */
    .ev-side-card {
        transition: background 0.25s, border-color 0.25s, transform 0.2s, box-shadow 0.25s;
    }
    .ev-side-card:hover {
        background: rgba(255,159,28,0.12) !important;
        border-color: rgba(255,159,28,0.45) !important;
        transform: translateX(4px);
        box-shadow: 0 4px 24px rgba(255,159,28,0.15);
    }
    /* Hover: bottom strip */
    .ev-bottom-strip {
        transition: background 0.3s, box-shadow 0.3s;
    }
    .ev-bottom-strip:hover {
        background: rgba(255,255,255,0.07) !important;
        box-shadow: 0 0 0 2px rgba(255,159,28,0.35);
    }
    .ev-bottom-strip:hover .ev-strip-img {
        transform: scale(1.04);
    }
    .ev-strip-img {
        transition: transform 0.4s ease;
    }
</style>

<section class="section-padding" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0f2027 100%);">
    <div class="container">

        <!-- Section Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-5">
            <div>
                <span class="badge rounded-pill px-3 py-2 mb-2 d-inline-block fw-semibold" style="background: rgba(255,159,28,0.18); color: #ff9f1c; font-size: 0.75rem; letter-spacing: 0.08em;">
                    <i class="fas fa-fire me-1"></i> DON'T MISS OUT
                </span>
                <h2 class="fw-bold text-white mb-1" style="font-size: 2rem;">Upcoming Events in Michigan</h2>
                <p class="text-white opacity-50 mb-0">Live concerts, festivals, cultural gatherings &amp; more.</p>
            </div>
            <a href="{{ route('web.events.index') }}" class="btn btn-outline-light rounded-pill px-4 fw-semibold mt-3 mt-md-0">
                View All Events <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

        <!-- Main Grid: Featured Banner + Side Cards -->
        <div class="row g-4 align-items-stretch">

            <!-- Left: Featured Event Banner with hover zoom -->
            <div class="col-lg-7">
                <a href="{{ route('web.events.show', $featuredEv->slug) }}" class="text-decoration-none d-block h-100 ev-banner-link">
                    <div class="position-relative rounded-4 overflow-hidden h-100" style="min-height: 420px;">
                        @if($featuredEv->featured_image)
                        <img src="{{ asset($featuredEv->featured_image) }}" alt="{{ $featuredEv->featured_image_alt ?? $featuredEv->name }}"
                             class="w-100 h-100 position-absolute top-0 start-0 ev-banner-img" style="object-fit: cover;">
                        @else
                        <div class="w-100 h-100 position-absolute top-0 start-0 ev-banner-img" style="background: linear-gradient(135deg, #1a1a2e, #16213e);"></div>
                        @endif
                        <!-- Gradient Overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.35) 50%, transparent 100%);"></div>
                        <!-- Content -->
                        <div class="position-absolute bottom-0 start-0 p-4 p-md-5 w-100">
                            @if($featuredEv->category)
                            <span class="badge rounded-pill px-3 py-2 mb-3 d-inline-block fw-semibold" style="background: #ff9f1c; color: #fff; font-size: 0.75rem;">
                                @if($featuredEv->category->icon)<i class="{{ $featuredEv->category->icon }} me-1"></i>@endif
                                {{ $featuredEv->category->name }}
                            </span>
                            @endif
                            <h3 class="fw-bold text-white mb-2" style="font-size: 1.6rem; line-height: 1.25;">{{ $featuredEv->name }}</h3>
                            <div class="d-flex flex-wrap gap-3 text-white opacity-75 mb-3" style="font-size: 0.875rem;">
                                @if($featuredEv->start_date)
                                <span><i class="fas fa-calendar-alt me-1 text-warning"></i>{{ \Carbon\Carbon::parse($featuredEv->start_date)->format('M j, Y') }}</span>
                                @endif
                                @if($featuredEv->venue_name)
                                <span><i class="fas fa-map-marker-alt me-1 text-warning"></i>{{ $featuredEv->venue_name }}</span>
                                @endif
                                @if($featuredEv->city)
                                <span><i class="fas fa-city me-1 text-warning"></i>{{ $featuredEv->city }}</span>
                                @endif
                            </div>
                            <span class="btn btn-warning btn-sm rounded-pill px-4 fw-bold">
                                View Details <i class="fas fa-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Right: Stacked Event Cards with hover glow -->
            <div class="col-lg-5 d-flex flex-column gap-3">
                @foreach($sideEvs as $sideEv)
                <a href="{{ route('web.events.show', $sideEv->slug) }}" class="text-decoration-none d-block flex-fill">
                    <div class="ev-side-card rounded-4 overflow-hidden d-flex align-items-stretch h-100" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.09); min-height: 110px;">
                        <!-- Thumbnail -->
                        @if($sideEv->featured_image)
                        <img src="{{ asset($sideEv->featured_image) }}" alt="{{ $sideEv->featured_image_alt ?? $sideEv->name }}"
                             class="flex-shrink-0" style="width: 110px; object-fit: cover;">
                        @else
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 110px; background: rgba(255,159,28,0.12);">
                            <i class="fas fa-calendar-alt text-warning fs-3"></i>
                        </div>
                        @endif
                        <!-- Text -->
                        <div class="p-3 d-flex flex-column justify-content-center overflow-hidden flex-grow-1">
                            @if($sideEv->category)
                            <span class="small fw-semibold mb-1" style="color: #ff9f1c; font-size: 0.7rem; letter-spacing: 0.05em; text-transform: uppercase;">{{ $sideEv->category->name }}</span>
                            @endif
                            <div class="fw-bold text-white mb-1" style="font-size: 0.95rem; line-height: 1.3;">{{ Str::limit($sideEv->name, 50) }}</div>
                            <div class="d-flex flex-wrap gap-2 text-white" style="opacity: 0.55; font-size: 0.78rem;">
                                @if($sideEv->start_date)
                                <span><i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($sideEv->start_date)->format('M j, Y') }}</span>
                                @endif
                                @if($sideEv->city)
                                <span><i class="fas fa-map-marker-alt me-1"></i>{{ $sideEv->city }}</span>
                                @endif
                            </div>
                        </div>
                        <!-- Arrow -->
                        <div class="flex-shrink-0 d-flex align-items-center px-3" style="color: rgba(255,159,28,0.5);">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

        </div>{{-- end main grid row --}}

        {{-- (bottom strip moved above Featured Hotels) --}}

    </div>
</section>
@endif

<!-- 9. Newsletter Strip -->
<section class="py-5 bg-primary">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-6 text-white text-center text-lg-start mb-4 mb-lg-0">
                <h3 class="fw-bold mb-2 text-white font-heading">Join the Explorer Club</h3>
                <p class="mb-0 text-white fs-5">Get the best travel secrets and exclusive deals delivered to your inbox.</p>
            </div>
            <div class="col-lg-5">
                <form id="explorerClubForm" method="POST" action="{{ route('newsletter.subscribe') }}">
                    @csrf
                    <input type="hidden" name="source" value="explorer_club">
                    <div class="input-group input-group-lg shadow-lg rounded-pill overflow-hidden bg-white p-1">
                        <input type="email" name="email" class="form-control border-0 shadow-none px-4" placeholder="Enter your email address" required>
                        <button class="btn btn-primary rounded-pill px-4" type="submit">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('webLayoutScript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchContainer = document.getElementById('heroSearchContainer');
        if (!searchContainer) return;
        
        const initialOffset = searchContainer.offsetTop + 120;
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > initialOffset) {
                searchContainer.classList.add('sticky-active');
            } else {
                searchContainer.classList.remove('sticky-active');
            }
        });
    });
</script>
@endsection
