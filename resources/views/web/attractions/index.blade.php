<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attractions - Michigan Explorer</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .hero { background: linear-gradient(135deg, #198754, #20c997); color: white; padding: 60px 0; text-align: center; }
        .attraction-card { border: none; border-radius: 12px; transition: transform 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .attraction-card:hover { transform: translateY(-5px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .attraction-img { height: 200px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px; background-color: #e9ecef; }
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
                    <a class="nav-link active" href="{{ route('web.attractions.index') }}">Attractions</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="hero mb-5">
        <div class="container">
            <h1 class="display-4 fw-bold">Exciting Attractions</h1>
            <p class="lead">Discover the best places to visit in Michigan City.</p>
        </div>
    </div>
    <div class="container mb-5">
        <div class="row g-4">
            @forelse($attractions as $attraction)
            <div class="col-md-4">
                <div class="card attraction-card h-100">
                    <img src="{{ $attraction->featured_image ? asset($attraction->featured_image) : 'https://placehold.co/600x400/e9ecef/495057?text=No+Image' }}" class="card-img-top attraction-img" alt="{{ $attraction->name }}">
                    <div class="card-body">
                        <span class="badge bg-success mb-2">{{ $attraction->category ? $attraction->category->name : 'Attraction' }}</span>
                        <h5 class="card-title fw-bold">{{ $attraction->name }}</h5>
                        <p class="text-muted small mb-3"><i class="fa fa-map-marker-alt"></i> {{ $attraction->city ? $attraction->city . ', MI' : 'Michigan City, IN' }}</p>
                        <p class="card-text">{{ Str::limit($attraction->description, 100) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <a href="{{ route('web.attractions.show', $attraction->slug) }}" class="btn btn-outline-success w-100 rounded-pill">View Details</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <h4>No attractions found.</h4>
            </div>
            @endforelse
        </div>
        <div class="d-flex justify-content-center mt-5">
            {{ $attractions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</body>
</html>
