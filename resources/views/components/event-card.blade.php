@props(['event'])

<div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden premium-card transition-hover position-relative">
    
    <!-- Image Section -->
    <div class="position-relative overflow-hidden h-220px">
        <img src="{{ $event->featured_image ? asset($event->featured_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop' }}" 
             class="card-img-top w-100 h-100 object-fit-cover transition-hover" 
             loading="lazy"
             alt="{{ $event->featured_image_alt ?? $event->name }}">
             
        <!-- Date Badge overlaid on top right -->
        <div class="position-absolute top-0 end-0 m-3 bg-white rounded-3 shadow-sm text-center d-flex flex-column justify-content-center align-items-center w-55px h-60px z-index-10">
            @php
                $date = $event->start_date ? \Carbon\Carbon::parse($event->start_date) : \Carbon\Carbon::now()->addDays(rand(1, 14));
            @endphp
            <span class="text-primary fw-bold text-uppercase fs-xs letter-spacing-1 lh-1">{{ $date->format('M') }}</span>
            <span class="text-dark fw-bolder fs-4 lh-11">{{ $date->format('d') }}</span>
        </div>
        
        <!-- Category Badge overlaid on bottom left -->
        @if(isset($event->category))
        <div class="position-absolute bottom-0 start-0 m-3 z-2">
            <span class="badge bg-primary rounded-pill px-3 py-2 fw-semibold shadow-sm">
                @if(isset($event->category->icon)) <i class="{{ $event->category->icon }} me-1"></i> @endif
                {{ $event->category->name }}
            </span>
        </div>
        @endif
        
        <!-- Gradient Overlay for lower half of image to make category badge pop -->
        <div class="position-absolute bottom-0 start-0 w-100 h-50 z-1 bg-gradient-dark-bottom"></div>
    </div>
    
    <!-- Content Section -->
    <div class="card-body p-4 d-flex flex-column">
        <h3 class="card-title fw-bold mb-2 text-dark font-heading fs-5">{{ $event->name }}</h3>
        
        <div class="d-flex align-items-center text-muted mb-3 small">
            <i class="fas fa-map-marker-alt text-primary me-2"></i>
            <span>{{ $event->venue_name ?? $event->city ?? 'Grand Rapids, MI' }}</span>
        </div>
        
        <p class="card-text text-muted mb-4 small lh-16">
            {{ Str::limit(strip_tags($event->short_description ?? $event->description), 90) }}
        </p>
        
        <div class="mt-auto pt-3 border-top">
            <a href="{{ route('web.events.show', $event->slug) }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-semibold w-100 text-center">
                Details <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Make the whole card clickable -->
    <a href="{{ route('web.events.show', $event->slug) }}" class="position-absolute top-0 start-0 w-100 h-100 z-index-5">
        <span class="visually-hidden">View Event Details</span>
    </a>
</div>
