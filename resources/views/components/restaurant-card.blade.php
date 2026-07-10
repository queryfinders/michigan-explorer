@props([
    'restaurant',
    'featured' => false,
    'compact' => false
])

<div class="premium-hotel-card position-relative" style="cursor: pointer;" onclick="window.location.href='{{ route('web.restaurants.show', $restaurant->slug ?? 'demo') }}'">
    
    <!-- Image Wrapper -->
    <div class="hotel-img-wrapper position-relative" style="height: 240px; overflow: hidden;">
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
        
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="hotel-location text-muted small d-flex align-items-center gap-1">
                <i class="fas fa-map-marker-alt text-primary"></i> 
                {{ $restaurant->city ?? 'Michigan' }}
            </div>
            <div class="text-success small fw-bold"><i class="fas fa-door-open me-1"></i> Open Now</div>
        </div>
        
        <h3 class="hotel-title fw-bold text-heading mb-1" style="font-size: 1.25rem; font-family: var(--font-heading);">
            {{ $restaurant->name ?? 'Lakeside Prime Steakhouse' }}
        </h3>
        
        <div class="text-muted small mb-3 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
            {{ $restaurant->category->name ?? 'Italian' }} &bull; Fine Dining
        </div>
        
        <p class="hotel-desc text-muted mb-3" style="font-size: 0.95rem; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: all 0.3s ease;">
            {{ Str::limit($restaurant->description ?? 'Savor exquisite culinary masterpieces with breathtaking waterfront views and exceptional service.', 150) }}
        </p>
        
        <!-- Premium Amenities Row (Restaurant) -->
        <div class="hotel-amenities d-flex align-items-center gap-3 mb-4 mt-auto text-secondary" style="font-size: 0.9rem;">
            <span data-bs-toggle="tooltip" title="Outdoor Seating"><i class="fas fa-chair text-primary opacity-75"></i></span>
            <span data-bs-toggle="tooltip" title="Wine Bar"><i class="fas fa-wine-glass text-primary opacity-75"></i></span>
            <span data-bs-toggle="tooltip" title="Family Friendly"><i class="fas fa-child text-primary opacity-75"></i></span>
            <span data-bs-toggle="tooltip" title="Parking"><i class="fas fa-parking text-primary opacity-75"></i></span>
            <span class="ms-auto fw-bold text-primary" style="font-size: 0.8rem;">+3 MORE</span>
        </div>
        
        <!-- Footer -->
        <div class="hotel-card-footer pt-3 border-top d-flex flex-column gap-3">
            <div class="d-flex justify-content-between align-items-end">
                <div class="hotel-price text-heading fw-bold fs-6">
                    <span class="text-success">$$$</span> <span class="text-muted fw-normal" style="font-size: 0.8rem; margin-left: 5px;">Average $25-50</span>
                </div>
                <div class="fw-bold" style="font-size: 0.9rem;">
                    <i class="fas fa-star text-warning me-1"></i>4.8
                </div>
            </div>
            
            <div class="d-flex gap-2 w-100">
                <a href="{{ route('web.restaurants.show', $restaurant->slug ?? 'demo') }}" 
                   class="btn btn-outline-primary rounded-pill w-50 fw-bold shadow-sm d-flex align-items-center justify-content-center" 
                   style="font-size: 0.9rem;"
                   onclick="event.stopPropagation();">
                    View Details
                </a>
                
                <a href="{{ $restaurant->affiliate_url ?? '#' }}" 
                   class="btn btn-secondary hotel-book-btn rounded-pill w-50 fw-bold shadow-sm d-flex align-items-center justify-content-center" 
                   style="color: #fff; font-size: 0.9rem;"
                   onclick="event.stopPropagation();" 
                   target="_blank">
                    Reserve Table
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
