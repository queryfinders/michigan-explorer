<!-- Upcoming Events Widget: Large Banner + Side Cards -->
@if(isset($upcomingEventsWidget) && $upcomingEventsWidget->count() > 0)
@php
    $featuredEv  = $upcomingEventsWidget->first();
    $sideEvs     = $upcomingEventsWidget->skip(1)->take(3);
    $bottomEv    = $upcomingEventsWidget->skip(1)->first(); // single latest after featured
@endphp


<section class="section-padding ev-widget-section">
    <div class="container">

        <!-- Section Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-5">
            <div>
                <span class="badge rounded-pill px-3 py-2 mb-2 d-inline-block fw-semibold ev-widget-badge">
                    <i class="fas fa-fire me-1"></i> DON'T MISS OUT
                </span>
                <h2 class="fw-bold text-white mb-1" style="font-size: 2rem;">Upcoming Events in Michigan</h2>
                <p class="text-white opacity-50 mb-0">Live concerts, festivals, cultural gatherings &amp; more.</p>
            </div>
            <a href="{{ route('web.events.index') }}" class="btn btn-outline-light rounded-pill px-4 fw-semibold mt-3 mt-md-0">
                View All Events <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

        <!-- Main Grid: Featured Banner + Side Cards -->
        <div class="row g-4 align-items-stretch">

            <!-- Left: Featured Event Banner with hover zoom -->
            <div class="col-lg-7">
                <a href="{{ route('web.events.show', $featuredEv->slug) }}" class="text-decoration-none d-block h-100 ev-banner-link">
                    <div class="position-relative rounded-4 overflow-hidden h-100 ev-widget-banner-inner">
                        @if($featuredEv->featured_image)
                        <img src="{{ asset($featuredEv->featured_image) }}" alt="{{ $featuredEv->featured_image_alt ?? $featuredEv->name }}"
                             class="w-100 h-100 position-absolute top-0 start-0 ev-banner-img">
                        @else
                        <div class="w-100 h-100 position-absolute top-0 start-0 ev-banner-img ev-banner-fallback"></div>
                        @endif
                        <!-- Gradient Overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 ev-banner-overlay"></div>
                        <!-- Content -->
                        <div class="position-absolute bottom-0 start-0 p-4 p-md-5 w-100">
                            @if($featuredEv->category)
                            <span class="badge rounded-pill px-3 py-2 mb-3 d-inline-block fw-semibold ev-banner-cat">
                                @if($featuredEv->category->icon)<i class="{{ $featuredEv->category->icon }} me-1"></i>@endif
                                {{ $featuredEv->category->name }}
                            </span>
                            @endif
                            <h3 class="fw-bold text-white mb-2 ev-widget-banner-title">{{ $featuredEv->name }}</h3>
                            <div class="d-flex flex-wrap gap-3 text-white opacity-75 mb-3 ev-widget-banner-meta">
                                @if($featuredEv->start_date)
                                <span><i class="fas fa-calendar-alt me-1 text-warning"></i>{{ \Carbon\Carbon::parse($featuredEv->start_date)->format('M j, Y') }}</span>
                                @endif
                                @if($featuredEv->venue_name)
                                <span><i class="fas fa-map-marker-alt me-1 text-warning"></i>{{ $featuredEv->venue_name }}</span>
                                @endif
                                @if($featuredEv->city)
                                <span><i class="fas fa-city me-1 text-warning"></i>{{ $featuredEv->city }}</span>
                                @endif
                            </div>
                            <span class="btn btn-warning btn-sm rounded-pill px-4 fw-bold">
                                View Details <i class="fas fa-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Right: Stacked Event Cards with hover glow -->
            <div class="col-lg-5 d-flex flex-column gap-3">
                @foreach($sideEvs as $sideEv)
                <a href="{{ route('web.events.show', $sideEv->slug) }}" class="text-decoration-none d-block flex-fill">
                    <div class="ev-side-card rounded-4 overflow-hidden d-flex align-items-stretch h-100">
                        <!-- Thumbnail -->
                        @if($sideEv->featured_image)
                        <img src="{{ asset($sideEv->featured_image) }}" alt="{{ $sideEv->featured_image_alt ?? $sideEv->name }}"
                             class="flex-shrink-0 ev-side-card-thumb-img">
                        @else
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center ev-side-fallback ev-side-fallback-thumb">
                            <i class="fas fa-calendar-alt text-warning fs-3"></i>
                        </div>
                        @endif
                        <!-- Text -->
                        <div class="p-3 d-flex flex-column justify-content-center overflow-hidden flex-grow-1">
                            @if($sideEv->category)
                            <span class="small fw-semibold mb-1 ev-side-cat">{{ $sideEv->category->name }}</span>
                            @endif
                            <div class="fw-bold text-white mb-1 ev-side-card-title">{{ Str::limit($sideEv->name, 50) }}</div>
                            <div class="d-flex flex-wrap gap-2 text-white ev-side-card-meta">
                                @if($sideEv->start_date)
                                <span><i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($sideEv->start_date)->format('M j, Y') }}</span>
                                @endif
                                @if($sideEv->city)
                                <span><i class="fas fa-map-marker-alt me-1"></i>{{ $sideEv->city }}</span>
                                @endif
                            </div>
                        </div>
                        <!-- Arrow -->
                        <div class="flex-shrink-0 d-flex align-items-center px-3 ev-side-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

        </div>{{-- end main grid row --}}

    </div>
</section>
@endif
