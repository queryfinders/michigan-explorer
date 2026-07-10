<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $attraction->name }} - Michigan Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .hero-img { width: 100%; height: 400px; object-fit: cover; border-radius: 16px; background-color: #e9ecef; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">Michigan Explorer</a>
            <a href="{{ route('web.attractions.index') }}" class="btn btn-outline-secondary btn-sm">Back to Attractions</a>
        </div>
    </nav>
    <div class="container mb-5">
        <img src="{{ $attraction->featured_image ? asset($attraction->featured_image) : 'https://placehold.co/1200x400/e9ecef/495057?text=No+Image' }}" class="hero-img mb-4 shadow" alt="{{ $attraction->name }}">
        <div class="row">
            <div class="col-md-8">
                <span class="badge bg-success mb-2">{{ $attraction->category ? $attraction->category->name : 'Attraction' }}</span>
                <h1 class="fw-bold">{{ $attraction->name }}</h1>
                <p class="text-muted"><i class="fa fa-map-marker-alt"></i> {{ $attraction->address }}, {{ $attraction->city }}, {{ $attraction->state }} {{ $attraction->zip }}</p>
                <div class="mt-4">
                    <h4>About this Attraction</h4>
                    <p>{{ $attraction->description }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Information</h5>
                        @if($attraction->phone)<p><strong>Phone:</strong> {{ $attraction->phone }}</p>@endif
                        @if($attraction->email)<p><strong>Email:</strong> {{ $attraction->email }}</p>@endif
                        @if($attraction->website)<p><strong>Website:</strong> <a href="{{ $attraction->website }}" target="_blank">Visit Site</a></p>@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
