@extends('web.layout.app_layout')

@section('title', $restaurant->name . ' - Michigan Explorer')

@section('webLayoutContent')
<div class="container detail-container">
    
    <!-- 1. Breadcrumb -->
    <nav class="breadcrumb-custom">
        <a href="{{ route('web.home') }}">Home</a> <i class="fas fa-chevron-right mx-2 text-muted fs-7"></i>
        <a href="{{ route('web.restaurants.index') }}">Restaurants</a> <i class="fas fa-chevron-right mx-2 text-muted fs-7"></i>
        <a href="{{ route('web.restaurants.index', ['city' => $restaurant->city]) }}">{{ $restaurant->city ?? 'Michigan' }}</a> <i class="fas fa-chevron-right mx-2 text-muted fs-7"></i>
        <span class="text-muted">{{ $restaurant->name }}</span>
    </nav>

    <!-- 2. Hero Section -->
    <div class="hotel-header">
        <div>
            <div class="d-flex align-items-center mb-2 flex-wrap gap-2">
                <span class="badge bg-warning text-dark rounded-pill fw-bold px-3 py-2"><i class="fas fa-crown me-1"></i> Featured Partner</span>
                <span class="badge bg-primary text-white rounded-pill fw-bold px-3 py-2">{{ $hotel->category->name ?? 'Luxury Resort' }}</span>
                <div class="hotel-stars d-flex align-items-center bg-light rounded-pill px-3 py-1 ms-2 border">
                    <span class="fw-bold me-2 text-dark">4.8</span>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <a href="#reviews" class="text-muted ms-2 small text-decoration-underline">(1,245 Reviews)</a>
                </div>
            </div>
            <h1 class="hotel-header-title">{{ $restaurant->name }}</h1>
            <div class="hotel-header-location">
                <i class="fas fa-map-marker-alt text-amber"></i> 
                {{ !empty($restaurant->address) ? $restaurant->address . ', ' : '' }}{{ $restaurant->city ?? 'Traverse City' }}, {{ $restaurant->state ?? 'MI' }} {{ $restaurant->zip ?? '49684' }}
                <a href="#location-map" class="text-amber fw-bold ms-2 text-underline">Show on map</a>
            </div>
        </div>
        <div class="hotel-actions mt-3 mt-md-0 d-flex gap-2">
            <button class="btn btn-outline-secondary bg-white"><i class="fas fa-share-alt"></i> Share</button>
            <button class="btn btn-outline-secondary bg-white"><i class="far fa-heart"></i> Save</button>
        </div>
    </div>

    <!-- 3. Image Gallery -->
    <div class="gallery-grid mb-4" onclick="openCustomGallery()">
        <div class="gallery-item main-img">
            <img src="{{ !empty($restaurant->featured_image) && $restaurant->slug !== 'demo' ? asset($restaurant->featured_image) : asset('storage/demo/michigan_hotel_lobby_1783683621508.png') }}" alt="{{ $restaurant->name ?? 'Restaurant' }}">
        </div>
        <!-- Thumbnails -->
        <div class="gallery-item"><img src="{{ asset('storage/demo/michigan_hotel_room_1_1783683598842.png') }}" alt="Dining 1"></div>
        <div class="gallery-item"><img src="{{ asset('storage/demo/michigan_hotel_room_2_1783683609409.png') }}" alt="Dining 2"></div>
        <div class="gallery-item"><img src="{{ asset('storage/demo/michigan_hotel_pool_1783683632041.png') }}" alt="Exterior"></div>
        <div class="gallery-item">
            <img src="{{ asset('storage/demo/michigan_resort_exterior_1783683587847.png') }}" alt="Food">
            <div class="gallery-overlay-count">+12</div>
        </div>
    </div>
    
    <!-- Quick Facts -->
    <div class="quick-facts-row">
        <div class="quick-fact-item"><i class="fas fa-star text-warning"></i> 4.8 (890 Reviews)</div>
        <div class="quick-fact-item"><i class="fas fa-utensils"></i> {{ $restaurant->category->name ?? 'Fine Dining' }}</div>
        <div class="quick-fact-item"><i class="fas fa-door-open"></i> Open Now</div>
        <div class="quick-fact-item"><i class="fas fa-dollar-sign"></i>$$$</div>
        <div class="quick-fact-item"><i class="fas fa-child"></i> Family Friendly</div>
        <div class="quick-fact-item"><i class="fas fa-wine-glass"></i> Full Bar</div>
    </div>

    <!-- 4. Main Layout -->
    <div class="row">
        
        <!-- LEFT CONTENT (70%) -->
        <div class="col-lg-8">
            
            <!-- Overview -->
            <div class="content-card">
                <h3>About {{ $restaurant->name }}</h3>
                <div class="text-muted lh-18">
                    <p>{{ $restaurant->description ?: 'Experience the finest waterfront dining in Traverse City. Our culinary team crafts exquisite dishes using locally sourced ingredients, perfectly paired with our award-winning wine selection.' }}</p>
                    <p>Whether you are here for a romantic dinner or a family gathering, our beautifully appointed dining room and exceptional service ensure a perfect evening.</p>
                </div>
            </div>

            <!-- Amenities (Cuisine) -->
            <div class="content-card">
                <h3 class="mb-4">Cuisine & Features</h3>
                <div class="amenities-grid-premium">
                    <div class="amenity-card"><i class="fas fa-pizza-slice"></i><span>Italian</span></div>
                    <div class="amenity-card"><i class="fas fa-fish"></i><span>Seafood</span></div>
                    <div class="amenity-card"><i class="fas fa-drumstick-bite"></i><span>Steakhouse</span></div>
                    <div class="amenity-card"><i class="fas fa-leaf"></i><span>Vegetarian Options</span></div>
                    <div class="amenity-card"><i class="fas fa-wine-glass-alt"></i><span>Fine Wine</span></div>
                    <div class="amenity-card"><i class="fas fa-ice-cream"></i><span>Desserts</span></div>
                    <div class="amenity-card"><i class="fas fa-coffee"></i><span>Cafe</span></div>
                    <div class="amenity-card"><i class="fas fa-parking"></i><span>Valet Parking</span></div>
                </div>
            </div>

            <!-- Hotel Information -> Opening Hours -->
            <div class="content-card">
                <h3 class="mb-4">Opening Hours</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <h6>Monday - Thursday</h6>
                        <p>11:00 AM - 10:00 PM</p>
                    </div>
                    <div class="info-item">
                        <h6>Friday - Saturday</h6>
                        <p>11:00 AM - 11:30 PM</p>
                    </div>
                    <div class="info-item">
                        <h6>Sunday</h6>
                        <p>10:00 AM - 9:00 PM (Brunch available)</p>
                    </div>
                    <div class="info-item">
                        <h6>Happy Hour</h6>
                        <p>Daily 4:00 PM - 6:00 PM</p>
                    </div>
                </div>
            </div>

            <!-- Location & Map removed from here -->

            <!-- Nearby Attractions -->
            <!-- Nearby Attractions -->
            <div class="content-card">
                <h3 class="mb-4">Explore Nearby</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <x-hotel-card :hotel="(object)[
                            'name' => 'Sleeping Bear Dunes',
                            'city' => '2.5 miles away',
                            'description' => 'Experience towering sand dunes and spectacular views of Lake Michigan at this national lakeshore.',
                            'starting_price' => 'Free',
                            'affiliate_url' => '#',
                            'featured_image' => asset('storage/demo/michigan_sleeping_bear_1783683642640.png')
                        ]" />
                    </div>
                    <div class="col-md-6">
                        <x-hotel-card :hotel="(object)[
                            'name' => 'Grand Haven Lighthouse',
                            'city' => '1.2 miles away',
                            'description' => 'A historic red lighthouse located on the pier, offering a scenic walk and beautiful sunset views over the water.',
                            'starting_price' => 'Free',
                            'affiliate_url' => '#',
                            'featured_image' => asset('storage/demo/michigan_lighthouse_1783683652511.png')
                        ]" />
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="content-card">
                <h3>Frequently Asked Questions</h3>
                <div class="accordion accordion-flush mt-3" id="hotelFaq">
                    <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Is breakfast included in the room rate?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#hotelFaq">
                            <div class="accordion-body text-muted">Yes, a complimentary hot breakfast buffet is included for all guests booking directly or through our premium partners.</div>
                        </div>
                    </div>
                    <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Do you offer airport shuttle service?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#hotelFaq">
                            <div class="accordion-body text-muted">We offer a paid shuttle service to the local airport. Please contact the front desk 24 hours in advance to arrange transportation.</div>
                        </div>
                    </div>
                    <div class="accordion-item border rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Is there a pool on-site?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#hotelFaq">
                            <div class="accordion-body text-muted">Yes, we have a heated indoor swimming pool and a hot tub available for guests from 7:00 AM to 10:00 PM daily.</div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- RIGHT SIDEBAR (30%) -->
        <div class="col-lg-4">
            <div class="sidebar-card-premium">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase mb-1">Average Cost</div>
                        <div class="sidebar-price">${{ $restaurant->starting_price ?? '45' }} <span>/person</span></div>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-primary fs-6 mb-1 rounded-pill px-3 py-2"><i class="fas fa-star text-warning me-1"></i> 4.8</div>
                        <div class="text-muted small fw-bold">890 reviews</div>
                    </div>
                </div>
                
                <hr class="text-muted opacity-25">

                <div class="mb-4 text-center">
                    <p class="text-muted small mb-0">Secure your table in advance through our official reservation partner.</p>
                </div>

                <a href="{{ $restaurant->affiliate_url ?? '#' }}" class="btn-affiliate-book" target="_blank">
                    Reserve a Table <i class="fas fa-external-link-alt ms-2"></i>
                </a>

                <div class="mt-4 text-center">
                    <p class="small text-muted mb-2"><i class="fas fa-check-circle text-success me-1"></i> Free Cancellation</p>
                    <p class="small text-muted mb-0"><i class="fas fa-lock text-success me-1"></i> Secure & Trusted Booking</p>
                </div>
            </div>
        </div>
        
        <!-- Full-Width Location & Map -->
        <div class="content-card mt-4" id="location-map">
            <h3>Location</h3>
            <p class="text-muted mb-3"><i class="fas fa-map-marker-alt text-primary me-2"></i> {{ $hotel->address ?? 'Main Street' }}, {{ $hotel->city ?? 'Mackinac Island' }}, {{ $hotel->state ?? 'MI' }}</p>
            <div class="map-container bg-light h-500px">
                <!-- Google Maps Iframe Placeholder -->
                <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ urlencode(($hotel->address ?? 'Main Street') . ' ' . ($hotel->city ?? 'Mackinac Island')) }}&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <h6 class="fw-bold mb-1">Contact Info</h6>
                    <p class="mb-0 text-muted small"><i class="fas fa-phone-alt me-2"></i> {{ $hotel->phone ?? '(555) 123-4567' }}</p>
                </div>
                <a href="https://maps.google.com/?q={{ urlencode(($hotel->address ?? 'Main Street') . ' ' . ($hotel->city ?? 'Mackinac Island')) }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4">Get Directions</a>
            </div>
        </div>

    </div>
    
    <!-- Bottom CTA using reusable component -->
    <!-- <x-cta-block 
        title="Ready to Experience Michigan's Best Food?"
        subtitle="Discover local favorites and plan your next dining experience today. Tables at {{ $restaurant->name }} fill up fast!"
        buttonText="Explore Restaurants"
        buttonUrl="{{ route('web.restaurants.index') }}"
    /> -->

</div>

<!-- Custom Fullscreen Gallery Lightbox -->
<div id="customGalleryLightbox" class="custom-lightbox" style="display: none;">
    <button class="lightbox-close" onclick="closeCustomGallery()"><i class="fas fa-times"></i></button>
    <div class="lightbox-counter"><span id="lightbox-current-idx">1</span> / <span id="lightbox-total-idx">5</span></div>
    
    <button class="lightbox-nav lightbox-prev" onclick="changeLightboxImage(-1)"><i class="fas fa-chevron-left"></i></button>
    
    <div class="lightbox-image-container">
        <img id="lightbox-main-img" src="" alt="Gallery Image">
    </div>
    
    <button class="lightbox-nav lightbox-next" onclick="changeLightboxImage(1)"><i class="fas fa-chevron-right"></i></button>
    
    <div class="lightbox-thumbnail-strip" id="lightbox-thumbnails">
        <!-- Thumbnails injected via JS -->
    </div>
</div>

<script>
    const galleryImages = [
        "{{ !empty($restaurant->featured_image) && $restaurant->slug !== 'demo' ? asset($restaurant->featured_image) : asset('storage/demo/michigan_hotel_lobby_1783683621508.png') }}",
        "{{ asset('storage/demo/michigan_hotel_room_1_1783683598842.png') }}",
        "{{ asset('storage/demo/michigan_hotel_room_2_1783683609409.png') }}",
        "{{ asset('storage/demo/michigan_hotel_pool_1783683632041.png') }}",
        "{{ asset('storage/demo/michigan_resort_exterior_1783683587847.png') }}"
    ];
    let currentGalleryIndex = 0;

    function openCustomGallery(index = 0) {
        currentGalleryIndex = index;
        document.getElementById('customGalleryLightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Disable scroll
        updateLightbox();
        renderThumbnails();
    }

    function closeCustomGallery() {
        document.getElementById('customGalleryLightbox').style.display = 'none';
        document.body.style.overflow = 'auto'; // Re-enable scroll
    }

    function changeLightboxImage(direction) {
        currentGalleryIndex += direction;
        if (currentGalleryIndex < 0) currentGalleryIndex = galleryImages.length - 1;
        if (currentGalleryIndex >= galleryImages.length) currentGalleryIndex = 0;
        updateLightbox();
    }

    function updateLightbox() {
        const imgEl = document.getElementById('lightbox-main-img');
        imgEl.style.opacity = 0;
        setTimeout(() => {
            imgEl.src = galleryImages[currentGalleryIndex];
            imgEl.style.opacity = 1;
        }, 200);
        
        document.getElementById('lightbox-current-idx').innerText = currentGalleryIndex + 1;
        document.getElementById('lightbox-total-idx').innerText = galleryImages.length;
        
        const thumbs = document.querySelectorAll('.lightbox-thumb');
        thumbs.forEach((t, i) => {
            if (i === currentGalleryIndex) t.classList.add('active');
            else t.classList.remove('active');
        });
    }

    function renderThumbnails() {
        const strip = document.getElementById('lightbox-thumbnails');
        strip.innerHTML = '';
        galleryImages.forEach((src, index) => {
            const img = document.createElement('img');
            img.src = src;
            img.className = 'lightbox-thumb ' + (index === currentGalleryIndex ? 'active' : '');
            img.onclick = () => { currentGalleryIndex = index; updateLightbox(); };
            strip.appendChild(img);
        });
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (document.getElementById('customGalleryLightbox').style.display === 'flex') {
            if (e.key === 'Escape') closeCustomGallery();
            if (e.key === 'ArrowRight') changeLightboxImage(1);
            if (e.key === 'ArrowLeft') changeLightboxImage(-1);
        }
    });
</script>

@endsection
