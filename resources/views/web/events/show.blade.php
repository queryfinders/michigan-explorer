<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->name }} - Michigan Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .hero-img { width: 100%; height: 400px; object-fit: cover; border-radius: 16px; background-color: #e9ecef; }
        .info-box { background: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .info-icon { font-size: 1.5rem; color: #6f42c1; margin-right: 15px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">Michigan Explorer</a>
            <a href="{{ route('web.events.index') }}" class="btn btn-outline-secondary btn-sm">Back to Events</a>
        </div>
    </nav>
    <div class="container mb-5">
        <img src="{{ $event->featured_image ? asset($event->featured_image) : 'https://placehold.co/1200x400/e9ecef/495057?text=No+Image' }}" class="hero-img mb-4 shadow" alt="{{ $event->name }}">
        <div class="row">
            <div class="col-md-8">
                <span class="badge bg-primary mb-2">{{ $event->category ? $event->category->name : 'Event' }}</span>
                <h1 class="fw-bold">{{ $event->name }}</h1>
                <div class="mt-4">
                    <h4>About this Event</h4>
                    <p>{!! nl2br(e($event->description)) !!}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">Event Details</h5>
                        
                        @if($event->start_date)
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa fa-calendar-alt info-icon"></i>
                            <div>
                                <small class="text-muted d-block">Date & Time</small>
                                <strong>{{ \Carbon\Carbon::parse($event->start_date)->format('F d, Y - g:i A') }}</strong>
                            </div>
                        </div>
                        @endif

                        @if($event->venue_name || $event->city)
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa fa-map-marker-alt info-icon"></i>
                            <div>
                                <small class="text-muted d-block">Location</small>
                                <strong>{{ $event->venue_name }}</strong><br>
                                {{ $event->city }}{{ $event->state ? ', ' . $event->state : '' }}
                            </div>
                        </div>
                        @endif

                        @if($event->price)
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa fa-ticket-alt info-icon"></i>
                            <div>
                                <small class="text-muted d-block">Admission</small>
                                <strong>${{ number_format($event->price, 2) }}</strong>
                            </div>
                        </div>
                        @endif
                        
                        @if($event->website)
                        <hr>
                        <a href="{{ $event->website }}" target="_blank" class="btn btn-primary w-100 rounded-pill mt-2">Visit Event Website</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
