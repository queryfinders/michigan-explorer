@extends('web.layout.app_layout')

@section('title', $hotel->name . ' - Michigan Explorer')

@section('webLayoutContent')
<div class="container detail-container">
    
    <!-- 1. Breadcrumb -->
    @php
        $prevUrl = url()->previous();
        $prevPath = parse_url($prevUrl, PHP_URL_PATH);
        
        $middleLabel = null;
        $middleUrl = null;
        
        if (Str::contains($prevPath, '/hotels/category/')) {
            $slug = basename($prevPath);
            $cat = \App\Models\HotelCategory::where('slug', $slug)->first();
            if ($cat) {
                $middleLabel = $cat->name;
                $middleUrl = $prevUrl;
            }
        }
    @endphp
    <nav class="breadcrumb-custom">
        <a href="{{ route('web.home') }}">Home</a> <i class="fas fa-chevron-right mx-2 text-muted fs-7"></i>
        <a href="{{ route('web.hotels.index') }}">Hotels</a> <i class="fas fa-chevron-right mx-2 text-muted fs-7"></i>
        @if($middleLabel && $middleUrl)
            <a href="{{ $middleUrl }}">{{ $middleLabel }}</a> <i class="fas fa-chevron-right mx-2 text-muted fs-7"></i>
        @endif
        <span class="text-muted">{{ $hotel->name }}</span>
    </nav>

    <!-- 2. Hero Section -->
    <div class="hotel-header">
        <div>
            <div class="d-flex align-items-center mb-2 flex-wrap gap-2">
                @if($hotel->is_featured)
                <span class="badge bg-warning text-dark rounded-pill fw-bold px-3 py-2"><i class="fas fa-crown me-1"></i> Featured Partner</span>
                @endif
                <span class="badge bg-primary text-white rounded-pill fw-bold px-3 py-2">{{ $hotel->category->name ?? 'Luxury Resort' }}</span>
            </div>
            <h1 class="hotel-header-title">{{ $hotel->name }}</h1>
            <div class="hotel-header-location">
                <i class="fas fa-map-marker-alt text-amber"></i> 
                {{ !empty($hotel->address) ? $hotel->address . ', ' : '' }}{{ $hotel->city ?? 'Mackinac Island' }}, {{ $hotel->state ?? 'MI' }} {{ $hotel->zip ?? '49757' }}
                <a href="#location-map" class="text-amber fw-bold ms-2 text-underline">Show on map</a>
            </div>
        </div>
        <div class="hotel-actions mt-3 mt-md-0 d-flex gap-2">
            <button class="btn btn-outline-secondary bg-white" onclick="shareCurrentPage('{{ addslashes($hotel->name) }}')"><i class="fas fa-share-alt"></i> Share</button>
        </div>
    </div>

    <!-- 3. Image Gallery -->
    @php
        $galleryItems = isset($hotel->images) && $hotel->images instanceof \Illuminate\Support\Collection
            ? $hotel->images
            : (isset($hotel->images) ? collect($hotel->images) : collect([]));
        $hasDynamicGallery = $galleryItems->count() > 0;
        $featuredSrc = !empty($hotel->featured_image) && (is_object($hotel) && property_exists($hotel, 'slug') ? $hotel->slug !== 'demo' : true)
            ? asset($hotel->featured_image)
            : asset('storage/demo/michigan_resort_exterior_1783683587847.png');
        // Build full gallery array: featured first, then additional images
        $allGalleryImages = [];
        $allGalleryImages[] = ['src' => $featuredSrc, 'alt' => $hotel->featured_image_alt ?? $hotel->name ?? 'Hotel'];
        if ($hasDynamicGallery) {
            foreach ($galleryItems as $img) {
                $allGalleryImages[] = ['src' => asset($img->image), 'alt' => $img->alt_text ?? $hotel->name ?? 'Hotel Gallery'];
            }
        } else {
            // Static demo thumbnails as fallback
            $allGalleryImages[] = ['src' => asset('storage/demo/michigan_hotel_room_1_1783683598842.png'), 'alt' => 'Room 1'];
            $allGalleryImages[] = ['src' => asset('storage/demo/michigan_hotel_room_2_1783683609409.png'), 'alt' => 'Room 2'];
            $allGalleryImages[] = ['src' => asset('storage/demo/michigan_hotel_lobby_1783683621508.png'), 'alt' => 'Lobby'];
            $allGalleryImages[] = ['src' => asset('storage/demo/michigan_hotel_pool_1783683632041.png'), 'alt' => 'Pool'];
        }
        $extraCount = count($allGalleryImages) - 5;
    @endphp
    <div class="gallery-grid mb-4">
        {{-- Main featured image or video --}}
        <div class="gallery-item main-img">
            @if(!empty($hotel->video))
                <div class="video-wrapper-premium w-100 h-100">
                    <div class="video-loading-spinner" id="videoSpinnerHotels">
                        <div class="spinner-border text-white" role="status" style="width: 1.5rem; height: 1.5rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <video class="w-100 h-100 object-fit-cover rounded-3 shadow-sm" controls autoplay muted loop playsinline style="object-fit: cover;"
                           onplay="document.getElementById('videoSpinnerHotels').style.display='none'"
                           onplaying="document.getElementById('videoSpinnerHotels').style.display='none'"
                           onwaiting="document.getElementById('videoSpinnerHotels').style.display='flex'"
                           oncanplay="document.getElementById('videoSpinnerHotels').style.display='none'">
                        <source src="{{ asset($hotel->video) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @else
                <img src="{{ $allGalleryImages[0]['src'] }}" alt="{{ $allGalleryImages[0]['alt'] }}" onclick="openCustomGallery(0)" class="cursor-pointer">
            @endif
        </div>
        {{-- Thumbnails: show up to 4 --}}
        @for($gi = 1; $gi <= min(4, count($allGalleryImages) - 1); $gi++)
        <div class="gallery-item cursor-pointer" onclick="openCustomGallery({{ $gi }})">
            <img src="{{ $allGalleryImages[$gi]['src'] }}" alt="{{ $allGalleryImages[$gi]['alt'] }}">
            @if($gi === 4 && $extraCount > 0)
                <div class="gallery-overlay-count">+{{ $extraCount }}</div>
            @endif
        </div>
        @endfor
    </div>
    
    <!-- Quick Facts -->
    <div class="quick-facts-row">
        @if(isset($hotel->category))
        <div class="quick-fact-item"><i class="fas fa-building"></i> {{ $hotel->category->name }}</div>
        @endif
        
        @if(isset($hotel->amenities) && $hotel->amenities->count() > 0)
            @foreach($hotel->amenities->take(4) as $amenity)
                <div class="quick-fact-item"><i class="fas {{ $amenity->icon ?? 'fa-check' }}"></i> {{ $amenity->name }}</div>
            @endforeach
        @else
            <div class="quick-fact-item"><i class="fas fa-bed"></i> 128 Rooms</div>
            <div class="quick-fact-item"><i class="fas fa-parking"></i> Free Parking</div>
            <div class="quick-fact-item"><i class="fas fa-paw"></i> Pet Friendly</div>
            <div class="quick-fact-item"><i class="fas fa-swimmer"></i> Indoor Pool</div>
        @endif
    </div>

    <!-- 4. Main Layout -->
    <div class="row">
        
        <!-- LEFT CONTENT (70%) -->
        <div class="col-lg-8">
            
            <!-- Overview -->
            <div class="content-card">
                <h2 class="mb-5">About {{ $hotel->name }}</h2>
                <div class="text-muted lh-18">
                    @if($hotel->description)
                        {!! $hotel->description !!}
                    @else
                        <p>Experience the pinnacle of luxury and comfort at our exquisite property. Located in the heart of the city, we offer world-class amenities, stunning views, and exceptional service to make your stay truly unforgettable.</p>
                        <p>Whether you are here for business or leisure, our beautifully appointed rooms and state-of-the-art facilities ensure a perfect getaway.</p>
                    @endif
                </div>
            </div>

            <!-- Amenities -->
            <div class="content-card">
                <h3 class="mb-4">Top Amenities</h3>
                <div class="amenities-grid-premium">
                    @forelse($hotel->amenities as $amenity)
                        <div class="amenity-card">
                            <i class="fas {{ $amenity->icon ?? 'fa-check' }}"></i>
                            <span>{{ $amenity->name }}</span>
                        </div>
                    @empty
                        <div class="text-muted">No amenities listed.</div>
                    @endforelse
                </div>
            </div>

            <!-- Hotel Information -->
            @if(isset($hotel->policyValues) && $hotel->policyValues->count() > 0)
            <div class="content-card">
                <h3 class="mb-4">Hotel Policies & Info</h3>
                <div class="policy-list">
                    @foreach($hotel->policyValues->sortBy('policy.sort_order') as $policyValue)
                    <div class="policy-item">
                        <div>
                            <h6>{{ $policyValue->policy->name }}</h6>
                            <p>{!! nl2br(e($policyValue->value)) !!}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Location & Map removed from here -->

            <!-- Nearby Attractions -->
            <div class="content-card">
                <h3 class="mb-4">Nearby Attractions</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <x-attraction-card :attraction="(object)[
                            'name' => 'Sleeping Bear Dunes',
                            'distance' => '2.5 miles away',
                            'travel_time_car' => '10 min drive',
                            'travel_time_walk' => '45 min walk',
                            'description' => 'Experience towering sand dunes and spectacular views of Lake Michigan at this national lakeshore.',
                            'slug' => 'demo',
                            'featured_image' => asset('storage/demo/michigan_sleeping_bear_1783683642640.png')
                        ]" />
                    </div>
                    <div class="col-md-6">
                        <x-attraction-card :attraction="(object)[
                            'name' => 'Grand Haven Lighthouse',
                            'distance' => '1.2 miles away',
                            'travel_time_car' => '5 min drive',
                            'travel_time_walk' => '20 min walk',
                            'description' => 'A historic red lighthouse located on the pier, offering a scenic walk and beautiful sunset views over the water.',
                            'slug' => 'demo',
                            'featured_image' => asset('storage/demo/michigan_lighthouse_1783683652511.png')
                        ]" />
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            @if($hotel->faqs && count($hotel->faqs) > 0)
            <div class="content-card">
                <h3>Frequently Asked Questions</h3>
                <div class="accordion accordion-flush mt-3" id="hotelFaq">
                    @foreach($hotel->faqs as $index => $faq)
                    <div class="accordion-item border rounded-3 {{ $loop->last ? '' : 'mb-2' }} overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="faq{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#hotelFaq">
                            <div class="accordion-body text-muted">{!! $faq->answer !!}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
        </div>

        <!-- RIGHT SIDEBAR (30%) -->
        <div class="col-lg-4">
            <div class="sidebar-card-premium">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase mb-1">Starting from</div>
                        @if($hotel->starting_price)
                        <div class="sidebar-price">${{ $hotel->starting_price }} <span>/night</span></div>
                        @else
                        <div class="sidebar-price" style="font-size: 1.5rem;">Check Rates</div>
                        @endif
                    </div>
                </div>
                
                <hr class="text-muted opacity-25">

                <div class="mb-4 text-center">
                    <p class="text-muted small mb-0">Check availability and book securely through our official affiliate partner for the best guaranteed rate.</p>
                </div>

                @if($hotel->affiliate_url)
                <a href="{{ $hotel->affiliate_url }}" class="btn-affiliate-book" target="_blank">
                    Check Availability & Book <i class="fas fa-external-link-alt ms-2"></i>
                </a>
                @else
                <button class="btn-affiliate-book disabled" disabled style="opacity: 0.6; cursor: not-allowed;">
                    Currently Unavailable Online
                </button>
                @endif

                @if($hotel->bookingFeatures && $hotel->bookingFeatures->count() > 0)
                <div class="trust-badges">
                    @foreach($hotel->bookingFeatures as $feature)
                    <div class="trust-badge">
                        @if($feature->icon)
                            <i class="{{ $feature->icon }}"></i>
                        @endif
                        {{ $feature->name }}
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        
        <!-- Full-Width Location & Map -->
        <div class="content-card mt-4" id="location-map">
            <h3>Location</h3>
            <p class="text-muted mb-3"><i class="fas fa-map-marker-alt text-primary me-2"></i> {{ $hotel->address ?? 'Main Street' }}, {{ $hotel->city ?? 'Mackinac Island' }}, {{ $hotel->state ?? 'MI' }}</p>
            <div class="map-container bg-light h-500px">
                <!-- Google Maps Iframe Placeholder -->
                <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ $hotel->latitude && $hotel->longitude ? $hotel->latitude . ',' . $hotel->longitude : urlencode(($hotel->address ?? 'Main Street') . ' ' . ($hotel->city ?? 'Mackinac Island')) }}&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <h6 class="fw-bold mb-1">Contact Info</h6>
                    <p class="mb-0 text-muted small"><i class="fas fa-phone-alt me-2"></i> {{ $hotel->phone ?? '(555) 123-4567' }}</p>
                </div>
                <a href="https://maps.google.com/?q={{ $hotel->latitude && $hotel->longitude ? $hotel->latitude . ',' . $hotel->longitude : urlencode(($hotel->address ?? 'Main Street') . ' ' . ($hotel->city ?? 'Mackinac Island')) }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4">Get Directions</a>
            </div>
        </div>

    </div>
    
</div>

<!-- Related Hotels Section -->
@if(isset($relatedHotels) && $relatedHotels->count() > 0)
<section class="section-padding-related-hotel bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-heading">Related Hotels</h2>
                <p class="text-muted mb-0">Discover more luxury stays and similar accommodations in the area.</p>
            </div>
            <a href="{{ route('web.hotels.index') }}" class="btn btn-outline-primary rounded-pill px-4">View All</a>
        </div>
        
        <div class="row g-4">
            @foreach($relatedHotels as $relatedHotel)
            <div class="col-lg-4 col-md-6">
                <x-hotel-card :hotel="$relatedHotel" :compact="true" />
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Bottom CTA using reusable component -->
<!-- <x-cta-block 
    title="Ready to Plan Your Michigan Getaway?"
    subtitle="Compare prices and book securely through our trusted travel partners. Rooms at {{ $hotel->name ?? 'our hotels' }} fill up fast during the season!"
    buttonText="Check Availability & Book"
    buttonUrl="{{ $hotel->affiliate_url ?? '#' }}"
/> -->

<!-- Custom Fullscreen Gallery Lightbox -->
<div id="customGalleryLightbox" class="custom-lightbox" style="display: none;">
    <button class="lightbox-close" onclick="closeCustomGallery()"><i class="fas fa-times"></i></button>
    <div class="lightbox-counter"><span id="lightbox-current-idx">1</span> / <span id="lightbox-total-idx">5</span></div>
    
    <button class="lightbox-nav lightbox-prev" onclick="changeLightboxImage(-1)"><i class="fas fa-chevron-left"></i></button>
    
    <div class="lightbox-image-container">
        <img id="lightbox-main-img" src="" alt="Gallery Image" style="display: block;">
        <video id="lightbox-main-video" controls autoplay muted style="display: none; max-width: 90%; max-height: 80vh;" class="rounded"></video>
    </div>
    
    <button class="lightbox-nav lightbox-next" onclick="changeLightboxImage(1)"><i class="fas fa-chevron-right"></i></button>
    
    <div class="lightbox-thumbnail-strip" id="lightbox-thumbnails">
        <!-- Thumbnails injected via JS -->
    </div>
</div>

<script>
    const galleryItemsList = [
        @if(!empty($hotel->video))
        { type: 'video', src: "{{ asset($hotel->video) }}" },
        @endif
        @foreach($allGalleryImages as $gImg)
        { type: 'image', src: "{{ $gImg['src'] }}" },
        @endforeach
    ];
    let currentGalleryIndex = 0;

    function openCustomGallery(index = 0) {
        // Adjust index if video is present (since video is placed first at index 0)
        @if(!empty($hotel->video))
            currentGalleryIndex = index + 1;
        @else
            currentGalleryIndex = index;
        @endif
        
        document.getElementById('customGalleryLightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Disable scroll
        updateLightbox();
        renderThumbnails();
    }

    function closeCustomGallery() {
        // Pause any playing lightbox video
        const videoEl = document.getElementById('lightbox-main-video');
        if (videoEl) videoEl.pause();
        
        document.getElementById('customGalleryLightbox').style.display = 'none';
        document.body.style.overflow = 'auto'; // Re-enable scroll
    }

    function changeLightboxImage(direction) {
        currentGalleryIndex += direction;
        if (currentGalleryIndex < 0) currentGalleryIndex = galleryItemsList.length - 1;
        if (currentGalleryIndex >= galleryItemsList.length) currentGalleryIndex = 0;
        updateLightbox();
    }

    function updateLightbox() {
        const item = galleryItemsList[currentGalleryIndex];
        const imgEl = document.getElementById('lightbox-main-img');
        const videoEl = document.getElementById('lightbox-main-video');
        
        if (videoEl) {
            videoEl.pause();
            videoEl.style.display = 'none';
        }
        if (imgEl) {
            imgEl.style.display = 'none';
        }
        
        if (item.type === 'video') {
            if (videoEl) {
                videoEl.src = item.src;
                videoEl.style.display = 'block';
                videoEl.load();
                videoEl.play();
            }
        } else {
            if (imgEl) {
                imgEl.style.opacity = 0;
                imgEl.style.display = 'block';
                setTimeout(() => {
                    imgEl.src = item.src;
                    imgEl.style.opacity = 1;
                }, 200);
            }
        }
        
        document.getElementById('lightbox-current-idx').innerText = currentGalleryIndex + 1;
        document.getElementById('lightbox-total-idx').innerText = galleryItemsList.length;
        
        const thumbs = document.querySelectorAll('.lightbox-thumb-container');
        thumbs.forEach((t, i) => {
            if (i === currentGalleryIndex) t.classList.add('active');
            else t.classList.remove('active');
        });
    }

    function renderThumbnails() {
        const strip = document.getElementById('lightbox-thumbnails');
        strip.innerHTML = '';
        galleryItemsList.forEach((item, index) => {
            const thumbContainer = document.createElement('div');
            thumbContainer.className = 'lightbox-thumb-container ' + (index === currentGalleryIndex ? 'active' : '');
            
            if (item.type === 'video') {
                thumbContainer.innerHTML = `<div class="lightbox-thumb-video-placeholder"><i class="fas fa-play"></i></div>`;
            } else {
                const img = document.createElement('img');
                img.src = item.src;
                img.className = 'lightbox-thumb';
                thumbContainer.appendChild(img);
            }
            
            thumbContainer.onclick = () => { currentGalleryIndex = index; updateLightbox(); };
            strip.appendChild(thumbContainer);
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
