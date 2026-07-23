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
            </div>
            <h1 class="hotel-header-title">{{ $restaurant->name }}</h1>
            <div class="hotel-header-location">
                <i class="fas fa-map-marker-alt text-amber"></i> 
                {{ !empty($restaurant->address) ? $restaurant->address . ', ' : '' }}{{ $restaurant->city ?? 'Traverse City' }}, {{ $restaurant->state ?? 'MI' }} {{ $restaurant->zip ?? '49684' }}
                <a href="#location-map" class="text-amber fw-bold ms-2 text-underline">Show on map</a>
            </div>
        </div>
        <div class="hotel-actions mt-3 mt-md-0 d-flex gap-2">
            <button class="btn btn-outline-secondary bg-white" onclick="shareCurrentPage('{{ addslashes($restaurant->name) }}')"><i class="fas fa-share-alt"></i> Share</button>
        </div>
    </div>

    <!-- 3. Image Gallery -->
    @php
        $galleryItems = isset($restaurant->images) && $restaurant->images instanceof \Illuminate\Support\Collection
            ? $restaurant->images
            : (isset($restaurant->images) ? collect($restaurant->images) : collect([]));
        $hasDynamicGallery = $galleryItems->count() > 0;
        $featuredSrc = !empty($restaurant->featured_image) && (is_object($restaurant) && property_exists($restaurant, 'slug') ? $restaurant->slug !== 'demo' : true)
            ? asset($restaurant->featured_image)
            : asset('images/fine_dining_1783508270763.png');
        
        $allGalleryImages = [];
        $allGalleryImages[] = ['src' => $featuredSrc, 'alt' => $restaurant->featured_image_alt ?? $restaurant->name ?? 'Restaurant'];
        if ($hasDynamicGallery) {
            foreach ($galleryItems as $img) {
                $allGalleryImages[] = ['src' => asset($img->image), 'alt' => $img->alt_text ?? $restaurant->name ?? 'Restaurant Gallery'];
            }
        } else {
            // Static demo thumbnails as fallback
            $allGalleryImages[] = ['src' => asset('storage/demo/michigan_hotel_room_1_1783683598842.png'), 'alt' => 'Dining Area'];
            $allGalleryImages[] = ['src' => asset('storage/demo/michigan_hotel_room_2_1783683609409.png'), 'alt' => 'Food Item'];
            $allGalleryImages[] = ['src' => asset('storage/demo/michigan_hotel_lobby_1783683621508.png'), 'alt' => 'Interior'];
            $allGalleryImages[] = ['src' => asset('storage/demo/michigan_hotel_pool_1783683632041.png'), 'alt' => 'Entrance'];
        }
        $extraCount = count($allGalleryImages) - 5;
        
        $videoUrl = $restaurant->video;
        $embedUrl = '';
        if ($videoUrl) {
            if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                $embedUrl = str_replace('watch?v=', 'embed/', $videoUrl);
            } elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                $embedUrl = str_replace('youtu.be/', 'youtube.com/embed/', $videoUrl);
            } elseif (strpos($videoUrl, 'vimeo.com/') !== false) {
                // Convert vimeo.com/12345 to player.vimeo.com/video/12345
                $videoId = substr(parse_url($videoUrl, PHP_URL_PATH), 1);
                $embedUrl = "https://player.vimeo.com/video/" . $videoId;
            } else {
                $embedUrl = $videoUrl;
            }
        }
    @endphp
    <div class="gallery-grid mb-4" onclick="openCustomGallery()">
        {{-- Main featured image --}}
        <div class="gallery-item main-img position-relative">
            <img src="{{ $allGalleryImages[0]['src'] }}" alt="{{ $allGalleryImages[0]['alt'] }}">
            @if($embedUrl)
            <div class="position-absolute top-50 start-50 translate-middle" style="pointer-events: none; z-index: 10;">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 60px; height: 60px; opacity: 0.9;">
                    <i class="fas fa-play text-primary fs-4 ms-1"></i>
                </div>
            </div>
            @endif
        </div>
        {{-- Thumbnails: show up to 4 --}}
        @for($gi = 1; $gi <= min(4, count($allGalleryImages) - 1); $gi++)
        <div class="gallery-item">
            <img src="{{ $allGalleryImages[$gi]['src'] }}" alt="{{ $allGalleryImages[$gi]['alt'] }}">
            @if($gi === 4 && $extraCount > 0)
                <div class="gallery-overlay-count">+{{ $extraCount }}</div>
            @endif
        </div>
        @endfor
    </div>
    
    <!-- Quick Facts -->
    <div class="quick-facts-row">
        <div class="quick-fact-item"><i class="fas fa-utensils"></i> {{ $restaurant->category->name ?? 'Fine Dining' }}</div>
        <div class="quick-fact-item"><i class="fas fa-door-open"></i> Open Now</div>
        <div class="quick-fact-item"><i class="fas fa-dollar-sign"></i>$$$</div>
        <div class="quick-fact-item"><i class="fas fa-child"></i> Family Friendly</div>
        <div class="quick-fact-item"><i class="fas fa-wine-glass"></i> Full Bar</div>
    </div>

    @php
        $hours = is_array($restaurant->opening_hours) ? $restaurant->opening_hours : json_decode($restaurant->opening_hours ?? '{}', true);
        $groupedHours = [];
        if (is_array($hours) && !empty($hours)) {
            $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $currentGroup = null;
            
            foreach ($daysOfWeek as $day) {
                $data = $hours[$day] ?? null;
                if (!$data) continue;
                
                $formattedTime = '';
                if (!empty($data['closed'])) {
                    $formattedTime = 'Closed';
                } elseif (!empty($data['24_hours'])) {
                    $formattedTime = 'Open 24 Hours';
                } else {
                    $open = !empty($data['open']) ? date('h:i A', strtotime($data['open'])) : '';
                    $close = !empty($data['close']) ? date('h:i A', strtotime($data['close'])) : '';
                    $formattedTime = ($open && $close) ? "$open – $close" : '';
                }
                
                if (!$currentGroup) {
                    $currentGroup = ['start_day' => $day, 'end_day' => $day, 'time' => $formattedTime];
                } elseif ($currentGroup['time'] === $formattedTime) {
                    $currentGroup['end_day'] = $day;
                } else {
                    $groupedHours[] = $currentGroup;
                    $currentGroup = ['start_day' => $day, 'end_day' => $day, 'time' => $formattedTime];
                }
            }
            if ($currentGroup) {
                $groupedHours[] = $currentGroup;
            }
        }
    @endphp

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
            @if((isset($restaurant->cuisines) && $restaurant->cuisines instanceof \Illuminate\Support\Collection && $restaurant->cuisines->count() > 0) || (isset($restaurant->features) && $restaurant->features instanceof \Illuminate\Support\Collection && $restaurant->features->count() > 0))
            <div class="content-card">
                <h3 class="mb-4">Cuisine & Features</h3>
                <div class="amenities-grid-premium">
                    @if(isset($restaurant->cuisines) && $restaurant->cuisines instanceof \Illuminate\Support\Collection)
                        @foreach($restaurant->cuisines as $cuisine)
                        <div class="amenity-card"><i class="fas fa-utensils"></i><span>{{ $cuisine->name }}</span></div>
                        @endforeach
                    @endif
                    @if(isset($restaurant->features) && $restaurant->features instanceof \Illuminate\Support\Collection)
                        @foreach($restaurant->features as $feature)
                        <div class="amenity-card"><i class="{{ $feature->icon_class ?? 'fas fa-star' }}"></i><span>{{ $feature->name }}</span></div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endif

            @if(count($groupedHours) > 0)
            <div class="content-card">
                <h3 class="mb-4">Opening Hours</h3>
                <div class="info-grid">
                    @foreach($groupedHours as $group)
                        @if($group['time'])
                        <div class="info-item">
                            <h6 class="text-uppercase">{{ $group['start_day'] }}{{ $group['start_day'] !== $group['end_day'] ? ' - ' . $group['end_day'] : '' }}</h6>
                            <p>{{ $group['time'] }}</p>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

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

            @if($restaurant->faqs && $restaurant->faqs->count() > 0)
            <!-- FAQ Section -->
            <div class="content-card">
                <h3>Frequently Asked Questions</h3>
                <div class="accordion accordion-flush mt-3" id="restaurantFaq">
                    @foreach($restaurant->faqs as $index => $faq)
                    <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $index }}">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="faq{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#restaurantFaq">
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
                        <div class="text-muted small fw-bold text-uppercase mb-1">Average Cost</div>
                        <div class="sidebar-price">${{ $restaurant->starting_price ?? '45' }} <span>/person</span></div>
                    </div>
                </div>
                
                <hr class="text-muted opacity-25">

                @if(count($groupedHours) > 0)
                <div class="mb-4">
                    <div class="text-muted small fw-bold text-uppercase mb-2"><i class="far fa-clock me-1"></i> Opening Hours</div>
                    @foreach($groupedHours as $group)
                        @if($group['time'])
                        <div class="d-flex justify-content-between mb-1 small">
                            <span class="text-capitalize">{{ substr($group['start_day'], 0, 3) }}{{ $group['start_day'] !== $group['end_day'] ? ' – ' . substr($group['end_day'], 0, 3) : '' }}</span>
                            <span class="fw-semibold">{{ $group['time'] }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
                <hr class="text-muted opacity-25">
                @endif

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
            <p class="text-muted mb-3"><i class="fas fa-map-marker-alt text-primary me-2"></i> {{ $restaurant->address ?? '' }}, {{ $restaurant->city ?? '' }} {{ $restaurant->zip ?? '' }}</p>
            <div class="map-container bg-light h-500px">
                @if(!empty($restaurant->map_iframe))
                    @if(str_contains($restaurant->map_iframe, '<iframe'))
                        {!! $restaurant->map_iframe !!}
                    @else
                        <iframe width="100%" height="100%" frameborder="0" style="border:0;" src="{{ $restaurant->map_iframe }}"></iframe>
                    @endif
                @else
                    <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ urlencode(($restaurant->address ?? '') . ' ' . ($restaurant->city ?? '') . ' ' . ($restaurant->zip ?? '')) }}&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
                @endif
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <h6 class="fw-bold mb-1">Contact Info</h6>
                    <p class="mb-0 text-muted small"><i class="fas fa-phone-alt me-2"></i> {{ $restaurant->phone ?? '(555) 123-4567' }}</p>
                </div>
                <a href="https://maps.google.com/?q={{ urlencode(($restaurant->address ?? '') . ' ' . ($restaurant->city ?? '') . ' ' . ($restaurant->zip ?? '')) }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4">Get Directions</a>
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
    
    <div class="lightbox-image-container" style="display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;">
        <iframe id="lightbox-main-video" src="" frameborder="0" allow="autoplay; fullscreen" allowfullscreen style="display:none; width: 80vw; height: 80vh; max-width: 1200px;"></iframe>
        <img id="lightbox-main-img" src="" alt="Gallery Image">
    </div>
    
    <button class="lightbox-nav lightbox-next" onclick="changeLightboxImage(1)"><i class="fas fa-chevron-right"></i></button>
    
    <div class="lightbox-thumbnail-strip" id="lightbox-thumbnails">
        <!-- Thumbnails injected via JS -->
    </div>
</div>

<script>
    const restaurantVideoUrl = "{!! $embedUrl !!}";
    const galleryImages = [
        @foreach($allGalleryImages as $imgItem)
        "{{ $imgItem['src'] }}",
        @endforeach
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
        const videoEl = document.getElementById('lightbox-main-video');
        if (videoEl) videoEl.src = ''; // stop video
    }

    function changeLightboxImage(direction) {
        currentGalleryIndex += direction;
        if (currentGalleryIndex < 0) currentGalleryIndex = galleryImages.length - 1;
        if (currentGalleryIndex >= galleryImages.length) currentGalleryIndex = 0;
        updateLightbox();
    }

    function updateLightbox() {
        const imgEl = document.getElementById('lightbox-main-img');
        const videoEl = document.getElementById('lightbox-main-video');
        
        imgEl.style.opacity = 0;
        
        if (currentGalleryIndex === 0 && restaurantVideoUrl) {
            imgEl.style.display = 'none';
            videoEl.style.display = 'block';
            videoEl.src = restaurantVideoUrl + (restaurantVideoUrl.includes('?') ? '&' : '?') + 'autoplay=1&mute=1';
            imgEl.style.opacity = 1;
        } else {
            videoEl.style.display = 'none';
            videoEl.src = '';
            imgEl.style.display = 'block';
            setTimeout(() => {
                imgEl.src = galleryImages[currentGalleryIndex];
                imgEl.style.opacity = 1;
            }, 200);
        }
        
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

    // Auto-open video on page load if present
    document.addEventListener('DOMContentLoaded', function() {
        if (restaurantVideoUrl) {
            setTimeout(() => {
                openCustomGallery(0);
            }, 500); // slight delay for smooth UX
        }
    });
</script>

<style>
  .map-container iframe {
    width: 100% !important;
    height: 100% !important;
    border: 0 !important;
  }
</style>

@endsection
