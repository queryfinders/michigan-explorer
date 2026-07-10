<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->title }} - Michigan Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; line-height: 1.8; font-size: 1.1rem; }
        .hero-img { width: 100%; height: 500px; object-fit: cover; border-radius: 16px; background-color: #e9ecef; }
        .blog-header { text-align: center; max-width: 800px; margin: 0 auto 40px auto; }
        .blog-content { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .blog-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 20px 0; }
        .meta-info { color: #6c757d; font-size: 0.95rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">Michigan Explorer</a>
            <a href="{{ route('web.blogs.index') }}" class="btn btn-outline-secondary btn-sm">Back to Blog</a>
        </div>
    </nav>
    <div class="container mb-5">
        <div class="blog-header">
            <span class="badge bg-info text-dark mb-3">{{ $blog->category ? $blog->category->name : 'Uncategorized' }}</span>
            <h1 class="display-5 fw-bold mb-3">{{ $blog->title }}</h1>
            <div class="meta-info">
                <span>Published on {{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('F d, Y') : $blog->created_at->format('F d, Y') }}</span>
                @if($blog->author)
                <span class="ms-3">By {{ $blog->author->name }}</span>
                @endif
            </div>
        </div>
        
        <img src="{{ $blog->featured_image ? asset($blog->featured_image) : 'https://placehold.co/1200x500/e9ecef/495057?text=No+Image' }}" class="hero-img mb-5 shadow" alt="{{ $blog->title }}">
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="blog-content">
                    {!! $blog->content !!}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
