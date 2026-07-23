@props([
    'restaurant',
    'featured' => false,
    'compact' => false
])

<div class="listing-card position-relative cursor-pointer" onclick="window.location.href='{{ route('web.restaurants.show', $restaurant->slug ?? 'demo') }}'">
    
    <!-- Image Wrapper -->
    <div class="listing-img-wrapper position-relative h-240px overflow-hidden">
        @php
            $imgUrl = asset('images/fine_dining_1783508270763.png');
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
            <div class="text-success small fw-bold"><i class="fas fa-door-open me-1"></i> Open Now</div>
        </div>
        
        <h3 class="listing-title fw-bold text-heading mb-1 fs-5 font-heading">
            {{ $restaurant->name ?? 'Lakeside Prime Steakhouse' }}
        </h3>
        
        <div class="text-muted small mb-3 fw-bold text-uppercase fs-xs letter-spacing-wide">
            {{ $restaurant->category->name ?? 'Italian' }} &bull; Fine Dining
        </div>
        
        <p class="listing-desc text-muted mb-3 text-truncate-2 lh-16 transition-base fs-095rem">
            {{ Str::limit($restaurant->description ?? 'Savor exquisite culinary masterpieces with breathtaking waterfront views and exceptional service.', 150) }}
        </p>
        
        <!-- Premium Amenities Row (Restaurant) -->
        <div class="listing-amenities d-flex align-items-center gap-3 mb-4 mt-auto text-secondary fs-sm">
            <span data-bs-toggle="tooltip" title="Outdoor Seating"><i class="fas fa-chair text-primary opacity-75"></i></span>
            <span data-bs-toggle="tooltip" title="Wine Bar"><i class="fas fa-wine-glass text-primary opacity-75"></i></span>
            <span data-bs-toggle="tooltip" title="Family Friendly"><i class="fas fa-child text-primary opacity-75"></i></span>
            <span data-bs-toggle="tooltip" title="Parking"><i class="fas fa-parking text-primary opacity-75"></i></span>
            <span class="ms-auto fw-bold text-primary fs-xs">+3 MORE</span>
        </div>
        
        <!-- Footer -->
        <div class="hotel-card-footer pt-3 border-top d-flex flex-column gap-3">
            <div class="d-flex justify-content-between align-items-end">
                <div class="hotel-price text-heading fw-bold fs-6">
                    <span class="text-success">$$$</span> <span class="text-muted fw-normal fs-xs ms-1">Average $25-50</span>
                </div>
                <div class="fw-bold fs-sm">
                    <i class="fas fa-star text-warning me-1"></i>4.8
                </div>
            </div>
            
            <div class="d-flex gap-2 w-100 mt-1">
                <a href="{{ route('web.restaurants.show', $restaurant->slug ?? 'demo') }}" 
                   class="btn btn-outline-primary rounded-pill w-50 fw-bold shadow-sm d-flex align-items-center justify-content-center px-1 py-2"
                   style="font-size: 0.8rem;"
                   onclick="event.stopPropagation();">
                    View Details
                </a>
                
                <a href="{{ $restaurant->affiliate_url ?? '#' }}" 
                   class="btn btn-secondary hotel-book-btn rounded-pill w-50 fw-bold shadow-sm d-flex align-items-center justify-content-center text-white px-1 py-2"
                   style="font-size: 0.8rem;"
                   onclick="event.stopPropagation();" 
                   target="_blank">
                    Reserve Table
                </a>
            </div>
        </div>
    </div>
</div>


