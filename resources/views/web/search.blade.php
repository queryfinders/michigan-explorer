<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Michigan Explorer</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .hero { background: #212529; color: white; padding: 60px 0; text-align: center; }
        .search-result-card { border: none; border-radius: 12px; transition: transform 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .search-result-card:hover { transform: translateY(-5px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .result-img { width: 100%; height: 200px; object-fit: cover; border-radius: 12px 12px 0 0; background-color: #e9ecef; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">Michigan Explorer</a>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('web.hotels.index') }}">Hotels</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('web.restaurants.index') }}">Restaurants</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('web.attractions.index') }}">Attractions</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('web.events.index') }}">Events</a></li>
            </ul>
        </div>
    </nav>
    <div class="hero mb-5">
        <div class="container">
            <h1 class="display-5 fw-bold">Search Results</h1>
            <p class="lead">Results for "{{ $q }}"</p>
            <form action="{{ route('web.search') }}" method="GET" class="mt-4 mx-auto" style="max-width: 600px;">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" name="q" value="{{ $q }}" placeholder="Search for hotels, events, attractions..." required>
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>
    <div class="container mb-5">
        @if($results->count() > 0)
            <div class="row g-4">
                @foreach($results as $item)
                <div class="col-md-4">
                    <div class="card search-result-card h-100">
                        <img src="{{ $item->image ? asset($item->image) : 'https://placehold.co/600x400/e9ecef/495057?text=Result' }}" class="card-img-top result-img" alt="{{ $item->title }}">
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">{{ $item->type }}</span>
                            <h5 class="card-title fw-bold">{{ $item->title }}</h5>
                            <p class="card-text text-muted small">{{ $item->description }}</p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <a href="{{ $item->url }}" class="btn btn-outline-primary w-100 rounded-pill">View Details</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <h4 class="text-muted">No results found for your query. Try a different term!</h4>
            </div>
        @endif
    </div>
</body>
</html>
