@props([
    'attraction',
    'featured' => false,
    'compact' => false
])

<div class="premium-hotel-card position-relative" style="cursor: pointer;" onclick="window.location.href='{{ route('web.attractions.show', $attraction->slug ?? 'demo') }}'">
    
    <!-- Image Wrapper -->
    <div class="hotel-img-wrapper position-relative" style="height: 240px; overflow: hidden;">
        @php
            $imgUrl = asset('images/attraction_nature_1783508280642.png');
            if (!empty($attraction->featured_image)) {
                $imgUrl = str_starts_with($attraction->featured_image, 'http') ? $attraction->featured_image : asset($attraction->featured_image);
            } elseif (!empty($attraction->image)) {
                $imgUrl = str_starts_with($attraction->image, 'http') ? $attraction->image : asset('storage/' . $attraction->image);
            }
        @endphp
        <img src="{{ $imgUrl }}" 
             alt="{{ $attraction->name ?? 'Attraction Image' }}" 
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
        
        <div class="hotel-location text-muted small mb-2 d-flex align-items-center gap-1 fw-bold">
            <i class="fas fa-route text-primary"></i> 
            {{ $attraction->distance ?? '2.5 miles away' }}
        </div>
        
        <h3 class="hotel-title fw-bold text-heading mb-3" style="font-size: 1.25rem; font-family: var(--font-heading);">
            {{ $attraction->name ?? 'Sleeping Bear Dunes' }}
        </h3>
        
        <p class="hotel-desc text-muted mb-4" style="font-size: 0.95rem; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: all 0.3s ease;">
            {{ Str::limit($attraction->description ?? 'Experience towering sand dunes and spectacular views of Lake Michigan at this national lakeshore.', 150) }}
        </p>
        
        <!-- Travel Time Row -->
        <div class="hotel-amenities d-flex align-items-center gap-3 mb-4 mt-auto text-secondary" style="font-size: 0.9rem;">
            <span><i class="fas fa-car text-primary opacity-75 me-1"></i> {{ $attraction->travel_time_car ?? '10 min drive' }}</span>
            <span><i class="fas fa-walking text-primary opacity-75 me-1"></i> {{ $attraction->travel_time_walk ?? '45 min walk' }}</span>
        </div>
        
        <!-- Footer -->
        <div class="hotel-card-footer pt-3 border-top d-flex flex-column gap-3">
            
            <div class="d-flex gap-2 w-100">
                <a href="{{ route('web.attractions.show', $attraction->slug ?? 'demo') }}" 
                   class="btn btn-outline-primary rounded-pill w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center" 
                   style="font-size: 0.9rem;"
                   onclick="event.stopPropagation();">
                    View Attraction <i class="fas fa-arrow-right ms-2"></i>
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
