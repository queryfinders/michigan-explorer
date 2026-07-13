@props([
    'hotel',
    'featured' => false,
    'compact' => false
])

<div class="listing-card position-relative cursor-pointer" onclick="window.location.href='{{ route('web.hotels.show', $hotel->slug ?? 'demo') }}'">
    
    <!-- Image Wrapper -->
    <div class="listing-img-wrapper position-relative h-240px overflow-hidden">
        @php
            $imgUrl = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=800';
            if (!empty($hotel->featured_image)) {
                $imgUrl = str_starts_with($hotel->featured_image, 'http') ? $hotel->featured_image : asset($hotel->featured_image);
            } elseif (!empty($hotel->image)) {
                $imgUrl = str_starts_with($hotel->image, 'http') ? $hotel->image : asset('storage/' . $hotel->image);
            }
        @endphp
        <img src="{{ $imgUrl }}" 
             alt="{{ $hotel->name ?? 'Hotel Image' }}" 
             class="img-fluid object-fit-cover transition-transform-slow w-100 h-100">
             
        <!-- Badges -->
        <div class="position-absolute top-0 start-0 p-3">
            @if($featured)
                <x-premium-badge type="featured" text="Featured" />
            @endif
        </div>
        
        <div class="position-absolute bottom-0 end-0 p-3">
            <x-premium-badge type="rating" text="4.8" />
        </div>
    </div>
    
    <!-- Card Body -->
    <div class="listing-card-body p-4 d-flex flex-column flex-grow-1 bg-white">
        
        <div class="listing-location text-muted small mb-2 d-flex align-items-center gap-1">
            <i class="fas fa-map-marker-alt text-primary"></i> 
            {{ $hotel->city ?? 'Michigan' }}
        </div>
        
        <h3 class="listing-title fw-bold text-heading mb-3 fs-5 font-heading">
            {{ $hotel->name ?? 'Luxury Resort & Spa' }}
        </h3>
        
        <p class="listing-desc text-muted mb-4 text-truncate-2 lh-16 transition-base fs-095rem">
            {{ Str::limit($hotel->description ?? 'Experience true comfort and luxury in this beautifully appointed property in the heart of Michigan.', 150) }}
        </p>
        
        <!-- Premium Amenities Row -->
        <div class="listing-amenities d-flex align-items-center gap-3 mb-4 mt-auto text-secondary fs-sm">
            <span data-bs-toggle="tooltip" title="Free WiFi"><i class="fas fa-wifi text-primary opacity-75"></i></span>
            <span data-bs-toggle="tooltip" title="Swimming Pool"><i class="fas fa-swimming-pool text-primary opacity-75"></i></span>
            <span data-bs-toggle="tooltip" title="Free Parking"><i class="fas fa-parking text-primary opacity-75"></i></span>
            <span data-bs-toggle="tooltip" title="Restaurant"><i class="fas fa-utensils text-primary opacity-75"></i></span>
            <span class="ms-auto fw-bold text-primary fs-xs">+3 MORE</span>
        </div>
        
        <!-- Footer -->
        <div class="hotel-card-footer pt-3 border-top d-flex flex-column gap-3">
            <div class="hotel-price text-primary fw-bold fs-5">
                ${{ $hotel->starting_price ?? '249' }}<span class="text-muted fw-normal fs-085rem">/night</span>
            </div>
            
            <div class="d-flex gap-2 w-100">
                <a href="{{ route('web.hotels.show', $hotel->slug ?? 'demo') }}" 
                   class="btn btn-outline-primary rounded-pill w-50 fw-bold shadow-sm d-flex align-items-center justify-content-center fs-sm"
                   onclick="event.stopPropagation();">
                    View Details
                </a>
                
                <a href="{{ $hotel->affiliate_url ?? '#' }}" 
                   class="btn btn-secondary hotel-book-btn rounded-pill w-50 fw-bold shadow-sm d-flex align-items-center justify-content-center text-white fs-sm"
                   onclick="event.stopPropagation();" 
                   target="_blank">
                    Book Now
                </a>
            </div>
        </div>
    </div>
</div>


