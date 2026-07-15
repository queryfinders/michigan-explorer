@extends('web.layout.app_layout')

@php
    $pageTitle = $blog->title;
    $metaTitle = $blog->meta_title ?? ($blog->title . ' - Michigan Explorer');
    $metaDescription = $blog->meta_description ?? Str::limit(strip_tags($blog->content), 160);
    $canonicalUrl = $blog->canonical_url ?? route('web.blogs.show', $blog->slug);
    $heroImage = $blog->featured_image ? asset($blog->featured_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1600';
    $readTime = ceil(str_word_count(strip_tags($blog->content)) / 200);

    // Fetch Next and Previous Articles locally
    $prevBlog = App\Models\Blog::where('id', '<', $blog->id)
        ->where('status', 'published')
        ->orderBy('id', 'desc')
        ->first();

    $nextBlog = App\Models\Blog::where('id', '>', $blog->id)
        ->where('status', 'published')
        ->orderBy('id', 'asc')
        ->first();
@endphp

@section('title', $metaTitle)

@section('meta_description')
<meta name="description" content="{{ $metaDescription }}">
@endsection

@section('canonical')
<link rel="canonical" href="{{ $canonicalUrl }}">
@endsection

@section('og_tags')
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:type" content="article">
<meta property="og:image" content="{{ $heroImage }}">
<meta property="article:published_time" content="{{ $blog->published_at ?? $blog->created_at }}">
@if($blog->author)
<meta property="article:author" content="{{ $blog->author->name }}">
@endif
<meta name="twitter:card" content="summary_large_image">

<!-- JSON-LD Schema -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Article",
  "headline": "{{ $blog->title }}",
  "image": "{{ $heroImage }}",
  "author": {
    "@@type": "Person",
    "name": "{{ $blog->author ? $blog->author->name : 'Michigan Explorer' }}"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "Michigan Explorer",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}"
    }
  },
  "datePublished": "{{ $blog->published_at ?? $blog->created_at }}"
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [{
    "@@type": "ListItem",
    "position": 1,
    "name": "Home",
    "item": "{{ route('web.home') }}"
  },{
    "@@type": "ListItem",
    "position": 2,
    "name": "Travel Guides",
    "item": "{{ route('web.blogs.index') }}"
  },{
    "@@type": "ListItem",
    "position": 3,
    "name": "{{ $blog->title }}",
    "item": "{{ $canonicalUrl }}"
  }]
}
</script>
@endsection

@section('webLayoutContent')



<!-- 1. Editorial Hero Section -->
<section class="editorial-hero position-relative overflow-hidden">
    <!-- Parallax Zoom Background -->
    <div class="hero-bg-parallax">
        <div class="hero-bg-zoom" style="background-image: url('{{ $heroImage }}');"></div>
    </div>
    <div class="hero-overlay-gradient"></div>

    <div class="container position-relative h-100 d-flex flex-column justify-content-center align-items-center text-center pb-5 pt-5 mt-5 z-index-2">
        <div class="row w-100 justify-content-center">
            <div class="col-lg-10 col-xl-9 d-flex flex-column align-items-center">
                
                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb" class="mb-4 fade-up-anim auto-style-19">
                    <ol class="breadcrumb justify-content-center text-white opacity-75 small text-uppercase letter-spacing-1 mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('web.home') }}" class="text-white text-decoration-none hover-text-accent">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('web.blogs.index') }}" class="text-white text-decoration-none hover-text-accent">Travel Guides</a></li>
                        @if($blog->category)
                        <li class="breadcrumb-item"><a href="{{ route('web.blogs.index') }}?category={{ $blog->category->slug }}" class="text-white text-decoration-none hover-text-accent">{{ $blog->category->name }}</a></li>
                        @endif
                    </ol>
                </nav>
                
                @if($blog->category)
                <div class="fade-up-anim auto-style-32">
                    <a href="{{ route('web.blogs.index') }}?category={{ $blog->category->slug }}" class="badge bg-white text-primary text-uppercase px-3 py-2 rounded-pill fw-bold text-decoration-none mb-3 shadow-sm border border-white border-opacity-25">{{ $blog->category->name }}</a>
                </div>
                @endif
                
                <h1 class="display-3 fw-bold text-white mb-4 editorial-title fade-up-anim auto-style-33">{{ $blog->title }}</h1>
                
                <div class="d-flex flex-wrap align-items-center justify-content-center text-white opacity-90 gap-4 fade-up-anim auto-style-34">
                    <div class="d-flex align-items-center">
                        <img src="{{ $blog->author && $blog->author->avatar && file_exists(public_path($blog->author->avatar)) ? asset($blog->author->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($blog->author ? $blog->author->name : 'Admin').'&background=0d6efd&color=fff' }}" alt="Author" class="rounded-circle me-2 border border-2 border-white shadow-sm auto-style-35">
                        <span class="fw-bold">{{ $blog->author ? $blog->author->name : 'Admin' }}</span>
                    </div>
                    <div class="d-flex align-items-center small text-uppercase letter-spacing-1 fw-bold">
                        <i class="far fa-calendar-alt me-2 text-accent"></i>
                        {{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') : $blog->created_at->format('M d, Y') }}
                    </div>
                    <div class="d-flex align-items-center small text-uppercase letter-spacing-1 fw-bold">
                        <i class="far fa-clock me-2 text-accent"></i>
                        {{ $readTime }} min read
                    </div>
                    <div class="d-flex align-items-center small text-uppercase letter-spacing-1 fw-bold">
                        <i class="far fa-eye me-2 text-accent"></i>
                        {{ number_format($blog->views) }} Views
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>

<!-- 2. Main Content Layout -->
<section class="py-5 bg-background">
    <div class="container">
        <div class="row">
            
            <!-- Left: Floating Share Bar -->
            <div class="col-lg-1 d-none d-lg-block">
                <div class="sticky-top pt-4 auto-style-16">
                    <div class="d-flex flex-column gap-3 align-items-center share-sidebar">
                        <span class="text-muted small fw-bold text-uppercase letter-spacing-1 mb-2 auto-style-36">Share</span>
                        <div class="bg-primary text-white w-100 h-1px mb-2"></div>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($canonicalUrl) }}" target="_blank" class="share-btn facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode($canonicalUrl) }}" target="_blank" class="share-btn twitter"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($canonicalUrl) }}&title={{ urlencode($blog->title) }}" target="_blank" class="share-btn linkedin"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://pinterest.com/pin/create/button/?url={{ urlencode($canonicalUrl) }}&media={{ urlencode($heroImage) }}&description={{ urlencode($blog->title) }}" target="_blank" class="share-btn pinterest"><i class="fab fa-pinterest-p"></i></a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' ' . $canonicalUrl) }}" target="_blank" class="share-btn whatsapp"><i class="fab fa-whatsapp"></i></a>
                        <button onclick="navigator.clipboard.writeText('{{ $canonicalUrl }}'); alert('Link Copied!');" class="share-btn copy-link"><i class="fas fa-link"></i></button>
                    </div>
                </div>
            </div>

            <!-- Center: Blog Content -->
            <div class="col-lg-8 col-xl-7 px-lg-4 px-xl-5">
                <article class="blog-editorial-content mb-5">
                    
                    @if($blog->excerpt)
                    <p class="lead-excerpt">
                        {{ $blog->excerpt }}
                    </p>
                    @endif

                    <div class="content-body" id="article-body">
                        {!! $blog->content !!}
                    </div>
                    
                </article>

                <!-- FAQ Section -->
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-5">
                    <h3 class="fw-bold mb-4 font-heading text-heading">Frequently Asked Questions</h3>
                    <div class="accordion accordion-flush" id="blogFaq">
                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    When is the best time to visit Michigan for this trip?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#blogFaq">
                                <div class="accordion-body text-muted lh-18">
                                    Late spring through early autumn (May to October) is ideal. Summer offers beach activities and warm weather, while autumn brings breathtaking fall colors and harvest season at local vineyards.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Are these recommendations family-friendly?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#blogFaq">
                                <div class="accordion-body text-muted lh-18">
                                    Yes! Most locations, parks, and dining options mentioned in our guides offer options suitable for travelers of all ages.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Do I need to book reservations in advance?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#blogFaq">
                                <div class="accordion-body text-muted lh-18">
                                    For popular attractions, restaurants, and hotels, we highly recommend booking 2-4 weeks in advance, especially during the peak summer and fall color seasons.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tags -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-5 pb-4 border-bottom">
                    <span class="fw-bold text-muted me-2 text-uppercase small letter-spacing-1">Tags:</span>
                    @forelse($blog->tags as $tag)
                        <a href="#" class="tag-chip">{{ $tag->name }}</a>
                    @empty
                        <span class="text-muted small">No tags</span>
                    @endforelse
                </div>
                
                <!-- Mobile Share (Hidden on Desktop) -->
                <div class="d-flex d-lg-none flex-wrap align-items-center gap-2 mb-5">
                    <span class="fw-bold text-muted me-2 text-uppercase small letter-spacing-1">Share:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($canonicalUrl) }}" target="_blank" class="share-btn-mobile facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode($canonicalUrl) }}" target="_blank" class="share-btn-mobile twitter"><i class="fab fa-x-twitter"></i></a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($canonicalUrl) }}&title={{ urlencode($blog->title) }}" target="_blank" class="share-btn-mobile linkedin"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' ' . $canonicalUrl) }}" target="_blank" class="share-btn-mobile whatsapp"><i class="fab fa-whatsapp"></i></a>
                </div>

                <!-- Premium Author Card -->
                @if($blog->author)
                <div class="author-premium-card p-4 p-md-5 mb-5 rounded-4 border-0">
                    <div class="d-flex flex-column flex-md-row align-items-center text-center text-md-start">
                        <div class="position-relative mb-4 mb-md-0 me-md-4">
                            <img src="{{ $blog->author && $blog->author->avatar && file_exists(public_path($blog->author->avatar)) ? asset($blog->author->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($blog->author->name).'&background=0d6efd&color=fff&size=150' }}" alt="{{ $blog->author->name }}" class="rounded-circle object-fit-cover shadow-sm border border-3 border-white auto-style-37">
                            <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border border-2 border-white auto-style-38">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <span class="text-primary fw-bold text-uppercase small letter-spacing-1 mb-1 d-block">Author & Explorer</span>
                            <h3 class="fw-bold mb-2">{{ $blog->author->name }}</h3>
                            <p class="text-muted mb-3">{{ $blog->author->bio ?? 'Passionate traveler and local expert sharing the best of Michigan\'s hidden gems and iconic destinations.' }}</p>
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-3">
                                <a href="#" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-bold">View Profile</a>
                                <div class="d-flex gap-2">
                                    <a href="#" class="text-muted hover-text-primary"><i class="fab fa-instagram fs-5"></i></a>
                                    <a href="#" class="text-muted hover-text-primary"><i class="fab fa-x-twitter fs-5"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Next / Previous Navigation -->
                <div class="row g-3 mb-5">
                    <div class="col-md-6">
                        @if($prevBlog)
                        <a href="{{ route('web.blogs.show', $prevBlog->slug) }}" class="article-nav-card text-start h-100">
                            <span class="text-uppercase small letter-spacing-1 text-muted fw-bold mb-2 d-block"><i class="fas fa-arrow-left me-2"></i> Previous Article</span>
                            <h6 class="fw-bold mb-0 text-dark">{{ Str::limit($prevBlog->title, 50) }}</h6>
                        </a>
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if($nextBlog)
                        <a href="{{ route('web.blogs.show', $nextBlog->slug) }}" class="article-nav-card text-end h-100">
                            <span class="text-uppercase small letter-spacing-1 text-muted fw-bold mb-2 d-block">Next Article <i class="fas fa-arrow-right ms-2"></i></span>
                            <h6 class="fw-bold mb-0 text-dark">{{ Str::limit($nextBlog->title, 50) }}</h6>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Newsletter Callout -->
                <div class="newsletter-premium-card p-4 p-md-5 mb-5 rounded-4 position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0 opacity-10 auto-style-39">
                        <i class="fas fa-paper-plane auto-style-40"></i>
                    </div>
                    <div class="position-relative z-index-1 text-center">
                        <span class="badge bg-white text-primary text-uppercase letter-spacing-1 px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">Join the club</span>
                        <h3 class="fw-bold mb-3">Get Michigan Travel Tips Weekly</h3>
                        <p class="text-muted mb-4 mx-auto auto-style-41">Subscribe to our newsletter for exclusive itineraries, hidden gems, and seasonal travel inspiration.</p>
                        <form class="d-flex mx-auto shadow-sm rounded-pill p-1 bg-white auto-style-42">
                            <input type="email" class="form-control border-0 rounded-pill px-4" placeholder="Your email address" required>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Subscribe</button>
                        </form>
                    </div>
                </div>

                <!-- Comments Placeholder -->
                <!-- <div class="comments-section mb-5">
                    <h3 class="fw-bold mb-4 border-bottom pb-3">Comments <span class="text-muted fs-5 fw-normal">(1)</span></h3>
                    
                    <div class="comment-item d-flex mb-4">
                        <img src="https://placehold.co/80" alt="User" class="rounded-circle me-3 auto-style-43">
                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <h6 class="fw-bold mb-0 me-2">Sarah Jenkins</h6>
                                <span class="text-muted small">Oct 12, 2026</span>
                            </div>
                            <p class="mb-2 text-muted">This is such a fantastic guide! I've been looking for these exact hidden gems for my upcoming trip to the UP. Thank you so much for sharing!</p>
                            <button class="btn btn-sm btn-link text-primary p-0 text-decoration-none fw-bold small">Reply</button>
                        </div>
                    </div>

                    <div class="leave-reply bg-white p-4 rounded-4 shadow-sm border border-light">
                        <h5 class="fw-bold mb-3">Leave a Reply</h5>
                        <p class="small text-muted mb-3">Your email address will not be published. Required fields are marked *</p>
                        <form>
                            <div class="mb-3">
                                <textarea class="form-control rounded-3" rows="4" placeholder="Write your comment here..." required></textarea>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control rounded-pill px-4" placeholder="Name *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control rounded-pill px-4" placeholder="Email *" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold">Post Comment</button>
                        </form>
                    </div>
                </div> -->

            </div>

            <!-- Right: Table of Contents -->
            <div class="col-lg-3 col-xl-4 d-none d-lg-block">
                <div class="sticky-top pt-4 auto-style-44">
                    <div class="toc-premium-card p-4 rounded-4 shadow-sm bg-white border border-light">
                        <h6 class="text-uppercase fw-bold letter-spacing-1 mb-3 text-primary d-flex align-items-center">
                            <i class="fas fa-list-ul me-2"></i> In this article
                        </h6>
                        <nav id="toc-nav" class="toc-nav d-flex flex-column gap-2">
                            <!-- JS will inject links here -->
                        </nav>
                    </div>

                    <!-- Related Articles Sidebar Widget -->
                    @if(isset($relatedBlogs) && $relatedBlogs->count() > 0)
                    <div class="card border-0 rounded-4 shadow-sm mt-4 bg-white border border-light p-4">
                        <h6 class="text-uppercase fw-bold letter-spacing-1 mb-3 text-primary d-flex align-items-center">
                            <i class="fas fa-bookmark me-2"></i> Related Articles
                        </h6>
                        <div class="d-flex flex-column gap-3">
                            @foreach($relatedBlogs->take(3) as $related)
                            <div class="d-flex align-items-center gap-3 pb-2 border-bottom border-light">
                                <a href="{{ route('web.blogs.show', $related->slug) }}" class="flex-shrink-0" style="width: 70px; height: 70px;">
                                    <img src="{{ $related->featured_image ? asset($related->featured_image) : 'https://placehold.co/100' }}" class="rounded-3 w-100 h-100 object-fit-cover shadow-sm" alt="{{ $related->title }}">
                                </a>
                                <div>
                                    <h6 class="mb-1 fw-bold" style="font-size: 0.9rem; line-height: 1.3;">
                                        <a href="{{ route('web.blogs.show', $related->slug) }}" class="text-dark text-decoration-none hover-text-primary transition-all">{{ Str::limit($related->title, 45) }}</a>
                                    </h6>
                                    <span class="text-muted small">{{ $related->published_at ? \Carbon\Carbon::parse($related->published_at)->format('M d, Y') : $related->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Sidebar Ad / Promo Placeholder -->
                    <div class="mt-4 rounded-4 overflow-hidden position-relative shadow-sm group">
                        <img src="{{ asset('images/michigan_explorer_ad.png') }}" class="w-100 object-fit-cover auto-style-45" alt="Promo">
                        <div class="position-absolute top-0 start-0 w-100 h-100 auto-style-46"></div>
                        <div class="position-absolute bottom-0 start-0 w-100 p-4 text-white">
                            <span class="badge bg-accent text-dark mb-2 rounded-pill fw-bold">Discover</span>
                            <h5 class="fw-bold mb-2">Michigan Explorer Premium</h5>
                            <a href="#" class="text-white text-decoration-none fw-bold small text-uppercase letter-spacing-1">Learn More <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Related Articles (Premium UI) -->
        @if(isset($relatedBlogs) && $relatedBlogs->count() > 0)
        <div class="mt-5 pt-5 border-top">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="fw-bold mb-0">More Like This</h3>
                <a href="{{ route('web.blogs.index') }}{{ $blog->category ? '?category='.$blog->category->slug : '' }}" class="btn btn-outline-primary rounded-pill px-4 d-none d-md-inline-flex">View All</a>
            </div>
            
            <div class="row g-4">
                @foreach($relatedBlogs->take(3) as $related)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 rounded-4 shadow-sm hover-shadow-lg transition-all blog-card-premium overflow-hidden">
                        <div class="position-relative overflow-hidden auto-style-47">
                            <a href="{{ route('web.blogs.show', $related->slug) }}" class="d-block w-100 h-100">
                                <img src="{{ $related->featured_image ? asset($related->featured_image) : 'https://placehold.co/600x400' }}" class="w-100 h-100 object-fit-cover hover-zoom" alt="{{ $related->title }}">
                            </a>
                            @if($related->category)
                            <div class="position-absolute top-0 start-0 m-3">
                                <a href="{{ route('web.blogs.index') }}?category={{ $related->category->slug }}" class="badge bg-white text-dark text-uppercase px-3 py-2 rounded-pill fw-bold text-decoration-none shadow-sm">{{ $related->category->name }}</a>
                            </div>
                            @endif
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="card-title fw-bold mb-3 auto-style-48">
                                <a href="{{ route('web.blogs.show', $related->slug) }}" class="text-dark text-decoration-none hover-text-primary transition-all">{{ Str::limit($related->title, 60) }}</a>
                            </h5>
                            <p class="text-muted small mb-4 flex-grow-1">{{ Str::limit(strip_tags($related->excerpt ?? $related->content), 100) }}</p>
                            <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top border-light">
                                <div class="d-flex align-items-center text-muted small fw-bold">
                                    <i class="far fa-calendar-alt me-2 text-primary"></i> 
                                    {{ $related->published_at ? \Carbon\Carbon::parse($related->published_at)->format('M d') : $related->created_at->format('M d') }}
                                </div>
                                <div class="d-flex align-items-center text-muted small fw-bold">
                                    <i class="far fa-clock me-2 text-accent"></i> 
                                    {{ ceil(str_word_count(strip_tags($related->content)) / 200) }} min
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4 d-md-none">
                <a href="{{ route('web.blogs.index') }}{{ $blog->category ? '?category='.$blog->category->slug : '' }}" class="btn btn-outline-primary rounded-pill px-4 w-100">View All</a>
            </div>
        </div>
        @endif

    </div>
</section>

<!-- CSS specifically for Blog Editorial Layout -->


<!-- JavaScript for Interactive Features -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 2. Auto-generate Table of Contents
    const articleBody = document.getElementById('article-body');
    const tocNav = document.getElementById('toc-nav');
    
    if(articleBody && tocNav) {
        const headings = articleBody.querySelectorAll('h2, h3');
        if(headings.length > 0) {
            headings.forEach((heading, index) => {
                // Assign ID to heading if it doesn't have one
                if(!heading.id) {
                    heading.id = 'heading-' + index;
                }
                
                // Create link
                const link = document.createElement('a');
                link.href = '#' + heading.id;
                link.textContent = heading.textContent;
                link.className = heading.tagName.toLowerCase() === 'h3' ? 'toc-h3' : 'toc-h2';
                
                // Smooth scroll click event
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                });
                
                tocNav.appendChild(link);
            });

            // Intersection Observer to highlight active TOC item
            const observerOptions = {
                root: null,
                rootMargin: '0px 0px -60% 0px',
                threshold: 1.0
            };

            let currentActiveLink = null;
            
            // Simpler scroll listener for TOC highlighting since IntersectionObserver can be tricky for tall sections
            window.addEventListener('scroll', () => {
                let currentHeading = null;
                headings.forEach(heading => {
                    const rect = heading.getBoundingClientRect();
                    // If heading is above middle of screen
                    if(rect.top < window.innerHeight / 2) {
                        currentHeading = heading;
                    }
                });

                if(currentHeading) {
                    const id = currentHeading.getAttribute('id');
                    const links = tocNav.querySelectorAll('a');
                    links.forEach(l => l.classList.remove('active'));
                    const activeLink = tocNav.querySelector(`a[href="#${id}"]`);
                    if(activeLink) activeLink.classList.add('active');
                }
            });
            
        } else {
            // Hide TOC if no headings
            tocNav.parentElement.style.display = 'none';
        }
    }
});
</script>
@endsection
