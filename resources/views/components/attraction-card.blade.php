@props([
    'attraction',
    'featured' => false,
    'compact' => false
])

<div class="listing-card position-relative cursor-pointer" onclick="window.location.href='{{ route('web.attractions.show', $attraction->slug ?? 'demo') }}'">
    
    <!-- Image Wrapper -->
    <div class="listing-img-wrapper position-relative h-240px overflow-hidden">
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
        
        <div class="listing-location text-muted small mb-2 d-flex align-items-center gap-1 fw-bold">
            <i class="fas fa-route text-primary"></i> 
            {{ $attraction->distance ?? '2.5 miles away' }}
        </div>
        
        <h3 class="listing-title fw-bold text-heading mb-3 fs-5 font-heading">
            {{ $attraction->name ?? 'Sleeping Bear Dunes' }}
        </h3>
        
        <p class="listing-desc text-muted mb-4 text-truncate-2 lh-16 transition-base fs-095rem">
            {{ Str::limit($attraction->description ?? 'Experience towering sand dunes and spectacular views of Lake Michigan at this national lakeshore.', 150) }}
        </p>
        
        <!-- Travel Time Row -->
        <div class="listing-amenities d-flex align-items-center gap-3 mb-4 mt-auto text-secondary fs-sm">
            <span><i class="fas fa-car text-primary opacity-75 me-1"></i> {{ $attraction->travel_time_car ?? '10 min drive' }}</span>
            <span><i class="fas fa-walking text-primary opacity-75 me-1"></i> {{ $attraction->travel_time_walk ?? '45 min walk' }}</span>
        </div>
        
        <!-- Footer -->
        <div class="hotel-card-footer pt-3 border-top d-flex flex-column gap-3">
            
            <div class="d-flex gap-2 w-100">
                <a href="{{ route('web.attractions.show', $attraction->slug ?? 'demo') }}" 
                   class="btn btn-outline-primary rounded-pill w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center fs-sm"
                   onclick="event.stopPropagation();">
                    View Attraction <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
            
        </div>
    </div>
</div>


