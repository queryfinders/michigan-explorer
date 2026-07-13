<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->meta_title ?? $page->title }} - Michigan Explorer</title>
    <meta name="description" content="{{ $page->meta_description ?? '' }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="/">Michigan Explorer</a>
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
                    <a class="nav-link" href="{{ route('web.events.index') }}">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('web.blogs.index') }}">Blog</a>
                </li>
            </ul>
        </div>
    </nav>
    
    @if($page->featured_image)
        <img src="{{ asset($page->featured_image) }}" class="hero-img" alt="{{ $page->title }}">
    @else
        <div class="page-header">
            <div class="container">
                <h1 class="display-4 fw-bold">{{ $page->title }}</h1>
            </div>
        </div>
    @endif
    
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="page-content">
                    @if($page->featured_image)
                        <h1 class="display-5 fw-bold mb-4 text-center">{{ $page->title }}</h1>
                    @endif
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
