@props([
    'hotel',
    'featured' => false,
    'compact' => false
])

<div class="premium-hotel-card position-relative" style="cursor: pointer;" onclick="window.location.href='{{ route('web.hotels.show', $hotel->slug ?? 'demo') }}'">
    
    <!-- Image Wrapper -->
    <div class="hotel-img-wrapper position-relative" style="height: 240px; overflow: hidden;">
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
             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
             
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
    <div class="hotel-card-body p-4 d-flex flex-column flex-grow-1 bg-white">
        
        <div class="hotel-location text-muted small mb-2 d-flex align-items-center gap-1">
            <i class="fas fa-map-marker-alt text-primary"></i> 
            {{ $hotel->city ?? 'Michigan' }}
        </div>
        
        <h3 class="hotel-title fw-bold text-heading mb-3" style="font-size: 1.25rem; font-family: var(--font-heading);">
            {{ $hotel->name ?? 'Luxury Resort & Spa' }}
        </h3>
        
        <p class="hotel-desc text-muted mb-4" style="font-size: 0.95rem; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: all 0.3s ease;">
            {{ Str::limit($hotel->description ?? 'Experience true comfort and luxury in this beautifully appointed property in the heart of Michigan.', 150) }}
        </p>
        
        <!-- Premium Amenities Row -->
        <div class="hotel-amenities d-flex align-items-center gap-3 mb-4 mt-auto text-secondary" style="font-size: 0.9rem;">
            <span data-bs-toggle="tooltip" title="Free WiFi"><i class="fas fa-wifi text-primary opacity-75"></i></span>
            <span data-bs-toggle="tooltip" title="Swimming Pool"><i class="fas fa-swimming-pool text-primary opacity-75"></i></span>
            <span data-bs-toggle="tooltip" title="Free Parking"><i class="fas fa-parking text-primary opacity-75"></i></span>
            <span data-bs-toggle="tooltip" title="Restaurant"><i class="fas fa-utensils text-primary opacity-75"></i></span>
            <span class="ms-auto fw-bold text-primary" style="font-size: 0.8rem;">+3 MORE</span>
        </div>
        
        <!-- Footer -->
        <div class="hotel-card-footer pt-3 border-top d-flex flex-column gap-3">
            <div class="hotel-price text-primary fw-bold fs-5">
                ${{ $hotel->starting_price ?? '249' }}<span class="text-muted fw-normal" style="font-size: 0.85rem;">/night</span>
            </div>
            
            <div class="d-flex gap-2 w-100">
                <a href="{{ route('web.hotels.show', $hotel->slug ?? 'demo') }}" 
                   class="btn btn-outline-primary rounded-pill w-50 fw-bold shadow-sm d-flex align-items-center justify-content-center" 
                   style="font-size: 0.9rem;"
                   onclick="event.stopPropagation();">
                    View Details
                </a>
                
                <a href="{{ $hotel->affiliate_url ?? '#' }}" 
                   class="btn btn-secondary hotel-book-btn rounded-pill w-50 fw-bold shadow-sm d-flex align-items-center justify-content-center" 
                   style="color: #fff; font-size: 0.9rem;"
                   onclick="event.stopPropagation();" 
                   target="_blank">
                    Book Now
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .premium-hotel-card {
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(0,0,0,0.05);
        height: 100%;
        overflow: hidden;
    }
    .premium-hotel-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }
    .premium-hotel-card:hover .hotel-img-wrapper img {
        transform: scale(1.08);
    }
    /* Reveal full text on hover */
    .premium-hotel-card:hover .hotel-desc {
        display: block;
        height: auto;
    }
    .hotel-img-wrapper img {
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .hotel-book-btn {
        background-color: var(--bs-secondary);
        border-color: var(--bs-secondary);
        color: white !important;
        transition: all 0.3s ease;
    }
    .hotel-book-btn:hover {
        background-color: #e0961b; /* darker amber */
        border-color: #e0961b;
        color: white !important;
    }
</style>
