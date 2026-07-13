@extends('web.layout.app_layout')

@php
    $metaTitle = $attraction->meta_title ?? $attraction->name . ' - Michigan Explorer';
    $metaDescription = $attraction->meta_description ?? Str::limit(strip_tags($attraction->description), 160);
    $heroImage = $attraction->featured_image ? asset($attraction->featured_image) : asset('images/attraction_nature_1783508280642.png');
@endphp

@section('title', $metaTitle)

@section('meta_description')
<meta name="description" content="{{ $metaDescription }}">
@endsection

@section('og_tags')
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ $heroImage }}">
<meta property="og:url" content="{{ route('web.attractions.show', $attraction->slug) }}">
<meta property="og:type" content="website">
@endsection

@section('canonical')
<link rel="canonical" href="{{ route('web.attractions.show', $attraction->slug) }}">
@endsection

@section('webLayoutContent')

<!-- 1. Hero Banner -->
<section class="hotel-detail-hero position-relative" style="height: 500px; background-image: linear-gradient(to bottom, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.3) 100%), url('{{ $heroImage }}'); background-size: cover; background-position: center;">
    <div class="container h-100 d-flex flex-column justify-content-center align-items-center text-center pt-5">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="{{ route('web.home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('web.attractions.index') }}" class="text-white text-decoration-none">Attractions</a></li>
                <li class="breadcrumb-item active text-white fw-bold" aria-current="page">{{ $attraction->name }}</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
            @if($attraction->category)
            <span class="badge bg-primary px-3 py-2 rounded-pill">{{ $attraction->category->name }}</span>
            @endif
        </div>
        <h1 class="display-4 fw-bold text-white mb-2 auto-style-7">{{ $attraction->name }}</h1>
        <p class="text-white opacity-75 fs-5 mb-0"><i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ $attraction->city }}, {{ $attraction->state }}</p>
    </div>
</section>

<!-- Main Content Area -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-5">
            
            <!-- Left Column: Description & Map -->
            <div class="col-lg-8">
                
                <!-- About Section -->
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4 auto-style-7">About this Attraction</h3>
                    <div class="text-muted auto-style-12">
                        @if($attraction->description)
                            {!! $attraction->description !!}
                        @else
                            <p>Discover the beauty and excitement of {{ $attraction->name }}. This premier destination in {{ $attraction->city }} offers visitors an unforgettable experience.</p>
                            <p>Whether you're looking for family fun, outdoor adventure, or a relaxing day out, this attraction has something for everyone. Plan your visit today and explore all that Michigan has to offer!</p>
                        @endif
                    </div>
                </div>

                <!-- Gallery Section -->
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4 auto-style-7">Gallery</h3>
                    <div class="row g-2">
                        <div class="col-md-8" onclick="openCustomGallery(0)" class="auto-style-13">
                            <img src="{{ $heroImage }}" class="img-fluid rounded-3 w-100 h-100 object-fit-cover shadow-sm transition-hover" alt="Gallery 1" class="auto-style-14">
                        </div>
                        <div class="col-md-4">
                            <div class="row g-2 h-100">
                                <div class="col-12 h-50" onclick="openCustomGallery(1)" class="auto-style-13">
                                    <img src="{{ asset('images/attraction_nature_1783508280642.png') }}" class="img-fluid rounded-3 w-100 h-100 object-fit-cover shadow-sm transition-hover" alt="Gallery 2">
                                </div>
                                <div class="col-12 h-50" onclick="openCustomGallery(2)" class="auto-style-15">
                                    <img src="{{ asset('storage/demo/michigan_lighthouse_1783683652511.png') }}" class="img-fluid rounded-3 w-100 h-100 object-fit-cover shadow-sm transition-hover" alt="Gallery 3">
                                    <div class="position-absolute top-0 start-0 w-100 rounded-3 d-flex justify-content-center align-items-center text-white fw-bold fs-4 transition-hover">
                                        +3
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4 auto-style-7">Frequently Asked Questions</h3>
                    <div class="accordion accordion-flush" id="attractionFaq">
                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    What are the operating hours?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#attractionFaq">
                                <div class="accordion-body text-muted lh-18">Operating hours vary by season. Typically, the attraction is open from 9:00 AM to 5:00 PM daily. Please check the official website for holiday hours and special closures.</div>
                            </div>
                        </div>
                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Is parking available on-site?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#attractionFaq">
                                <div class="accordion-body text-muted lh-18">Yes, ample parking is available for visitors. A daily vehicle pass or annual park pass may be required for entry depending on the specific location.</div>
                            </div>
                        </div>
                        <div class="accordion-item border rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Are pets allowed?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#attractionFaq">
                                <div class="accordion-body text-muted lh-18">Pets are welcome in designated outdoor areas but must be kept on a leash at all times. Please clean up after your pet and follow all posted guidelines.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Sidebar Information -->
            <div class="col-lg-4">
                
                <!-- Visitor Information Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 sticky-top auto-style-16">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4 auto-style-7">Visitor Information</h4>
                        
                        <ul class="list-unstyled mb-4">
                            <li class="d-flex align-items-center mb-3 text-muted">
                                <div class="icon-box bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3 auto-style-10">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark small">Address</div>
                                    <div>{{ $attraction->address ?? 'Main Street' }}</div>
                                    <div>{{ $attraction->city }}, {{ $attraction->state }} {{ $attraction->zip }}</div>
                                </div>
                            </li>
                            
                            @if($attraction->phone)
                            <li class="d-flex align-items-center mb-3 text-muted">
                                <div class="icon-box bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3 auto-style-10">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark small">Phone</div>
                                    <a href="tel:{{ $attraction->phone }}" class="text-muted text-decoration-none">{{ $attraction->phone }}</a>
                                </div>
                            </li>
                            @endif
                            
                            @if($attraction->website)
                            <li class="d-flex align-items-center mb-3 text-muted">
                                <div class="icon-box bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3 auto-style-10">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark small">Website</div>
                                    <a href="{{ $attraction->website }}" target="_blank" class="text-primary text-decoration-none text-truncate d-inline-block auto-style-17">Visit Official Site</a>
                                </div>
                            </li>
                            @endif
                        </ul>

                        @if($attraction->visitor_information)
                        <hr class="my-4 text-muted opacity-25">
                        <h6 class="fw-bold mb-3">Additional Details</h6>
                        <div class="text-muted small">
                            {!! $attraction->visitor_information !!}
                        </div>
                        @endif

                        <hr class="my-4 text-muted opacity-25">
                        <button class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm d-flex justify-content-center align-items-center">
                            <i class="fas fa-share-alt me-2"></i> Share Attraction
                        </button>
                    </div>
                </div>

            </div>
        </div>
        
        <!-- Full-Width Location & Map -->
        <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mt-4">
            <h3 class="fw-bold mb-4 auto-style-7">Location</h3>
            <p class="text-muted mb-4"><i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ $attraction->address }}, {{ $attraction->city }}, {{ $attraction->state }} {{ $attraction->zip }}</p>
            <div class="rounded-3 overflow-hidden bg-light auto-style-18">
                <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ urlencode(($attraction->address ?? 'Main Street') . ' ' . ($attraction->city ?? 'Michigan')) }}&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
            </div>
        </div>

    </div>
</section>

<!-- Nearby Places Sections -->
<section class="py-5 border-top">
    <div class="container py-3">
        
        <!-- Nearby Hotels -->
        <div class="mb-5 pb-4 border-bottom">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold mb-1 auto-style-7">Stay Nearby</h3>
                    <p class="text-muted mb-0">Great hotels near {{ $attraction->name }}</p>
                </div>
                <a href="{{ route('web.hotels.index') }}" class="btn btn-outline-primary rounded-pill px-4">View All Hotels</a>
            </div>
            <div class="row g-4">
                @forelse($nearbyHotels as $hotel)
                <div class="col-lg-4 col-md-6">
                    <x-hotel-card :hotel="$hotel" />
                </div>
                @empty
                <!-- Fallback Hotel Cards -->
                @for($i=1; $i<=3; $i++)
                <div class="col-lg-4 col-md-6">
                    <x-hotel-card :hotel="(object)[
                        'name' => 'Grand Hotel ' . $i,
                        'slug' => 'demo',
                        'city' => $attraction->city,
                        'description' => 'A wonderful place to stay during your visit.',
                        'starting_price' => '149'
                    ]" />
                </div>
                @endfor
                @endforelse
            </div>
        </div>

        <!-- Nearby Restaurants -->
        <div>
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold mb-1 auto-style-7">Dine Nearby</h3>
                    <p class="text-muted mb-0">Delicious restaurants near {{ $attraction->name }}</p>
                </div>
                <a href="{{ route('web.restaurants.index') }}" class="btn btn-outline-primary rounded-pill px-4">View All Restaurants</a>
            </div>
            <div class="row g-4">
                @forelse($nearbyRestaurants as $restaurant)
                <div class="col-lg-4 col-md-6">
                    <x-restaurant-card :restaurant="$restaurant" />
                </div>
                @empty
                <!-- Fallback Restaurant Cards -->
                @for($i=1; $i<=3; $i++)
                <div class="col-lg-4 col-md-6">
                    <x-restaurant-card :restaurant="(object)[
                        'name' => 'Local Dining ' . $i,
                        'slug' => 'demo',
                        'city' => $attraction->city,
                        'description' => 'A fantastic place to grab a bite.',
                        'starting_price' => '25'
                    ]" />
                </div>
                @endfor
                @endforelse
            </div>
        </div>

    </div>
</section>

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

@endsection

@section('webLayoutScript')
<script>
    const galleryImages = [
        "{{ $heroImage }}",
        "{{ asset('images/attraction_nature_1783508280642.png') }}",
        "{{ asset('storage/demo/michigan_lighthouse_1783683652511.png') }}",
        "{{ asset('storage/demo/michigan_sleeping_bear_1783683642640.png') }}",
        "{{ asset('storage/demo/michigan_hotel_pool_1783683632041.png') }}"
    ];
    let currentGalleryIndex = 0;

    function openCustomGallery(index = 0) {
        currentGalleryIndex = index;
        const lightbox = document.getElementById('customGalleryLightbox');
        if(lightbox) {
            lightbox.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Disable scroll
            updateLightbox();
            renderThumbnails();
        }
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
        if(imgEl) {
            imgEl.style.opacity = 0;
            setTimeout(() => {
                imgEl.src = galleryImages[currentGalleryIndex];
                imgEl.style.opacity = 1;
            }, 200);
        }
        
        const currentIdxEl = document.getElementById('lightbox-current-idx');
        const totalIdxEl = document.getElementById('lightbox-total-idx');
        
        if(currentIdxEl) currentIdxEl.innerText = currentGalleryIndex + 1;
        if(totalIdxEl) totalIdxEl.innerText = galleryImages.length;
        
        const thumbs = document.querySelectorAll('.lightbox-thumb');
        thumbs.forEach((t, i) => {
            if (i === currentGalleryIndex) t.classList.add('active');
            else t.classList.remove('active');
        });
    }

    function renderThumbnails() {
        const strip = document.getElementById('lightbox-thumbnails');
        if(strip) {
            strip.innerHTML = '';
            galleryImages.forEach((src, index) => {
                const img = document.createElement('img');
                img.src = src;
                img.className = 'lightbox-thumb ' + (index === currentGalleryIndex ? 'active' : '');
                img.onclick = () => { currentGalleryIndex = index; updateLightbox(); };
                strip.appendChild(img);
            });
        }
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const lightbox = document.getElementById('customGalleryLightbox');
        if (lightbox && lightbox.style.display === 'flex') {
            if (e.key === 'Escape') closeCustomGallery();
            if (e.key === 'ArrowRight') changeLightboxImage(1);
            if (e.key === 'ArrowLeft') changeLightboxImage(-1);
        }
    });
</script>
@endsection
