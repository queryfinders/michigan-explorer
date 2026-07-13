@extends('web.layout.app_layout')

@section('title', 'Michigan Explorer - Luxury Travel & Tourism')

@section('webLayoutContent')

<!-- 1. PREMIUM HERO SECTION -->
<section class="hero-premium position-relative overflow-hidden">
    <img src="{{ asset('images/hero_banner_1783508250640.png') }}" class="hero-bg-img parallax-img" alt="Hero Background">

    <div class="container position-relative z-index-1 text-white py-5 my-5">
        
        <!-- Typography with Stagger Animation -->
        <div class="text-center mb-3">
            <h1 class="display-3 fw-bold mb-4 font-heading text-shadow-md" data-aos="fade-up" data-aos-duration="1000">
                {{ $heroData->title ?? 'Discover the True Beauty of Michigan' }}
            </h1>
            <p class="lead fs-5 mx-auto hero-subtitle text-shadow-sm lh-18 mx-auto" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                {{ $heroData->subtitle ?? 'Experience luxury stays, amazing restaurants, breathtaking attractions, exciting events, and unforgettable adventures across Michigan.' }}
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

                <template x-for="(items, category) in groupedResults" :key="category">
                    <div class="autocomplete-group">
                        <div class="autocomplete-group-title" x-text="category"></div>
                        <template x-for="item in items" :key="item.id">
                            <a :href="item.url" class="autocomplete-item" :class="{ 'active': activeIndex === item.index }" @mouseenter="activeIndex = item.index">
                                <i :class="item.icon"></i>
                                <span x-html="highlight(item.title)"></span>
                            </a>
                        </template>
                    </div>
                </template>
            </div>
        </div>
        
        <!-- Popular Searches (Chips) -->
        <div class="text-center pt-4" data-aos="fade-up" data-aos-delay="600">
            <p class="small text-white mb-3 fw-bold text-uppercase tracking-wider text-shadow-dark">Popular Searches</p>
            <div class="popular-chips-wrapper justify-content-center">
                <a href="{{ route('web.search', ['keyword' => 'Indiana Dunes']) }}" class="premium-chip"><i class="fas fa-fire text-warning"></i> Indiana Dunes</a>
                <a href="{{ route('web.search', ['keyword' => 'Hotels']) }}" class="premium-chip"><i class="fas fa-hotel"></i> Hotels</a>
                <a href="{{ route('web.search', ['keyword' => 'Restaurants']) }}" class="premium-chip"><i class="fas fa-utensils"></i> Restaurants</a>
                <a href="{{ route('web.search', ['keyword' => 'Casino']) }}" class="premium-chip"><i class="fas fa-dice"></i> Casino</a>
                <a href="{{ route('web.search', ['keyword' => 'Exit 34']) }}" class="premium-chip"><i class="fas fa-map-pin"></i> Exit 34</a>
                <a href="{{ route('web.search', ['keyword' => 'Washington Park']) }}" class="premium-chip"><i class="fas fa-water"></i> Washington Park</a>
                <a href="{{ route('web.search', ['keyword' => 'Amazon Data Center']) }}" class="premium-chip"><i class="fas fa-cloud"></i> Amazon Data Center</a>
            </div>
        </div>

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
                    <x-hotel-card :hotel="$hotel" :featured="$loop->first" :compact="true" />
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
                    ]" :featured="$i === 1" :compact="true" />
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
                    <x-restaurant-card :restaurant="$restaurant" :featured="$loop->first" :compact="true" />
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
                    ]" :featured="$i === 1" :compact="true" />
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
                @foreach($attractions->take(2) as $attraction)
                <div class="col-md-6">
                    <div class="card text-white border-0 overflow-hidden rounded-4 shadow-lg h-100">
                        <img src="{{ $attraction->image ? asset('storage/'.$attraction->image) : asset('images/attraction_nature_1783508280642.png') }}" class="card-img h-100 object-fit-cover" alt="{{ $attraction->name }}" class="h-400px filter-dark">
                        <div class="card-img-overlay d-flex flex-column justify-content-end p-5">
                            <h3 class="card-title display-6 fw-bold mb-3">{{ $attraction->name }}</h3>
                            <p class="card-text fs-5 mb-4">{{ Str::limit($attraction->description, 100) }}</p>
                            <div>
                                <a href="{{ route('web.attractions.show', $attraction->slug) }}" class="btn btn-primary rounded-pill px-4">Explore Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Static Fallback Data -->
                <div class="col-md-6">
                    <div class="card text-white border-0 overflow-hidden rounded-4 shadow-lg h-100">
                        <img src="{{ asset('images/attraction_nature_1783508280642.png') }}" class="card-img h-100 object-fit-cover" alt="Attraction" class="h-400px filter-dark">
                        <div class="card-img-overlay d-flex flex-column justify-content-end p-5">
                            <h3 class="card-title display-6 fw-bold mb-3">Pictured Rocks National Lakeshore</h3>
                            <p class="card-text fs-5 mb-4">Experience majestic sandstone cliffs, pristine waterfalls, and turquoise waters.</p>
                            <div>
                                <a href="#" class="btn btn-primary rounded-pill px-4">Explore Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white border-0 overflow-hidden rounded-4 shadow-lg h-100">
                        <img src="{{ asset('images/attraction_nature_1783508280642.png') }}" class="card-img h-100 object-fit-cover" alt="Attraction" class="h-400px filter-dark">
                        <div class="card-img-overlay d-flex flex-column justify-content-end p-5">
                            <h3 class="card-title display-6 fw-bold mb-3">Sleeping Bear Dunes</h3>
                            <p class="card-text fs-5 mb-4">Towering sand dunes offering panoramic views of Lake Michigan.</p>
                            <div>
                                <a href="#" class="btn btn-primary rounded-pill px-4">Explore Now</a>
                            </div>
                        </div>
                    </div>
                </div>
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
                @foreach($events->take(4) as $event)
                <div class="col-lg-3 col-md-6">
                    <div class="premium-card">
                        <div class="img-wrapper position-relative">
                            <img src="{{ $event->image ? asset('storage/'.$event->image) : asset('images/festival_event_1783508290846.png') }}" class="card-img-top" alt="{{ $event->title }}">
                            <div class="position-absolute top-0 end-0 bg-primary text-white p-2 m-3 rounded text-center fw-bold lh-sm">
                                <span class="d-block fs-4">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                                <span class="d-block small text-uppercase">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title fs-5">{{ $event->title }}</h4>
                            <div class="location-badge mb-0 mt-3"><i class="fas fa-map-marker-alt text-primary"></i> {{ $event->location ?? 'Michigan' }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Static Fallback Data -->
                @for($i=1; $i<=4; $i++)
                <div class="col-lg-3 col-md-6">
                    <div class="premium-card">
                        <div class="img-wrapper position-relative">
                            <img src="{{ asset('images/festival_event_1783508290846.png') }}" class="card-img-top" alt="Event">
                            <div class="position-absolute top-0 end-0 bg-primary text-white p-2 m-3 rounded text-center fw-bold lh-sm">
                                <span class="d-block fs-4">15</span>
                                <span class="d-block small text-uppercase">AUG</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title fs-5">Summer Music & Food Festival</h4>
                            <div class="location-badge mb-0 mt-3"><i class="fas fa-map-marker-alt text-primary"></i> Detroit, MI</div>
                        </div>
                    </div>
                </div>
                @endfor
            @endif
        </div>
    </div>
</section>

<!-- 7. Affiliate Promotions -->
<section class="py-0">
    <div class="container-fluid px-0">
        <div class="card border-0 rounded-0 text-white position-relative promo-banner-wrapper">
    <img src="{{ asset('images/promo_banner_1783508311655.png') }}" class="promo-bg-img" alt="Promo Background">
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
                            <img src="{{ $blog->image ? asset('storage/'.$blog->image) : asset('images/travel_guide_1783508300840.png') }}" class="card-img-top" alt="{{ $blog->title }}">
                        </div>
                        <div class="card-body bg-white rounded-bottom-4">
                            <span class="text-primary fw-bold small text-uppercase mb-2 d-block">{{ $blog->category->name ?? 'Travel' }}</span>
                            <h3 class="card-title">{{ $blog->title }}</h3>
                            <p class="text-muted small mb-4">By {{ $blog->author ?? 'Admin' }} | {{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</p>
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
<section class="py-5 bg-primary-theme">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-6 text-white text-center text-lg-start mb-4 mb-lg-0">
                <h3 class="fw-bold mb-2 text-white font-heading">Join the Explorer Club</h3>
                <p class="mb-0 text-light fs-5">Get the best travel secrets and exclusive deals delivered to your inbox.</p>
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
