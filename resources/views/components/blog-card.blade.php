@props(['blog'])

<div class="card premium-card h-100 border-0 rounded-4 shadow-sm overflow-hidden text-decoration-none">
    <div class="img-wrapper position-relative" style="height: 220px; overflow: hidden;">
        <img src="{{ $blog->featured_image ? asset($blog->featured_image) : asset('images/travel_guide_1783508300840.jpg') }}" class="card-img-top w-100 h-100 object-fit-cover transition-transform" alt="{{ $blog->title }}">
    </div>
    <div class="card-body bg-white d-flex flex-column p-4">
        <span class="text-primary fw-bold small text-uppercase mb-2 d-block">{{ $blog->category->name ?? 'Travel' }}</span>
        <h3 class="card-title fw-bold text-heading mb-3 fs-5">{{ $blog->title }}</h3>
        <p class="text-muted small mb-4">By {{ $blog->author ? $blog->author->name : 'Admin' }} | {{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</p>
        
        <div class="mt-auto">
            <a href="{{ route('web.blogs.show', $blog->slug) }}" class="btn btn-outline-primary rounded-pill w-100 fw-bold">Read Full Guide <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</div>
