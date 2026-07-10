<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Michigan Explorer</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .hero { background: linear-gradient(135deg, #6f42c1, #d63384); color: white; padding: 60px 0; text-align: center; }
        .event-card { border: none; border-radius: 12px; transition: transform 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .event-card:hover { transform: translateY(-5px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .event-img { height: 200px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px; background-color: #e9ecef; }
        .date-badge { position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.9); color: #6f42c1; padding: 8px 12px; border-radius: 8px; font-weight: bold; text-align: center; line-height: 1.2; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .date-badge .month { font-size: 0.8rem; text-transform: uppercase; }
        .date-badge .day { font-size: 1.2rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">Michigan Explorer</a>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('web.hotels.index') }}">Hotels</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('web.restaurants.index') }}">Restaurants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('web.attractions.index') }}">Attractions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('web.events.index') }}">Events</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="hero mb-5">
        <div class="container">
            <h1 class="display-4 fw-bold">Upcoming Events</h1>
            <p class="lead">See what's happening around Michigan City.</p>
        </div>
    </div>
    <div class="container mb-5">
        <div class="row g-4">
            @forelse($events as $event)
            <div class="col-md-4">
                <div class="card event-card h-100 position-relative">
                    <img src="{{ $event->featured_image ? asset($event->featured_image) : 'https://placehold.co/600x400/e9ecef/495057?text=No+Image' }}" class="card-img-top event-img" alt="{{ $event->name }}">
                    @if($event->start_date)
                    <div class="date-badge">
                        <div class="month">{{ \Carbon\Carbon::parse($event->start_date)->format('M') }}</div>
                        <div class="day">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</div>
                    </div>
                    @endif
                    <div class="card-body">
                        <span class="badge bg-primary mb-2">{{ $event->category ? $event->category->name : 'Event' }}</span>
                        <h5 class="card-title fw-bold">{{ $event->name }}</h5>
                        <p class="text-muted small mb-3">
                            <i class="fa fa-map-marker-alt"></i> {{ $event->venue_name ? $event->venue_name . ', ' : '' }}{{ $event->city ? $event->city : 'Michigan City' }}
                        </p>
                        <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <a href="{{ route('web.events.show', $event->slug) }}" class="btn btn-outline-primary w-100 rounded-pill">View Details</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <h4>No events found.</h4>
            </div>
            @endforelse
        </div>
        <div class="d-flex justify-content-center mt-5">
            {{ $events->links('pagination::bootstrap-5') }}
        </div>
    </div>
</body>
</html>
