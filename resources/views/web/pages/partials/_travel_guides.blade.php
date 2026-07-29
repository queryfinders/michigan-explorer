<!-- 8. Latest Travel Guides (Blogs) -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
            <div>
                <h2 class="section-title mb-0">Latest Travel Guides</h2>
                <p class="section-subtitle mb-0 mt-2">Tips, itineraries, and stories from local experts.</p>
            </div>
            <a href="{{ route('web.blogs.index') }}" class="btn btn-outline-primary rounded-pill">View All Guides</a>
        </div>
        
        <div class="row g-4">
            @if(isset($blogs) && $blogs->count() > 0)
                @foreach($blogs->take(3) as $blog)
                <div class="col-lg-4 col-md-6">
                    <div class="premium-card border-0">
                        <div class="img-wrapper">
                            <img src="{{ $blog->featured_image ? asset($blog->featured_image) : asset('images/travel_guide_1783508300840.jpg') }}" class="card-img-top" alt="{{ $blog->title }}">
                        </div>
                        <div class="card-body bg-white rounded-bottom-4">
                            <span class="text-primary fw-bold small text-uppercase mb-2 d-block">{{ $blog->category->name ?? 'Travel' }}</span>
                            <h3 class="card-title">{{ $blog->title }}</h3>
                            <p class="text-muted small mb-4">By {{ $blog->author ? $blog->author->name : 'Admin' }} | {{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</p>
                            <a href="{{ route('web.blogs.show', $blog->slug) }}" class="text-primary fw-bold text-decoration-none">Read Full Guide <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Static Fallback Data -->
                @for($i=1; $i<=3; $i++)
                <div class="col-lg-4 col-md-6">
                    <div class="premium-card border-0">
                        <div class="img-wrapper">
                            <img src="{{ asset('images/travel_guide_1783508300840.jpg') }}" class="card-img-top" alt="Travel Guide">
                        </div>
                        <div class="card-body bg-white rounded-bottom-4">
                            <span class="text-primary fw-bold small text-uppercase mb-2 d-block">Travel Itinerary</span>
                            <h3 class="card-title">The Ultimate 5-Day Upper Peninsula Road Trip</h3>
                            <p class="text-muted small mb-4">By Explorer Team | July 15, 2026</p>
                            <a href="#" class="text-primary fw-bold text-decoration-none">Read Full Guide <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                @endfor
            @endif
        </div>
    </div>
</section>
