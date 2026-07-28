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
        <div class="row g-5 align-items-start">
            
            <!-- Left Column: Details & Gallery -->
            <div class="col-lg-8">
                
                <!-- Quick Info Bar -->
                <div class="bg-white rounded-4 shadow-sm p-4 mb-4 d-flex flex-wrap gap-4 align-items-center justify-content-start">
                    <div class="d-flex align-items-center me-5">
                        <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center me-3 auto-style-49">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-bold text-uppercase">Date</div>
                            <div class="fw-bold">
                                @if($date->format('Y-m-d') === $endDate->format('Y-m-d'))
                                    {{ $date->format('l, F j, Y') }}
                                @else
                                    {{ $date->format('F j, Y') }} - {{ $endDate->format('F j, Y') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center me-3 auto-style-49">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-bold text-uppercase">Time</div>
                            <div class="fw-bold">
                                @if($date->format('g:i A') === $endDate->format('g:i A'))
                                    {{ $date->format('g:i A') }}
                                @else
                                    {{ $date->format('g:i A') }} - {{ $endDate->format('g:i A') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                @if(!empty($event->video))
                <!-- Event Video Preview -->
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4 auto-style-7">Event Preview Video</h3>
                    <div class="video-wrapper-premium w-100 h-100">
                        <div class="video-loading-spinner" id="videoSpinnerEvents">
                            <div class="spinner-border text-white" role="status" style="width: 1.5rem; height: 1.5rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        @php
                            $isYoutube = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $event->video, $matches);
                            $youtubeId = $isYoutube ? $matches[1] : null;
                        @endphp
                        @if($isYoutube)
                            <iframe class="w-100 h-100 rounded-3 shadow-sm" style="min-height:350px;" src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&mute=1&loop=1&playlist={{ $youtubeId }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen onload="document.getElementById('videoSpinnerEvents').style.display='none'"></iframe>
                        @else
                            <video class="w-100 h-100 object-fit-cover rounded-3 shadow-sm" controls autoplay muted loop playsinline style="object-fit: cover;"
                                   onplay="document.getElementById('videoSpinnerEvents').style.display='none'"
                                   onplaying="document.getElementById('videoSpinnerEvents').style.display='none'"
                                   onwaiting="document.getElementById('videoSpinnerEvents').style.display='flex'"
                                   oncanplay="document.getElementById('videoSpinnerEvents').style.display='none'">
                                <source src="{{ asset($event->video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- About Description -->
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4 auto-style-7">About this Event</h3>
                    
                    <div class="text-muted lh-18">
                        {!! $event->description ?? '<p>No description available for this event.</p>' !!}
                    </div>
                </div>

                <!-- FAQ Section -->
                @if(isset($event->faqs) && $event->faqs->count() > 0)
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4">
                    <h3 class="fw-bold mb-4 auto-style-7">Frequently Asked Questions</h3>
                    <div class="accordion accordion-flush" id="eventFaq">
                        @foreach($event->faqs as $index => $faq)
                        <div class="accordion-item border rounded-3 {{ !$loop->last ? 'mb-2' : '' }} overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $index }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="faq{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#eventFaq">
                                <div class="accordion-body text-muted lh-18">{!! $faq->answer !!}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Right Column: Sidebar Information -->
            <div class="col-lg-4" style="align-self: start; position: sticky; top: 90px;">
                <!-- Event Booking Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 auto-style-16">
                    <div class="card-body p-4">
                        <a href="{{ $event->website ?? '#' }}" target="_blank" class="btn btn-primary w-100 rounded-pill fw-bold py-3 shadow-sm d-flex justify-content-center align-items-center mb-3 text-uppercase auto-style-50">
                            <i class="fas fa-external-link-alt me-2"></i> Visit Event Website
                        </a>
                        
                        <button class="btn btn-outline-secondary w-100 rounded-pill fw-bold py-2 d-flex justify-content-center align-items-center" onclick="shareCurrentPage('{{ addslashes($event->name) }}')">
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
                    </div>{{-- end card-body --}}
                </div>{{-- end booking card --}}

                {{-- Sidebar Widget 1: Latest Upcoming Event --}}
                @if($latestUpcomingEvent)
                <div class="card border-0 shadow-sm rounded-4 mb-4" style="margin-top: 0;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            <i class="fas fa-bolt me-1 text-warning"></i> Latest Upcoming Event
                        </h6>
                        @php
                            $lupDate = $latestUpcomingEvent->start_date ? \Carbon\Carbon::parse($latestUpcomingEvent->start_date) : null;
                        @endphp
                        <a href="{{ route('web.events.show', $latestUpcomingEvent->slug) }}" class="text-decoration-none d-block">
                            <div class="d-flex align-items-start gap-3">
                                @if($latestUpcomingEvent->featured_image)
                                <img src="{{ asset($latestUpcomingEvent->featured_image) }}" alt="{{ $latestUpcomingEvent->featured_image_alt ?? $latestUpcomingEvent->name }}"
                                     class="rounded-3 object-fit-cover flex-shrink-0" style="width: 70px; height: 70px; object-fit: cover;">
                                @else
                                <div class="rounded-3 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 70px; height: 70px;">
                                    <i class="fas fa-calendar-alt text-primary fs-4"></i>
                                </div>
                                @endif
                                <div class="overflow-hidden">
                                    <div class="fw-bold text-dark mb-1" style="font-size: 0.9rem; line-height: 1.3;">{{ Str::limit($latestUpcomingEvent->name, 55) }}</div>
                                    @if($lupDate)
                                    <div class="small text-primary fw-semibold"><i class="fas fa-calendar-alt me-1"></i>{{ $lupDate->format('M j, Y') }}</div>
                                    @endif
                                    @if($latestUpcomingEvent->venue_name)
                                    <div class="small text-muted text-truncate"><i class="fas fa-map-marker-alt me-1"></i>{{ $latestUpcomingEvent->venue_name }}</div>
                                    @endif
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('web.events.show', $latestUpcomingEvent->slug) }}" class="btn btn-outline-primary rounded-pill w-100 mt-3 btn-sm fw-semibold">
                            View Event <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                @endif

                {{-- Sidebar Widget 2: More in This Category --}}
                @if($categoryEvents->count() > 0)
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            <i class="fas fa-layer-group me-1 text-primary"></i>
                            More in {{ $event->category->name ?? 'This Category' }}
                        </h6>
                        <div class="d-flex flex-column gap-3">
                            @foreach($categoryEvents as $catEvent)
                            @php
                                $catEventDate = $catEvent->start_date ? \Carbon\Carbon::parse($catEvent->start_date) : null;
                            @endphp
                            <a href="{{ route('web.events.show', $catEvent->slug) }}" class="text-decoration-none d-flex align-items-start gap-3">
                                @if($catEvent->featured_image)
                                <img src="{{ asset($catEvent->featured_image) }}" alt="{{ $catEvent->featured_image_alt ?? $catEvent->name }}"
                                     class="rounded-3 object-fit-cover flex-shrink-0" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                <div class="rounded-3 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px;">
                                    <i class="fas fa-calendar text-secondary"></i>
                                </div>
                                @endif
                                <div class="overflow-hidden">
                                    <div class="fw-semibold text-dark mb-1" style="font-size: 0.85rem; line-height: 1.3;">{{ Str::limit($catEvent->name, 45) }}</div>
                                    @if($catEventDate)
                                    <div class="small text-primary"><i class="fas fa-calendar-alt me-1"></i>{{ $catEventDate->format('M j, Y') }}</div>
                                    @endif
                                </div>
                            </a>
                            @if(!$loop->last)
                            <hr class="my-0 opacity-15">
                            @endif
                            @endforeach
                        </div>
                        @if(isset($event->category))
                        <a href="{{ route('web.events.category', $event->category->slug) }}" class="btn btn-outline-secondary rounded-pill w-100 mt-3 btn-sm fw-semibold">
                            View All {{ $event->category->name }} Events <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>
        
        <!-- Full-Width Location & Map -->
        <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mt-4">
            <h3 class="fw-bold mb-4 auto-style-7">Venue Map</h3>
            <p class="text-muted mb-4"><i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ $event->venue_name ?? '' }} - {{ $event->address ?? '' }}, {{ $event->city ?? '' }}, {{ $event->state ?? 'MI' }}</p>
            <div class="rounded-3 overflow-hidden bg-light auto-style-51 map-wrapper" style="height: 400px; width: 100%;">
                <style>
                    .map-wrapper iframe {
                        width: 100% !important;
                        height: 100% !important;
                        border: 0;
                    }
                </style>
                @if(!empty($event->map_iframe))
                    @if(str_contains($event->map_iframe, '<iframe'))
                        {!! $event->map_iframe !!}
                    @else
                        <iframe width="100%" height="100%" frameborder="0" style="border:0;" src="{{ $event->map_iframe }}"></iframe>
                    @endif
                @else
                    <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ urlencode(($event->venue_name ?? '') . ' ' . ($event->city ?? 'Michigan')) }}&t=&z=14&ie=UTF8&iwloc=&output=embed"></iframe>
                @endif
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
