@props([
    'restaurant',
    'featured' => false,
    'compact' => false
])

@php
    // Determine if open now
    $isOpen = false;
    if (isset($restaurant->opening_hours) && is_array($restaurant->opening_hours)) {
        $today = strtolower(now()->timezone('America/Detroit')->format('l'));
        $currentTime = now()->timezone('America/Detroit')->format('H:i');
        
        $todayHours = $restaurant->opening_hours[$today] ?? null;
        if ($todayHours) {
            if (isset($todayHours['24_hours']) && $todayHours['24_hours']) {
                $isOpen = true;
            } elseif (!isset($todayHours['closed'])) {
                $openTime = $todayHours['open'] ?? '00:00';
                $closeTime = $todayHours['close'] ?? '23:59';
                
                // Handle cases where close time is past midnight (e.g. 02:00)
                if ($closeTime < $openTime) {
                    if ($currentTime >= $openTime || $currentTime <= $closeTime) {
                        $isOpen = true;
                    }
                } else {
                    if ($currentTime >= $openTime && $currentTime <= $closeTime) {
                        $isOpen = true;
                    }
                }
            }
        }
    }

    // Determine price symbols
    $priceValue = $restaurant->starting_price ?? 0;
    $priceSymbols = '$';
    if ($priceValue >= 100) $priceSymbols = '$$$$';
    elseif ($priceValue >= 50) $priceSymbols = '$$$';
    elseif ($priceValue >= 20) $priceSymbols = '$$';
@endphp

<div class="listing-card position-relative cursor-pointer" onclick="window.location.href='{{ route('web.restaurants.show', $restaurant->slug ?? 'demo') }}'">
    
    <!-- Image Wrapper -->
    <div class="listing-img-wrapper position-relative h-240px overflow-hidden">
        @php
            $imgUrl = asset('images/fine_dining_1783508270763.jpg');
            if (!empty($restaurant->featured_image)) {
                $imgUrl = str_starts_with($restaurant->featured_image, 'http') ? $restaurant->featured_image : asset($restaurant->featured_image);
            } elseif (!empty($restaurant->image)) {
                $imgUrl = str_starts_with($restaurant->image, 'http') ? $restaurant->image : asset('storage/' . $restaurant->image);
            }
        @endphp
        <img src="{{ $imgUrl }}" 
             alt="{{ $restaurant->name ?? 'Restaurant Image' }}" 
             loading="lazy"
             class="img-fluid object-fit-cover transition-transform-slow w-100 h-100">
             
        <!-- Badges -->
        <div class="position-absolute top-0 start-0 p-3">
            @if($featured)
                <x-premium-badge type="featured" text="Featured" />
            @endif
        </div>
        
    </div>
    
    <!-- Card Body -->
    <div class="listing-card-body p-4 d-flex flex-column flex-grow-1 bg-white">
        
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="listing-location text-muted small d-flex align-items-center gap-1">
                <i class="fas fa-map-marker-alt text-primary"></i> 
                {{ $restaurant->city ?? 'Michigan' }}
            </div>
            @if($isOpen)
                <div class="text-success small fw-bold"><i class="fas fa-door-open me-1"></i> Open Now</div>
            @else
                <div class="text-danger small fw-bold"><i class="fas fa-door-closed me-1"></i> Closed</div>
            @endif
        </div>
        
        <h3 class="listing-title fw-bold text-heading mb-1 fs-5 font-heading">
            {{ $restaurant->name ?? 'Lakeside Prime Steakhouse' }}
        </h3>
        
        <div class="text-muted small mb-3 fw-bold text-uppercase fs-xs letter-spacing-wide">
            {{ $restaurant->category->name ?? 'Dining' }} 
            @if(isset($restaurant->cuisines) && $restaurant->cuisines->count() > 0)
                &bull; {{ $restaurant->cuisines->first()->name }}
            @endif
        </div>
        
        <p class="listing-desc text-muted mb-3 text-truncate-2 lh-16 transition-base fs-095rem">
            {{ Str::limit(strip_tags($restaurant->description ?? 'Savor exquisite culinary masterpieces with breathtaking waterfront views and exceptional service.'), 150) }}
        </p>
        
        <!-- Premium Amenities Row (Restaurant) -->
        @if(isset($restaurant->features) && $restaurant->features instanceof \Illuminate\Support\Collection && $restaurant->features->count() > 0)
        <div class="listing-amenities d-flex align-items-center gap-3 mb-4 mt-auto text-secondary fs-sm">
            @foreach($restaurant->features->take(4) as $feature)
                <span data-bs-toggle="tooltip" title="{{ $feature->name }}"><i class="{{ $feature->icon_class ?? 'fas fa-star' }} text-primary opacity-75"></i></span>
            @endforeach
            @if($restaurant->features->count() > 4)
                <span class="ms-auto fw-bold text-primary fs-xs">+{{ $restaurant->features->count() - 4 }} MORE</span>
            @endif
        </div>
        @else
        <div class="listing-amenities d-flex align-items-center gap-3 mb-4 mt-auto text-secondary fs-sm">
            <span data-bs-toggle="tooltip" title="Standard Dining"><i class="fas fa-utensils text-primary opacity-75"></i></span>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="hotel-card-footer pt-3 border-top d-flex flex-column gap-3">
            <div class="d-flex justify-content-between align-items-end">
                <div class="hotel-price text-heading fw-bold fs-6">
                    <span class="text-success">{{ $priceSymbols }}</span> <span class="text-muted fw-normal fs-xs ms-1">Starts at ${{ number_format($priceValue) }}</span>
                </div>
            </div>
            
            <div class="d-flex gap-2 w-100 mt-1">
                <a href="{{ route('web.restaurants.show', $restaurant->slug ?? 'demo') }}" 
                   class="btn btn-outline-primary rounded-pill w-50 fw-bold shadow-sm d-flex align-items-center justify-content-center px-1 py-2 custom-hotel-card-btn"
                   onclick="event.stopPropagation();">
                    View Details
                </a>
                
                @if(isset($restaurant->affiliate_link_id) && $restaurant->affiliate_link_id)
                <a href="{{ route('affiliate.redirect', ['type' => 'restaurant', 'id' => $restaurant->id]) }}" 
                   class="btn btn-secondary hotel-book-btn rounded-pill w-50 fw-bold shadow-sm d-flex align-items-center justify-content-center text-white px-1 py-2 custom-hotel-card-btn"
                   onclick="event.stopPropagation();" 
                   target="_blank">
                    Reserve Table
                </a>
                @else
                <button class="btn btn-secondary rounded-pill w-50 fw-bold shadow-sm d-flex align-items-center justify-content-center text-white px-1 py-2 disabled custom-hotel-card-btn-disabled"
                   onclick="event.stopPropagation();" 
                   disabled>
                    Unavailable
                </button>
                @endif
            </div>
        </div>
    </div>
</div>


