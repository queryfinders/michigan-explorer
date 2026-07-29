@extends('web.layout.app_layout')

@php
    $metaTitle = $attraction->meta_title ?? $attraction->name . ' - Michigan Explorer';
    $metaDescription = $attraction->meta_description ?? Str::limit(strip_tags($attraction->description), 160);
    $heroImage = $attraction->featured_image ? asset($attraction->featured_image) : asset('images/attraction_nature_1783508280642.jpg');
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
<section class="hotel-detail-hero position-relative" style="height: 500px; background-image: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.5)), url('{{ $heroImage }}'); background-size: cover; background-position: center; background-attachment: fixed;">
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
                        <div class="col-md-8 position-relative">
                            @if(!empty($attraction->video))
                                <div class="video-wrapper-premium w-100 h-100 position-relative" style="min-height: 350px;">
                                    <div class="video-loading-spinner" id="videoSpinnerAttractions">
                                        <div class="spinner-border text-white" role="status" style="width: 1.5rem; height: 1.5rem;">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    @php
                                        $isYoutube = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $attraction->video, $matches);
                                        $youtubeId = $isYoutube ? $matches[1] : null;
                                    @endphp
                                    @if($isYoutube)
                                        <iframe class="w-100 h-100 rounded-3 shadow-sm" style="min-height:350px;" src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&mute=1&loop=1&playlist={{ $youtubeId }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen onload="document.getElementById('videoSpinnerAttractions').style.display='none'"></iframe>
                                    @else
                                        <video class="w-100 h-100 object-fit-cover rounded-3 shadow-sm" controls autoplay muted loop playsinline style="object-fit: cover; min-height: 350px;"
                                               onplay="document.getElementById('videoSpinnerAttractions').style.display='none'"
                                               onplaying="document.getElementById('videoSpinnerAttractions').style.display='none'"
                                               onwaiting="document.getElementById('videoSpinnerAttractions').style.display='flex'"
                                               oncanplay="document.getElementById('videoSpinnerAttractions').style.display='none'">
                                            <source src="{{ asset($attraction->video) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                    
                                    {{-- Fullscreen button overlay --}}
                                    <div class="position-absolute bottom-0 end-0 m-3" style="z-index: 11;">
                                        <button class="btn btn-sm btn-dark bg-opacity-75 rounded-pill px-3 text-white border-0" onclick="openCustomGallery(0)">
                                            <i class="fas fa-expand-arrows-alt me-1"></i> View Video Gallery
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div onclick="openCustomGallery(0)" class="auto-style-13 cursor-pointer h-100">
                                    <img src="{{ $heroImage }}" class="img-fluid rounded-3 w-100 h-100 object-fit-cover shadow-sm transition-hover" alt="Gallery 1" class="auto-style-14">
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="row g-2 h-100">
                                @if($attraction->images->count() > 0)
                                    <div class="col-12 h-50 cursor-pointer position-relative" onclick="openCustomGallery({{ !empty($attraction->video) ? 1 : 0 }})">
                                        <img src="{{ asset($attraction->images[0]->image) }}" class="img-fluid rounded-3 w-100 h-100 object-fit-cover shadow-sm transition-hover" alt="{{ $attraction->images[0]->alt_text ?? 'Gallery 1' }}">
                                    </div>
                                    @if($attraction->images->count() > 1)
                                    <div class="col-12 h-50 cursor-pointer position-relative" onclick="openCustomGallery({{ !empty($attraction->video) ? 2 : 1 }})">
                                        <img src="{{ asset($attraction->images[1]->image) }}" class="img-fluid rounded-3 w-100 h-100 object-fit-cover shadow-sm transition-hover" alt="{{ $attraction->images[1]->alt_text ?? 'Gallery 2' }}">
                                        @if($attraction->images->count() > 2)
                                        <div class="position-absolute top-0 start-0 w-100 h-100 rounded-3 d-flex justify-content-center align-items-center text-white fw-bold fs-4 transition-hover" style="background: rgba(0,0,0,0.4);">
                                            +{{ $attraction->images->count() - 2 }}
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                @else
                                    <div class="col-12 h-50 cursor-pointer" onclick="openCustomGallery({{ !empty($attraction->video) ? 1 : 0 }})" class="auto-style-13">
                                        <img src="{{ $heroImage }}" class="img-fluid rounded-3 w-100 h-100 object-fit-cover shadow-sm transition-hover" alt="Gallery">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                @if($attraction->faqs && $attraction->faqs->count() > 0)
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4 auto-style-7">Frequently Asked Questions</h3>
                    <div class="accordion accordion-flush" id="attractionFaq">
                        @foreach($attraction->faqs as $index => $faq)
                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="faq{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#attractionFaq">
                                <div class="accordion-body text-muted lh-18">{!! nl2br(e($faq->answer)) !!}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Right Column: Sidebar Information -->
            <div class="col-lg-4">
                
                <!-- Visitor Information Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 sticky-top" style="top: 100px; z-index: 10;">
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
                        <!-- <hr class="my-4 text-muted opacity-25"> -->
                        <!-- <h6 class="fw-bold mb-3">Additional Details</h6>
                        <div class="text-muted small">
                            {!! $attraction->visitor_information !!}
                        </div> -->
                        @endif

                        <hr class="my-4 text-muted opacity-25">
                        <button class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm d-flex justify-content-center align-items-center" onclick="shareCurrentPage('{{ addslashes($attraction->name) }}')">
                            <i class="fas fa-share-alt me-2"></i> Share Attraction
                        </button>
                    </div>
                </div>

            </div>
        </div>
        
          <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-5" id="location-map">
              <h3 class="fw-bold mb-4">Location</h3>
              <p class="text-muted mb-3"><i class="fas fa-map-marker-alt text-primary me-2"></i> {{ $attraction->address ?? 'Main Street' }}, {{ $attraction->city ?? '' }} {{ $attraction->zip ?? '' }}</p>
              
              <div class="rounded-3 overflow-hidden bg-light w-100 map-wrapper" style="height: 400px;">
                <style>
                    .map-wrapper iframe {
                        width: 100% !important;
                        height: 100% !important;
                        border: 0;
                    }
                </style>
                @if(!empty($attraction->map_iframe))
                    @if(str_contains($attraction->map_iframe, '<iframe'))
                        {!! $attraction->map_iframe !!}
                    @else
                        <iframe width="100%" height="100%" frameborder="0" style="border:0;" src="{{ $attraction->map_iframe }}"></iframe>
                    @endif
                @else
                    <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ urlencode(($attraction->address ?? 'Main Street') . ' ' . ($attraction->city ?? 'Michigan')) }}&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
                @endif
              </div>

              <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-4 pt-3 border-top gap-3">
                  <div>
                      <h6 class="fw-bold mb-1">Contact Info</h6>
                      <p class="mb-0 text-muted small"><i class="fas fa-phone-alt me-2 text-primary"></i> {{ $attraction->phone ?? '(555) 123-4567' }}</p>
                  </div>
                  <a href="https://maps.google.com/?q={{ urlencode(($attraction->address ?? '') . ' ' . ($attraction->city ?? '') . ' ' . ($attraction->zip ?? '')) }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center">
                      Get Directions <i class="fas fa-external-link-alt ms-2"></i>
                  </a>
              </div>
          </div>

    </div>
</section>

<!-- Nearby Places Sections -->
@if((isset($nearbyHotels) && count($nearbyHotels) > 0) || (isset($nearbyRestaurants) && count($nearbyRestaurants) > 0))
<section class="py-5 border-top">
    <div class="container py-3">
        
        <!-- Nearby Hotels -->
        @if(isset($nearbyHotels) && count($nearbyHotels) > 0)
        <div class="mb-5 pb-4 border-bottom">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold mb-1 auto-style-7">Stay Nearby</h3>
                    <p class="text-muted mb-0">Great hotels near {{ $attraction->name }}</p>
                </div>
                <a href="{{ route('web.hotels.index') }}" class="btn btn-outline-primary rounded-pill px-4">View All Hotels</a>
            </div>
            <div class="row g-4">
                @foreach($nearbyHotels as $hotel)
                <div class="col-lg-4 col-md-6">
                    <x-hotel-card :hotel="$hotel" />
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Nearby Restaurants -->
        @if(isset($nearbyRestaurants) && count($nearbyRestaurants) > 0)
        <div>
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold mb-1 auto-style-7">Dine Nearby</h3>
                    <p class="text-muted mb-0">Delicious restaurants near {{ $attraction->name }}</p>
                </div>
                <a href="{{ route('web.restaurants.index') }}" class="btn btn-outline-primary rounded-pill px-4">View All Restaurants</a>
            </div>
            <div class="row g-4">
                @foreach($nearbyRestaurants as $restaurant)
                <div class="col-lg-4 col-md-6">
                    <x-restaurant-card :restaurant="$restaurant" />
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>
@endif

{{-- Attraction Detail Promotion Banner --}}
<x-promo-banner :promotion="$detailPromotion ?? null" />

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

@endsection

@section('webLayoutScript')
<script>
    const galleryItemsList = [
        @if(!empty($attraction->video))
            @php
                $isYoutube = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $attraction->video, $matches);
                $youtubeId = $isYoutube ? $matches[1] : null;
            @endphp
            @if($isYoutube)
                { type: 'youtube', src: "https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&mute=1" },
            @else
                { type: 'video', src: "{{ asset($attraction->video) }}" },
            @endif
        @endif
        @if($attraction->images && $attraction->images->count() > 0)
            @foreach($attraction->images as $img)
                { type: 'image', src: "{{ asset($img->image) }}" },
            @endforeach
        @else
            { type: 'image', src: "{{ $heroImage }}" },
        @endif
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
        // Pause any playing lightbox video or remove youtube iframe
        const videoEl = document.getElementById('lightbox-main-video');
        if (videoEl) videoEl.pause();
        const existingIframe = document.getElementById('lightbox-main-iframe');
        if (existingIframe) existingIframe.remove();
        
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
        const container = document.querySelector('#customGalleryLightbox .lightbox-image-container');
        
        // Remove existing YouTube iframe if any
        const existingIframe = document.getElementById('lightbox-main-iframe');
        if (existingIframe) existingIframe.remove();
        
        if (videoEl) {
            videoEl.pause();
            videoEl.style.display = 'none';
        }
        if (imgEl) {
            imgEl.style.display = 'none';
        }
        
        // Reset container defaults
        container.style.maxWidth = '85%';
        container.style.width = 'auto';
        
        if (item.type === 'video') {
            container.style.maxWidth = '95%';
            container.style.width = '1000px';
            if (videoEl) {
                videoEl.src = item.src;
                videoEl.style.display = 'block';
                videoEl.style.width = '100%';
                videoEl.style.maxWidth = '1000px';
                videoEl.style.height = 'auto';
                videoEl.style.maxHeight = '70vh';
                videoEl.load();
                videoEl.play();
            }
        } else if (item.type === 'youtube') {
            container.style.maxWidth = '95%';
            container.style.width = '1000px';
            const iframe = document.createElement('iframe');
            iframe.id = 'lightbox-main-iframe';
            iframe.src = item.src;
            iframe.className = 'rounded';
            iframe.style.width = '100%';
            iframe.style.maxWidth = '1000px';
            iframe.style.height = '65vh';
            iframe.style.border = 'none';
            container.appendChild(iframe);
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
        
        const currentIdxEl = document.getElementById('lightbox-current-idx');
        const totalIdxEl = document.getElementById('lightbox-total-idx');
        
        if(currentIdxEl) currentIdxEl.innerText = currentGalleryIndex + 1;
        if(totalIdxEl) totalIdxEl.innerText = galleryItemsList.length;
        
        const thumbs = document.querySelectorAll('.lightbox-thumb-container');
        thumbs.forEach((t, i) => {
            if (i === currentGalleryIndex) t.classList.add('active');
            else t.classList.remove('active');
        });
    }

    function renderThumbnails() {
        const strip = document.getElementById('lightbox-thumbnails');
        if(strip) {
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
