@extends('web.layout.app_layout')

@section('webLayoutContent')

@php
    $date = $event->start_date ? \Carbon\Carbon::parse($event->start_date) : now()->addDays(2);
    $endDate = $event->end_date ? \Carbon\Carbon::parse($event->end_date) : $date->copy()->addHours(4);
    $heroImage = $event->featured_image ? asset($event->featured_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1600&auto=format&fit=crop';
@endphp

<!-- 1. Hero Banner -->
<section class="hotel-detail-hero position-relative" style="height: 500px; background-image: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.5)), url('{{ $heroImage }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container h-100 d-flex flex-column justify-content-center align-items-center text-center pt-5">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="{{ route('web.home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('web.events.index') }}" class="text-white text-decoration-none">Events</a></li>
                @if(isset($event->category))
                <li class="breadcrumb-item"><a href="{{ route('web.events.category', $event->category->slug) }}" class="text-white text-decoration-none">{{ $event->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active text-white opacity-75" aria-current="page">{{ Str::limit($event->name, 30) }}</li>
            </ol>
        </nav>
        
        @if(isset($event->category))
            <span class="badge bg-primary rounded-pill px-4 py-2 mb-3 shadow-sm fs-6">
                @if($event->category->icon) <i class="{{ $event->category->icon }} me-1"></i> @endif {{ $event->category->name }}
            </span>
        @endif
        
        <h1 class="display-4 fw-bold text-white mb-2 auto-style-7">{{ $event->name }}</h1>
        <p class="text-white opacity-75 fs-5 mb-0"><i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ $event->venue_name ?? 'Michigan' }}</p>
    </div>
</section>

<!-- 2. Main Content Area -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-5">
            
            <!-- Left Column: Details & Gallery -->
            <div class="col-lg-8">
                
                <!-- Quick Info Bar -->
                <div class="bg-white rounded-4 shadow-sm p-4 mb-4 d-flex flex-wrap gap-4 align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center me-3 auto-style-49">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-bold text-uppercase">Date</div>
                            <div class="fw-bold">{{ $date->format('l, F j, Y') }}</div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center me-3 auto-style-49">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-bold text-uppercase">Time</div>
                            <div class="fw-bold">{{ $date->format('g:i A') }} - {{ $endDate->format('g:i A') }}</div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center me-3 auto-style-49">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-bold text-uppercase">Tickets</div>
                            <div class="fw-bold">{{ $event->price > 0 ? '$' . number_format($event->price, 2) : 'Free Entry' }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- About Description -->
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4 auto-style-7">About this Event</h3>
                    
                    <div class="text-muted lh-18">
                        {!! $event->description ?? '<p>No description available for this event.</p>' !!}
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4 auto-style-7">Frequently Asked Questions</h3>
                    <div class="accordion accordion-flush" id="eventFaq">
                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Is parking available?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#eventFaq">
                                <div class="accordion-body text-muted lh-18">Public parking is available near the venue. We recommend arriving early as spots fill up quickly during events.</div>
                            </div>
                        </div>
                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Can I bring my own food and drinks?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#eventFaq">
                                <div class="accordion-body text-muted lh-18">Outside food and beverages are generally not permitted unless otherwise specified by the venue. Vendors will be available on-site.</div>
                            </div>
                        </div>
                        <div class="accordion-item border rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Is the event wheelchair accessible?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#eventFaq">
                                <div class="accordion-body text-muted lh-18">Yes, the venue is fully ADA compliant and wheelchair accessible. Dedicated seating areas are available upon request.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Sidebar Information -->
            <div class="col-lg-4">
                
                <!-- Event Booking Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 sticky-top auto-style-16">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="text-muted small fw-bold text-uppercase mb-1">General Admission</div>
                            <h2 class="fw-bolder text-primary mb-0 auto-style-7">
                                {{ $event->price > 0 ? '$' . number_format($event->price, 2) : 'Free' }}
                            </h2>
                        </div>
                        
                        <a href="{{ $event->website ?? '#' }}" target="_blank" class="btn btn-primary w-100 rounded-pill fw-bold py-3 shadow-sm d-flex justify-content-center align-items-center mb-3 text-uppercase auto-style-50">
                            <i class="fas fa-ticket-alt me-2"></i> Get Tickets
                        </a>
                        
                        <button class="btn btn-outline-secondary w-100 rounded-pill fw-bold py-2 d-flex justify-content-center align-items-center">
                            <i class="fas fa-share-alt me-2"></i> Share Event
                        </button>
                        
                        <hr class="my-4 text-muted opacity-25">
                        
                        <h5 class="fw-bold mb-3 auto-style-7">Location</h5>
                        
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex align-items-start mb-3 text-muted">
                                <i class="fas fa-building text-primary mt-1 me-3"></i>
                                <div>
                                    <div class="fw-bold text-dark">{{ $event->venue_name ?? 'Event Venue' }}</div>
                                    <div>{{ $event->address ?? '123 Main St' }}</div>
                                    <div>{{ $event->city ?? 'City' }}, {{ $event->state ?? 'MI' }} {{ $event->zip ?? '' }}</div>
                                </div>
                            </li>
                            
                            @if($event->phone)
                            <li class="d-flex align-items-center mb-3 text-muted">
                                <i class="fas fa-phone-alt text-primary me-3"></i>
                                <a href="tel:{{ $event->phone }}" class="text-muted text-decoration-none">{{ $event->phone }}</a>
                            </li>
                            @endif
                            
                            @if($event->website)
                            <li class="d-flex align-items-center text-muted">
                                <i class="fas fa-globe text-primary me-3"></i>
                                <a href="{{ $event->website }}" target="_blank" class="text-primary text-decoration-none text-truncate d-inline-block auto-style-17">Official Website</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        
        <!-- Full-Width Location & Map -->
        <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mt-4">
            <h3 class="fw-bold mb-4 auto-style-7">Venue Map</h3>
            <p class="text-muted mb-4"><i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ $event->venue_name ?? '' }} - {{ $event->address ?? '' }}, {{ $event->city ?? '' }}, {{ $event->state ?? 'MI' }}</p>
            <div class="rounded-3 overflow-hidden bg-light auto-style-51">
                <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ urlencode(($event->venue_name ?? '') . ' ' . ($event->city ?? 'Michigan')) }}&t=&z=14&ie=UTF8&iwloc=&output=embed"></iframe>
            </div>
        </div>

    </div>
</section>

<!-- Related Events -->
@if(isset($moreEvents) && $moreEvents->count() > 0)
<section class="py-5 border-top">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold mb-1 auto-style-7">More Events in {{ $event->city ?? 'the Area' }}</h3>
                <p class="text-muted mb-0">Discover other upcoming events you might enjoy</p>
            </div>
            <a href="{{ route('web.events.index') }}" class="btn btn-outline-primary rounded-pill px-4">View All Events</a>
        </div>
        
        <div class="row g-4">
            @foreach($moreEvents as $relatedEvent)
                <div class="col-lg-4 col-md-6">
                    <x-event-card :event="$relatedEvent" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@section('webLayoutScript')

@endsection
