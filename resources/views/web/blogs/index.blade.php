@extends('web.layout.app_layout')

@php
    $pageTitle = 'Travel Guides & Stories';
    $metaTitle = 'Travel Guides & Stories - Michigan Explorer';
    $metaDescription = 'Discover expert travel guides, hidden gems, seasonal adventures, local food, road trips, and insider tips across Michigan.';
    $canonicalUrl = route('web.blogs.index');
@endphp

@section('title', $metaTitle)

@section('meta_description')
<meta name="description" content="{{ $metaDescription }}">
@endsection

@section('canonical')
<link rel="canonical" href="{{ $canonicalUrl }}">
@endsection

@section('webLayoutContent')
<!-- 1. Premium Magazine Hero Section -->
<section class="position-relative hero-magazine overflow-hidden" style="height: 500px; padding-top: 80px; background-image: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.85)), url('{{ asset('images/attraction_nature_1783508280642.png') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container h-100 d-flex flex-column justify-content-center align-items-center text-center">
        
        <nav aria-label="breadcrumb" class="mb-3 slide-up-anim auto-style-19">
            <ol class="breadcrumb justify-content-center text-white opacity-75 small text-uppercase letter-spacing-1">
                <li class="breadcrumb-item"><a href="{{ route('web.home') }}" class="text-white text-decoration-none hover-text-accent">Home</a></li>
                <li class="breadcrumb-item active text-white fw-bold" aria-current="page">Travel Guides</li>
            </ol>
        </nav>
        
        <h1 class="display-3 fw-bold text-white mb-3 slide-up-anim auto-style-20">
            Travel Guides & Stories
        </h1>
        
        <p class="lead text-white opacity-90 slide-up-anim mb-0 auto-style-21">
            Discover expert travel guides, hidden gems, seasonal adventures, local food, road trips, and insider tips across Michigan.
        </p>



    </div>
</section>

<!-- 2. Sticky Filter & Sort Section -->
<section class="filter-bar bg-white border-bottom sticky-top shadow-sm z-index-1000 py-3 transition-all" id="stickyFilterBar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-lg-8 mb-3 mb-lg-0 overflow-auto hide-scrollbar">
                <div class="d-flex align-items-center gap-3 filter-scroll-wrapper auto-style-22">
                    <span class="text-muted fw-bold small text-uppercase letter-spacing-1 me-2 d-none d-md-block">Filters:</span>
                    
                    @if(request()->has('q') || request()->has('category') || request()->has('sort'))
                        <a href="{{ route('web.blogs.index') }}" class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill text-decoration-none transition-all hover-bg-danger hover-text-white d-flex align-items-center">
                            <i class="fas fa-times me-2"></i> Clear Filters
                        </a>
                    @endif

                    <div class="dropdown">
                        <button class="btn btn-outline-secondary rounded-pill dropdown-toggle px-4 {{ request('category') ? 'active bg-primary text-white border-primary' : '' }}" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="far fa-compass me-2"></i> {{ request('category') ? ucfirst(str_replace('-', ' ', request('category'))) : 'Category' }}
                        </button>
                        <ul class="dropdown-menu shadow-lg border-0 rounded-4 mt-2">
                            <li><a class="dropdown-item py-2" href="{{ route('web.blogs.index', array_merge(request()->query(), ['category' => null])) }}">All Categories</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach($categories as $cat)
                                <li>
                                    <a class="dropdown-item py-2 d-flex justify-content-between align-items-center {{ request('category') == $cat->slug ? 'active bg-primary text-white' : '' }}" href="{{ route('web.blogs.index', array_merge(request()->query(), ['category' => $cat->slug])) }}">
                                        <span>@if($cat->icon) <i class="{{ $cat->icon }} me-2 opacity-75"></i> @endif {{ $cat->name }}</span>
                                        <span class="badge {{ request('category') == $cat->slug ? 'bg-white text-primary' : 'bg-light text-muted' }} rounded-pill ms-3">{{ $cat->blogs_count }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-outline-secondary rounded-pill dropdown-toggle px-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-leaf me-2"></i> Season
                        </button>
                        <ul class="dropdown-menu shadow-lg border-0 rounded-4 mt-2">
                            <li><a class="dropdown-item py-2" href="#">All Seasons</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2" href="#"><i class="fas fa-sun text-warning me-2"></i> Summer</a></li>
                            <li><a class="dropdown-item py-2" href="#"><i class="fas fa-snowflake text-info me-2"></i> Winter</a></li>
                            <li><a class="dropdown-item py-2" href="#"><i class="fas fa-leaf text-success me-2"></i> Spring</a></li>
                            <li><a class="dropdown-item py-2" href="#"><i class="fab fa-canadian-maple-leaf text-danger me-2"></i> Fall</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 d-flex justify-content-lg-end">
                <div class="d-flex align-items-center bg-light rounded-pill p-1 border">
                    <span class="text-muted small fw-bold px-3 d-none d-sm-block">Sort by:</span>
                    <a href="{{ route('web.blogs.index', array_merge(request()->query(), ['sort' => 'latest'])) }}" class="btn btn-sm rounded-pill px-4 {{ request('sort', 'latest') == 'latest' ? 'btn-white shadow-sm fw-bold text-primary' : 'btn-light text-muted border-0' }}">Latest</a>
                    <a href="{{ route('web.blogs.index', array_merge(request()->query(), ['sort' => 'popular'])) }}" class="btn btn-sm rounded-pill px-4 {{ request('sort') == 'popular' ? 'btn-white shadow-sm fw-bold text-primary' : 'btn-light text-muted border-0' }}">Popular</a>
                    <a href="{{ route('web.blogs.index', array_merge(request()->query(), ['sort' => 'oldest'])) }}" class="btn btn-sm rounded-pill px-4 {{ request('sort') == 'oldest' ? 'btn-white shadow-sm fw-bold text-primary' : 'btn-light text-muted border-0' }}">Oldest</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Main Content Layout -->
<section class="py-5 bg-body-tertiary auto-style-23">
    <div class="container py-3">
        <div class="row g-5">
            
            <!-- Left Content: Blogs -->
            <div class="col-lg-8">
                
                @if($featuredBlog && !request()->has('category') && request('page', 1) == 1 && request('sort', 'latest') == 'latest')
                <!-- Premium Featured Article -->
                <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-5 featured-card-anim">
                    <div class="row g-0">
                        <div class="col-md-6 overflow-hidden">
                            <a href="{{ route('web.blogs.show', $featuredBlog->slug) }}" class="d-block h-100">
                                <img src="{{ $featuredBlog->featured_image ? asset($featuredBlog->featured_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800' }}" loading="lazy" class="img-fluid h-100 w-100 object-fit-cover hover-zoom-img" alt="{{ $featuredBlog->title }}">
                            </a>
                        </div>
                        <div class="col-md-6 p-4 p-lg-5 d-flex flex-column justify-content-center bg-white position-relative">
                            
                            <!-- Star Badge -->
                            <div class="position-absolute top-0 end-0 p-4">
                                <div class="bg-accent text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm auto-style-10" data-bs-toggle="tooltip" title="Featured Story">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>

                            @if($featuredBlog->category)
                                <a href="?category={{ $featuredBlog->category->slug }}" class="badge bg-primary bg-opacity-10 text-white text-uppercase px-3 py-2 rounded-pill fw-bold text-decoration-none align-self-start mb-3 transition-all hover-bg-primary hover-text-white d-flex align-items-center">
                                    @if($featuredBlog->category->icon) <i class="{{ $featuredBlog->category->icon }} me-2"></i> @endif
                                    {{ $featuredBlog->category->name }}
                                </a>
                            @endif
                            <h2 class="h3 fw-bold mb-3 auto-style-7">
                                <a href="{{ route('web.blogs.show', $featuredBlog->slug) }}" class="text-dark text-decoration-none hover-text-primary transition-all">{{ $featuredBlog->title }}</a>
                            </h2>
                            <p class="text-muted mb-4 fs-6 lh-lg">{{ $featuredBlog->excerpt ?? Str::limit(strip_tags($featuredBlog->content), 120) }}</p>
                            
                            <div class="d-flex align-items-center text-muted small mb-4 fw-bold">
                                <span class="me-4"><i class="far fa-clock me-1 text-primary"></i> {{ $featuredBlog->reading_time ?? ceil(str_word_count(strip_tags($featuredBlog->content)) / 200) }} min read</span>
                                <span><i class="far fa-eye me-1 text-primary"></i> {{ number_format($featuredBlog->views ?? 0) }} views</span>
                            </div>

                            <div class="mt-auto d-flex align-items-center justify-content-between border-top pt-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $featuredBlog->author && $featuredBlog->author->avatar ? asset($featuredBlog->author->avatar) : 'https://placehold.co/100' }}" class="rounded-circle me-3 shadow-sm border border-2 border-white object-fit-cover w-45px h-45px" alt="Author">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $featuredBlog->author ? $featuredBlog->author->name : 'Admin' }}</div>
                                        <div class="text-muted small">{{ $featuredBlog->published_at ? \Carbon\Carbon::parse($featuredBlog->published_at)->format('M d, Y') : $featuredBlog->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('web.blogs.show', $featuredBlog->slug) }}" class="btn btn-outline-primary rounded-circle shadow-sm hover-bg-primary transition-all d-flex align-items-center justify-content-center auto-style-24">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Standard Blogs Grid -->
                <div class="row g-4">
                    @forelse($blogs as $blog)
                        @if($featuredBlog && $blog->id == $featuredBlog->id && !request()->has('category') && request('page', 1) == 1 && request('sort', 'latest') == 'latest')
                            @continue
                        @endif
                        <div class="col-md-6 fade-up-card">
                            <div class="card h-100 border-0 rounded-4 shadow-sm hover-lift transition-all bg-white overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href="{{ route('web.blogs.show', $blog->slug) }}" class="d-block h-100">
                                        <img src="{{ $blog->featured_image ? asset($blog->featured_image) : 'https://placehold.co/600x400/e9ecef/495057?text=No+Image' }}" loading="lazy" class="card-img-top blog-img object-fit-cover hover-zoom-img auto-style-25" alt="{{ $blog->title }}">
                                    </a>
                                    
                                    <!-- Badges -->
                                    <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-2">
                                        @if($blog->category)
                                            <a href="?category={{ $blog->category->slug }}" class="badge bg-white text-primary px-3 py-2 rounded-pill shadow-sm text-decoration-none fw-bold hover-bg-primary hover-text-white transition-all d-flex align-items-center glass-badge">
                                                @if($blog->category->icon) <i class="{{ $blog->category->icon }} me-2"></i> @endif
                                                {{ $blog->category->name }}
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Action Buttons overlay -->
                                    <div class="position-absolute top-0 end-0 m-3 d-flex flex-column gap-2 opacity-0 card-actions transition-all">
                                        <button class="btn btn-light rounded-circle shadow-sm text-muted hover-text-primary p-0 d-flex align-items-center justify-content-center auto-style-26" data-bs-toggle="tooltip" title="Bookmark">
                                            <i class="far fa-bookmark"></i>
                                        </button>
                                        <button class="btn btn-light rounded-circle shadow-sm text-muted hover-text-primary p-0 d-flex align-items-center justify-content-center auto-style-26" data-bs-toggle="tooltip" title="Share">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex align-items-center text-muted small mb-3 fw-bold">
                                        <span class="me-3"><i class="far fa-clock me-1 text-primary"></i> {{ $blog->reading_time ?? ceil(str_word_count(strip_tags($blog->content)) / 200) }} min read</span>
                                        <span><i class="far fa-eye me-1 text-primary"></i> {{ number_format($blog->views ?? 0) }}</span>
                                    </div>

                                    <h5 class="card-title fw-bold mb-3 auto-style-7">
                                        <a href="{{ route('web.blogs.show', $blog->slug) }}" class="text-dark text-decoration-none hover-text-primary transition-all">{{ $blog->title }}</a>
                                    </h5>
                                    <p class="card-text text-muted mb-4 flex-grow-1">{{ $blog->excerpt ? $blog->excerpt : Str::limit(strip_tags($blog->content), 90) }}</p>
                                    
                                    <div class="mt-auto d-flex align-items-center justify-content-between border-top pt-3">
                                        <div class="d-flex align-items-center">
                                            @if($blog->author)
                                            <img src="{{ $blog->author && $blog->author->avatar ? asset($blog->author->avatar) : 'https://placehold.co/100' }}" class="rounded-circle me-2 shadow-sm border border-2 border-white object-fit-cover w-35px h-35px" alt="Author">
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark small lh-1 mb-1">{{ $blog->author ? $blog->author->name : 'Admin' }}</div>
                                                <div class="text-muted auto-style-11">{{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') : $blog->created_at->format('M d, Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Premium Empty State -->
                        <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm border border-light">
                            <div class="mb-4 text-primary opacity-50">
                                <i class="far fa-compass display-1"></i>
                            </div>
                            <h3 class="fw-bold mb-3">No travel guides found.</h3>
                            <p class="text-muted lead mb-4 mx-auto auto-style-27">We couldn't find any articles matching your current filters or search criteria. Try adjusting your search or explore our popular categories.</p>
                            <a href="{{ route('web.blogs.index') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm hover-lift">Browse All Categories</a>
                        </div>
                    @endforelse
                </div>

                <!-- Premium Pagination -->
                @if($blogs->hasPages())
                <div class="d-flex justify-content-center mt-5 pt-4">
                    <nav aria-label="Blog pagination">
                        <ul class="pagination pagination-lg premium-pagination mb-0 gap-2">
                            <!-- Previous Page Link -->
                            @if ($blogs->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link rounded-circle border-0 d-flex align-items-center justify-content-center bg-light text-muted"><i class="fas fa-chevron-left"></i></span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link rounded-circle border-0 shadow-sm d-flex align-items-center justify-content-center hover-bg-primary hover-text-white transition-all" href="{{ $blogs->previousPageUrl() }}" rel="prev"><i class="fas fa-chevron-left"></i></a>
                                </li>
                            @endif

                            <!-- Pagination Elements -->
                            @foreach ($blogs->links()->elements as $element)
                                <!-- "Three Dots" Separator -->
                                @if (is_string($element))
                                    <li class="page-item disabled"><span class="page-link border-0 bg-transparent">{{ $element }}</span></li>
                                @endif

                                <!-- Array Of Links -->
                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        @if ($page == $blogs->currentPage())
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link rounded-circle border-0 shadow-sm bg-primary d-flex align-items-center justify-content-center">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link rounded-circle border-0 shadow-sm d-flex align-items-center justify-content-center hover-bg-primary hover-text-white transition-all text-dark bg-white" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                            <!-- Next Page Link -->
                            @if ($blogs->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link rounded-circle border-0 shadow-sm d-flex align-items-center justify-content-center hover-bg-primary hover-text-white transition-all" href="{{ $blogs->nextPageUrl() }}" rel="next"><i class="fas fa-chevron-right"></i></a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link rounded-circle border-0 d-flex align-items-center justify-content-center bg-light text-muted"><i class="fas fa-chevron-right"></i></span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif

            </div>

            <!-- Right Content: Premium Sidebar -->
            <div class="col-lg-4">
                <div class="position-sticky auto-style-16">
                    
                  

                    <!-- Premium Categories -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white sidebar-widget">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4 d-flex align-items-center"><i class="fas fa-layer-group text-primary me-2"></i> Categories</h5>
                            <div class="d-flex flex-column gap-3">
                                @foreach($categories as $cat)
                                <a href="?category={{ $cat->slug }}" class="text-decoration-none transition-all hover-lift">
                                    <div class="p-3 rounded-4 border border-light bg-light hover-bg-primary-light d-flex align-items-center justify-content-between group">
                                        <div class="d-flex align-items-center text-dark group-hover-primary">
                                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm text-primary me-3 transition-all group-hover-bg-primary group-hover-text-white auto-style-10">
                                                <i class="{{ $cat->icon ?? 'fas fa-hashtag' }}"></i>
                                            </div>
                                            <span class="fw-bold">{{ $cat->name }}</span>
                                        </div>
                                        <span class="badge bg-white text-muted shadow-sm rounded-pill px-3 py-2 transition-all group-hover-bg-primary group-hover-text-white border border-light">{{ $cat->blogs_count }}</span>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Most Viewed -->
                    @if(isset($mostViewedBlogs) && $mostViewedBlogs->count() > 0)
                    <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white sidebar-widget">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4 d-flex align-items-center"><i class="fas fa-fire text-danger me-2"></i> Most Viewed</h5>
                            <div class="d-flex flex-column gap-3">
                                @foreach($mostViewedBlogs as $mvb)
                                <a href="{{ route('web.blogs.show', $mvb->slug) }}" class="text-decoration-none text-dark d-flex align-items-center hover-text-primary transition-all group">
                                    <img src="{{ $mvb->featured_image ? asset($mvb->featured_image) : 'https://placehold.co/100' }}" loading="lazy" class="rounded-3 object-fit-cover shadow-sm transition-all group-hover-lift auto-style-28" alt="{{ $mvb->title }}">
                                    <div class="ms-3">
                                        <h6 class="fw-bold mb-1 lh-base group-hover-primary auto-style-7">{{ Str::limit($mvb->title, 45) }}</h6>
                                        <div class="d-flex align-items-center text-muted small fw-bold">
                                            <i class="far fa-eye text-primary me-1"></i> {{ number_format($mvb->views ?? 0) }} Views
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Recent Posts -->
                    @if(isset($recentBlogs) && $recentBlogs->count() > 0)
                    <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white sidebar-widget">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4 d-flex align-items-center"><i class="far fa-clock text-primary me-2"></i> Recent Articles</h5>
                            <div class="d-flex flex-column gap-3">
                                @foreach($recentBlogs as $rb)
                                <a href="{{ route('web.blogs.show', $rb->slug) }}" class="text-decoration-none text-dark d-flex align-items-center hover-text-primary transition-all group">
                                    <img src="{{ $rb->featured_image ? asset($rb->featured_image) : 'https://placehold.co/100' }}" loading="lazy" class="rounded-3 object-fit-cover shadow-sm transition-all group-hover-lift auto-style-28" alt="{{ $rb->title }}">
                                    <div class="ms-3">
                                        <h6 class="fw-bold mb-1 lh-base group-hover-primary auto-style-7">{{ Str::limit($rb->title, 45) }}</h6>
                                        <div class="d-flex align-items-center text-muted small fw-bold">
                                            <i class="far fa-calendar-alt text-primary me-1"></i> {{ $rb->published_at ? \Carbon\Carbon::parse($rb->published_at)->format('M d, Y') : $rb->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Popular Tags (Rounded Chips) -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white sidebar-widget">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4 d-flex align-items-center"><i class="fas fa-tags text-primary me-2"></i> Popular Tags</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                <a href="#" class="badge bg-light text-dark border border-light text-decoration-none px-4 py-2 rounded-pill fw-bold hover-bg-primary hover-text-white transition-all shadow-sm hover-lift">{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                 

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom Premium Styles for Blog Index -->


@section('page-script')
<script>
    // Initialize tooltips
    document.addEventListener("DOMContentLoaded", function(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Add subtle shrink effect on sticky filter bar on scroll
        window.addEventListener('scroll', function() {
            var filterBar = document.getElementById('stickyFilterBar');
            if (window.scrollY > 500) {
                filterBar.classList.add('py-2');
                filterBar.classList.remove('py-3');
            } else {
                filterBar.classList.add('py-3');
                filterBar.classList.remove('py-2');
            }
        });
    });
</script>
@endsection
@endsection
