<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Blog - Michigan Explorer</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .hero { background: linear-gradient(135deg, #0dcaf0, #0d6efd); color: white; padding: 60px 0; text-align: center; }
        .blog-card { border: none; border-radius: 12px; transition: transform 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .blog-card:hover { transform: translateY(-5px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .blog-img { height: 220px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px; background-color: #e9ecef; }
        .publish-date { font-size: 0.85rem; color: #6c757d; }
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
                    <a class="nav-link" href="{{ route('web.events.index') }}">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('web.blogs.index') }}">Blog</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="hero mb-5">
        <div class="container">
            <h1 class="display-4 fw-bold">Explorer's Journal</h1>
            <p class="lead">Stories, tips, and guides for your Michigan City adventure.</p>
        </div>
    </div>
    <div class="container mb-5">
        <div class="row g-4">
            @forelse($blogs as $blog)
            <div class="col-md-4">
                <div class="card blog-card h-100">
                    <img src="{{ $blog->featured_image ? asset($blog->featured_image) : 'https://placehold.co/600x400/e9ecef/495057?text=No+Image' }}" class="card-img-top blog-img" alt="{{ $blog->title }}">
                    <div class="card-body">
                        <span class="badge bg-info text-dark mb-2">{{ $blog->category ? $blog->category->name : 'Uncategorized' }}</span>
                        <h5 class="card-title fw-bold">{{ $blog->title }}</h5>
                        <div class="publish-date mb-3"><i class="fa fa-clock"></i> {{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') : $blog->created_at->format('M d, Y') }}</div>
                        <p class="card-text">{{ $blog->excerpt ? $blog->excerpt : Str::limit(strip_tags($blog->content), 120) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <a href="{{ route('web.blogs.show', $blog->slug) }}" class="btn btn-outline-info text-dark w-100 rounded-pill">Read More</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <h4>No articles found.</h4>
            </div>
            @endforelse
        </div>
        <div class="d-flex justify-content-center mt-5">
            {{ $blogs->links('pagination::bootstrap-5') }}
        </div>
    </div>
</body>
</html>
