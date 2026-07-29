@extends('web.layout.app_layout')

@php
    $pageTitle = $blog->title;
    $metaTitle = $blog->meta_title ?? ($blog->title . ' - Michigan Explorer');
    $metaDescription = $blog->meta_description ?? Str::limit(strip_tags($blog->content), 160);
    $canonicalUrl = $blog->canonical_url ?? route('web.blogs.show', $blog->slug);
    
    if ($blog->featured_image) {
        $heroImage = Str::startsWith($blog->featured_image, ['http://', 'https://']) 
            ? $blog->featured_image 
            : asset($blog->featured_image);
    } else {
        $heroImage = 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1600';
    }
    
    $readTime = ceil(str_word_count(strip_tags($blog->content)) / 200);

    // Fetch Next and Previous Articles locally
    $prevBlog = App\Models\Blog::where('id', '<', $blog->id)
        ->where('status', 'published')
        ->where(function($q) {
            $q->whereNull('published_at')->orWhere('published_at', '<=', now());
        })
        ->orderBy('id', 'desc')
        ->first();

    $nextBlog = App\Models\Blog::where('id', '>', $blog->id)
        ->where('status', 'published')
        ->where(function($q) {
            $q->whereNull('published_at')->orWhere('published_at', '<=', now());
        })
        ->orderBy('id', 'asc')
        ->first();

    // Determine Author Avatar
    $authorAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($blog->author ? $blog->author->name : 'Admin') . '&background=0d6efd&color=fff';
    if ($blog->author && $blog->author->avatar) {
        if (Str::startsWith($blog->author->avatar, ['http://', 'https://'])) {
            $authorAvatar = $blog->author->avatar;
        } elseif (file_exists(public_path($blog->author->avatar))) {
            $authorAvatar = asset($blog->author->avatar);
        }
    }
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
@endsection

@section('custom_schema')
@if(!isset($blog->seo) || !$blog->seo->schema_markup)
<!-- JSON-LD Schema -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BlogPosting",
  "@@id": "{{ $canonicalUrl }}#blogposting",
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ $canonicalUrl }}"
  },
  "headline": {!! json_encode($blog->title) !!},
  "alternativeHeadline": {!! json_encode($blog->meta_title ?? $blog->title) !!},
  "description": {!! json_encode($blog->excerpt ?? Str::limit(strip_tags($blog->content), 160)) !!},
  "author": {
    "@@type": "Person",
    "name": "{{ $blog->author ? $blog->author->name : 'Michigan Explorer' }}",
    "url": "{{ $blog->author && $blog->author->facebook ? (Str::startsWith($blog->author->facebook, ['http://', 'https://']) ? $blog->author->facebook : 'https://' . $blog->author->facebook) : route('web.home') }}"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "Michigan Explorer",
    "url": "{{ route('web.home') }}",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}",
      "width": 512,
      "height": 512
    }
  },
  "datePublished": "{{ \Carbon\Carbon::parse($blog->published_at ?? $blog->created_at)->toIso8601String() }}",
  "dateModified": "{{ \Carbon\Carbon::parse($blog->updated_at ?? $blog->created_at)->toIso8601String() }}",
  "url": "{{ $canonicalUrl }}",
  "articleSection": "{{ $blog->category ? $blog->category->name : 'Travel' }}",
  "isAccessibleForFree": true,
  "genre": "Blog"
}
</script>
@endif

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
@if($blog->faqs && $blog->faqs->count() > 0)
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "FAQPage",
  "mainEntity": [
    @foreach($blog->faqs as $faq)
    {
      "@@type": "Question",
      "name": {!! json_encode($faq->question) !!},
      "acceptedAnswer": {
        "@@type": "Answer",
        "text": {!! json_encode(strip_tags($faq->answer)) !!}
      }
    }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endif
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
                
                <nav aria-label="breadcrumb" class="mb-3 fade-up-anim">
                    <ol class="breadcrumb justify-content-center text-white opacity-75 small text-uppercase align-items-center">
                        <li class="breadcrumb-item"><a href="{{ route('web.home') }}" class="text-white text-decoration-none">Home</a></li>
                        <span class="mx-2 text-white-50">/</span>
                        <li class="breadcrumb-item"><a href="{{ route('web.blogs.index') }}" class="text-white text-decoration-none">Travel Guides</a></li>
                        <span class="mx-2 text-white-50">/</span>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $blog->title }}</li>
                    </ol>
                </nav>

                @if($blog->category)
                <div class="mb-3 fade-up-anim">
                    <a href="{{ route('web.blogs.index') }}?category={{ $blog->category->slug }}" class="badge bg-primary text-white text-uppercase px-3 py-2 rounded-pill fw-bold text-decoration-none shadow-sm" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                        {{ $blog->category->name }}
                    </a>
                </div>
                @endif
                <h1 class="display-3 fw-bold text-white mb-4 editorial-title fade-up-anim auto-style-33">{{ $blog->title }}</h1>
                
                <div class="d-flex flex-wrap align-items-center justify-content-center text-white opacity-90 gap-4 fade-up-anim auto-style-34">
                    <div class="d-flex align-items-center">
                        <img src="{{ $authorAvatar }}" alt="Author" class="rounded-circle me-2 border border-2 border-white shadow-sm auto-style-35">
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
            
            <!-- Left: Floating Author Social Bar -->
            @if($blog->author && ($blog->author->facebook || $blog->author->twitter || $blog->author->linkedin || $blog->author->instagram))
            <div class="col-lg-1 d-none d-lg-block">
                <div class="sticky-top pt-4 auto-style-16">
                    <div class="d-flex flex-column gap-3 align-items-center share-sidebar">
                        <span class="text-muted small fw-bold text-uppercase letter-spacing-1 mb-2 auto-style-36">Share</span>
                        <div class="bg-primary text-white w-100 h-1px mb-2"></div>
                        @if($blog->author->facebook)
                        <a href="{{ Str::startsWith($blog->author->facebook, ['http://', 'https://']) ? $blog->author->facebook : 'https://' . $blog->author->facebook }}" target="_blank" class="share-btn facebook"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if($blog->author->twitter)
                        <a href="{{ Str::startsWith($blog->author->twitter, ['http://', 'https://']) ? $blog->author->twitter : 'https://' . $blog->author->twitter }}" target="_blank" class="share-btn twitter"><i class="fab fa-x-twitter"></i></a>
                        @endif
                        @if($blog->author->linkedin)
                        <a href="{{ Str::startsWith($blog->author->linkedin, ['http://', 'https://']) ? $blog->author->linkedin : 'https://' . $blog->author->linkedin }}" target="_blank" class="share-btn linkedin"><i class="fab fa-linkedin-in"></i></a>
                        @endif
                        @if($blog->author->instagram)
                        <a href="{{ Str::startsWith($blog->author->instagram, ['http://', 'https://']) ? $blog->author->instagram : 'https://' . $blog->author->instagram }}" target="_blank" class="share-btn instagram"><i class="fab fa-instagram"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Center: Blog Content -->
            <div class="col-lg-8 col-xl-7 px-lg-4 px-xl-5">
                <article class="blog-editorial-content mb-5">
                    
                    @if($blog->excerpt)
                    <p class="lead-excerpt">
                        {{ $blog->excerpt }}
                    </p>
                    @endif

                    <div class="content-body" id="article-body">
                        {!! html_entity_decode($blog->content) !!}
                    </div>
                    
                </article>

                <!-- FAQ Section -->
                @if($blog->faqs && $blog->faqs->count() > 0)
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-5">
                    <h3 class="fw-bold mb-4 font-heading text-heading">Frequently Asked Questions</h3>
                    <div class="accordion accordion-flush" id="blogFaq">
                        @foreach($blog->faqs as $index => $faq)
                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="faq{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#blogFaq">
                                <div class="accordion-body text-muted lh-lg">{!! $faq->answer !!}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif


                
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
                            <img src="{{ $authorAvatar }}" alt="{{ $blog->author->avatar_alt ?? $blog->author->name }}" class="rounded-circle object-fit-cover shadow-sm border border-3 border-white auto-style-37">
                            <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border border-2 border-white auto-style-38">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <span class="text-primary fw-bold text-uppercase small letter-spacing-1 mb-1 d-block">{{ $blog->author->designation ?? 'Author & Explorer' }}</span>
                            <h3 class="fw-bold mb-2">{{ $blog->author->name }}</h3>
                            <!-- <p class="text-muted mb-3">{{ $blog->author->bio ?? 'Passionate traveler and local expert sharing the best of Michigan\'s hidden gems and iconic destinations.' }}</p> -->
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-3">
                                <!-- <div class="d-flex gap-3 align-items-center">
                                    @if($blog->author->facebook)
                                    <a href="{{ Str::startsWith($blog->author->facebook, ['http://', 'https://']) ? $blog->author->facebook : 'https://' . $blog->author->facebook }}" target="_blank" class="text-muted hover-text-primary"><i class="fab fa-facebook-f fs-5"></i></a>
                                    @endif
                                    @if($blog->author->twitter)
                                    <a href="{{ Str::startsWith($blog->author->twitter, ['http://', 'https://']) ? $blog->author->twitter : 'https://' . $blog->author->twitter }}" target="_blank" class="text-muted hover-text-primary"><i class="fab fa-x-twitter fs-5"></i></a>
                                    @endif
                                    @if($blog->author->linkedin)
                                    <a href="{{ Str::startsWith($blog->author->linkedin, ['http://', 'https://']) ? $blog->author->linkedin : 'https://' . $blog->author->linkedin }}" target="_blank" class="text-muted hover-text-primary"><i class="fab fa-linkedin-in fs-5"></i></a>
                                    @endif
                                    @if($blog->author->instagram)
                                    <a href="{{ Str::startsWith($blog->author->instagram, ['http://', 'https://']) ? $blog->author->instagram : 'https://' . $blog->author->instagram }}" target="_blank" class="text-muted hover-text-primary"><i class="fab fa-instagram fs-5"></i></a>
                                    @endif
                                </div> -->
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
                <!-- <div class="newsletter-premium-card p-4 p-md-5 mb-5 rounded-4 position-relative overflow-hidden">
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
                </div> -->

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
                <div class="sticky-top pt-4 auto-style-44" style="top: 60px; z-index: 10;">
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
                                    <img src="{{ $related->featured_image ? (Str::startsWith($related->featured_image, ['http://', 'https://']) ? $related->featured_image : asset($related->featured_image)) : 'https://placehold.co/100' }}" class="rounded-3 w-100 h-100 object-fit-cover shadow-sm" alt="{{ $related->title }}">
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

                    <!-- Tags Sidebar Widget -->
                    @if($blog->tags && $blog->tags->count() > 0)
                    <div class="card border-0 rounded-4 shadow-sm mt-4 bg-white border border-light p-4">
                        <h6 class="text-uppercase fw-bold letter-spacing-1 mb-3 text-primary d-flex align-items-center">
                            <i class="fas fa-tags me-2"></i> Tags
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($blog->tags as $tag)
                                <a href="#" class="tag-chip">{{ $tag->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Sidebar Ad / Promo Placeholder -->
                    <!-- <div class="mt-4 rounded-4 overflow-hidden position-relative shadow-sm group hover-shadow-lg transition-all" style="border: 1px solid #f1f5f9;">
                        <a href="#" class="d-block">
                            <img src="{{ asset('images/michigan_explorer_ad.png') }}" class="w-100 object-fit-cover hover-zoom auto-style-45" alt="Explore Michigan Explorer Premium" style="transition: transform 0.3s ease;">
                        </a>
                    </div> -->

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
                                <img src="{{ $related->featured_image ? (Str::startsWith($related->featured_image, ['http://', 'https://']) ? $related->featured_image : asset($related->featured_image)) : 'https://placehold.co/600x400' }}" class="w-100 h-100 object-fit-cover hover-zoom" alt="{{ $related->title }}">
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

{{-- Blog Detail Promotion Banner --}}
<x-promo-banner :promotion="$detailPromotion ?? null" />

<!-- CSS specifically for Blog Editorial Layout -->
<style>
/* ── Premium Editorial Custom Styles ── */
.blog-editorial-content {
    font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
    font-size: 1.15rem !important;
    line-height: 1.95 !important;
    color: #334155 !important;
}

/* Excerpt Card */
.lead-excerpt {
    font-size: 1.25rem !important;
    line-height: 1.8 !important;
    font-weight: 500 !important;
    color: #475569 !important;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
    border-left: 4px solid var(--primary-color) !important;
    padding: 1.75rem 2rem !important;
    border-radius: 12px !important;
    border-bottom: none !important;
    margin-bottom: 1.75rem !important;
    box-shadow: 0 10px 25px rgba(0,0,0,0.02) !important;
}

/* Magazine Headings */
.blog-editorial-content h2 {
    font-size: 2rem !important;
    font-weight: 800 !important;
    letter-spacing: -0.5px !important;
    margin-top: 1.5rem !important;
    margin-bottom: 0.2rem !important;
    position: relative;
    padding-left: 15px;
}
.blog-editorial-content h2::before {
    content: '';
    position: absolute;
    left: 0;
    top: 15%;
    height: 70%;
    width: 4px;
    background: var(--primary-color);
    border-radius: 4px;
}

.blog-editorial-content h3 {
    font-size: 1.6rem !important;
    font-weight: 700 !important;
    margin-top: 1.6rem !important;
    margin-bottom: 0.6rem !important;
}

/* Hide redundant line breaks and empty tags causing extra space */
.blog-editorial-content br {
    display: none !important;
}
.blog-editorial-content p:empty {
    display: none !important;
}

/* Floating Share Bar */
.share-sidebar {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 40px;
    padding: 20px 8px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

.share-btn {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #64748b;
    background: #fff;
    border: 1px solid #e2e8f0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 6px rgba(0,0,0,0.02);
}
.share-btn:hover {
    color: #fff !important;
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.share-btn.facebook:hover { background: #3b5998; border-color: #3b5998; }
.share-btn.twitter:hover { background: #1da1f2; border-color: #1da1f2; }
.share-btn.linkedin:hover { background: #0077b5; border-color: #0077b5; }
.share-btn.instagram:hover { background: #e1306c; border-color: #e1306c; }
.share-btn.pinterest:hover { background: #bd081c; border-color: #bd081c; }
.share-btn.whatsapp:hover { background: #25d366; border-color: #25d366; }
.share-btn.copy-link:hover { background: #475569; border-color: #475569; }

/* Premium Tags chips */
.tag-chip {
    display: inline-flex;
    align-items: center;
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 0.82rem;
    font-weight: 600;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    text-decoration: none;
    transition: all 0.2s ease;
}
.tag-chip:hover {
    background: var(--primary-color);
    color: #fff !important;
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
}

.hover-zoom {
    transition: transform 0.3s ease;
}
.group:hover .hover-zoom {
    transform: scale(1.05);
}

/* Meet the Author Card */
.author-premium-card {
    background: linear-gradient(135deg, #ffffff, #f8fafc) !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 20px !important;
    box-shadow: 0 15px 35px rgba(0,0,0,0.04) !important;
    transition: all 0.3s ease;
}
.author-premium-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 20px 45px rgba(0,0,0,0.06) !important;
}
.author-premium-card .hover-text-primary {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f1f5f9;
    color: #64748b !important;
    transition: all 0.2s ease;
}
.author-premium-card .hover-text-primary:hover {
    background: var(--primary-color);
    color: #fff !important;
}

/* TOC & Sidebar Cards */
.toc-premium-card, .sidebar-widget {
    border-radius: 20px !important;
    border: 1px solid #f1f5f9 !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03) !important;
    background: #fff !important;
}

.toc-nav a {
    display: block;
    font-size: 0.88rem;
    color: #64748b;
    text-decoration: none;
    transition: all 0.2s ease;
    border-left: 2px solid #e2e8f0;
    padding-left: 12px;
    margin-bottom: 8px;
}
.toc-nav a:hover, .toc-nav a.active {
    color: var(--primary-color) !important;
    border-left-color: var(--primary-color);
    padding-left: 16px;
    background: rgba(115,103,240,0.05);
    border-radius: 0 4px 4px 0;
}

/* FAQ Accordion Premium styling */
.accordion-item {
    border-radius: 12px !important;
    margin-bottom: 12px !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.01) !important;
    transition: all 0.2s ease;
}
.accordion-item:hover {
    box-shadow: 0 6px 18px rgba(0,0,0,0.03) !important;
    border-color: #cbd5e1 !important;
}
.accordion-button {
    font-weight: 600 !important;
    color: #1e293b !important;
    padding: 1.25rem 1.5rem !important;
    background-color: #fff !important;
    border-radius: 12px !important;
}
.accordion-button:not(.collapsed) {
    background-color: #f8fafc !important;
    color: var(--primary-color) !important;
    box-shadow: none !important;
    border-bottom: 1px solid #f1f5f9 !important;
}
</style>


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
