{{-- Latest Upcoming Event Strip: below Featured Hotels --}}
@if(isset($upcomingEventsWidget) && $upcomingEventsWidget->count() > 0)
@php $latestStripEv = $upcomingEventsWidget->first(); @endphp
<section class="section-padding-upcoming bg-light overflow-hidden">
<div class="container">
    <a href="{{ route('web.events.show', $latestStripEv->slug) }}" class="text-decoration-none d-block ev-bottom-strip rounded-4 overflow-hidden ev-strip-container">
        <div class="d-flex align-items-stretch ev-strip-inner">
            {{-- Thumbnail --}}
            <div class="flex-shrink-0 position-relative overflow-hidden ev-strip-thumb-container">
                @if($latestStripEv->featured_image)
                <img src="{{ asset($latestStripEv->featured_image) }}" alt="{{ $latestStripEv->featured_image_alt ?? $latestStripEv->name }}"
                     class="ev-strip-img w-100 h-100 position-absolute top-0 start-0">
                @else
                <div class="w-100 h-100 d-flex align-items-center justify-content-center position-absolute top-0 start-0 ev-strip-fallback">
                    <i class="fas fa-calendar-star text-warning fs-2"></i>
                </div>
                @endif
            </div>
            {{-- Content --}}
            <div class="px-4 py-3 d-flex flex-column justify-content-center flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="fw-bold text-uppercase ev-strip-badge">
                        <i class="fas fa-bolt me-1"></i> Latest Upcoming
                    </span>
                    @if($latestStripEv->category)
                    <span class="text-muted ev-strip-category-name">{{ $latestStripEv->category->name }}</span>
                    @endif
                </div>
                <h5 class="fw-bold text-dark mb-1 ev-strip-title">{{ $latestStripEv->name }}</h5>
                <div class="d-flex flex-wrap gap-3 text-muted ev-strip-meta">
                    @if($latestStripEv->start_date)
                    <span><i class="fas fa-calendar-alt me-1 text-warning"></i>{{ \Carbon\Carbon::parse($latestStripEv->start_date)->format('l, M j, Y') }}</span>
                    @endif
                    @if($latestStripEv->venue_name)
                    <span><i class="fas fa-map-marker-alt me-1 text-primary"></i>{{ $latestStripEv->venue_name }}</span>
                    @endif
                    @if($latestStripEv->city)
                    <span><i class="fas fa-city me-1 text-primary"></i>{{ $latestStripEv->city }}, MI</span>
                    @endif
                </div>
            </div>
            {{-- CTA --}}
            <div class="flex-shrink-0 d-flex align-items-center px-4">
                <span class="btn btn-warning rounded-pill px-4 fw-bold btn-sm">
                    See Event <i class="fas fa-arrow-right ms-1"></i>
                </span>
            </div>
        </div>
    </a>
</div>
</section>
@endif
