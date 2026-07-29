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
{{-- 1. HERO --}}
<section class="blog-hero position-relative overflow-hidden" style="height:520px;padding-top:80px;background-image:linear-gradient(to bottom,rgba(0,0,0,0.25),rgba(0,0,0,0.82)),url('{{ asset('images/attraction_nature_1783508280642.png') }}');background-size:cover;background-position:center;background-attachment:fixed;">
    <div class="container h-100 d-flex flex-column justify-content-center align-items-center text-center">
        <nav aria-label="breadcrumb" class="mb-3 slide-up-anim">
            <ol class="breadcrumb justify-content-center text-white opacity-75 small text-uppercase">
                <li class="breadcrumb-item"><a href="{{ route('web.home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white fw-bold" aria-current="page">Travel Guides</li>
            </ol>
        </nav>
        <h1 class="display-3 fw-bold text-white mb-3 slide-up-anim">
            Travel Guides &amp; Stories
        </h1>
        <p class="lead text-white mb-4 slide-up-anim" style="max-width:540px;opacity:0.9;">
            Expert guides, hidden gems &amp; seasonal adventures across Michigan.
        </p>
        <!-- <div class="hero-search-bar w-100 slide-up-anim" style="max-width:580px;">
            <form action="{{ route('web.blogs.index') }}" method="GET" role="search" class="d-flex w-100 align-items-center m-0">
                <i class="fas fa-search text-muted ms-3 fs-5"></i>
                <input type="text" name="q" class="border-0 bg-transparent flex-grow-1 px-3" style="outline: none; font-size:1rem; color: #333;"
                       placeholder="Search guides, destinations, tips…" value="{{ request('q') }}" aria-label="Search">
                <button class="btn btn-primary rounded-pill px-4 py-2 fw-bold border-0" type="submit">Search</button>
            </form>
        </div> -->
        <!-- <div class="d-flex align-items-center justify-content-center gap-5 mt-4 text-white">
            <div class="text-center"><div class="fs-3 fw-bold lh-1 stat-num" data-target="{{ $totalBlogs }}">0</div><div class="small opacity-75 mt-1">Articles</div></div>
            <div class="vr opacity-25" style="height:36px;"></div>
            <div class="text-center"><div class="fs-3 fw-bold lh-1 stat-num" data-target="{{ $categories->count() }}">0</div><div class="small opacity-75 mt-1">Categories</div></div>
            <div class="vr opacity-25" style="height:36px;"></div>
            <div class="text-center"><div class="fs-3 fw-bold lh-1 stat-num" data-target="{{ $totalViews }}">0</div><div class="small opacity-75 mt-1">Total Reads</div></div>
        </div> -->
    </div>
</section>

{{-- 2. FILTER BAR --}}
<section class="category-filter-bar-sticky py-3 border-bottom bg-white shadow-sm transition-all" id="stickyFilterBar" style="z-index:1000; top: 78px;">
    <div class="container">
        <div class="category-bar-inner d-flex flex-column gap-2">
            <h6 class="text-uppercase text-muted fw-bold small mb-0 tracking-wider text-nowrap">Browse by Category</h6>
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 w-100">
                <div class="category-filter-wrapper d-flex align-items-center flex-nowrap gap-1 overflow-x-auto hide-scrollbar flex-grow-1" style="max-width: 100%;">
                    
                    <a href="{{ route('web.blogs.index') }}" class="category-pill {{ !$activeCategory ? 'active' : '' }}">
                        <span class="cat-name">All Articles</span>
                        <span class="cat-count">{{ $totalBlogs }}</span>
                    </a>

                    @php
                        $selectedCategory = $activeCategory;
                        
                        $displayCategories = $categories->take(4);
                        
                        if ($selectedCategory && !$displayCategories->contains('id', $selectedCategory->id)) {
                            $displayCategories = $categories->take(3)->concat([$selectedCategory]);
                        }
                    @endphp

                    @foreach($displayCategories as $cat)
                    <a href="{{ route('web.blogs.category', $cat->slug) }}" class="category-pill {{ ($activeCategory && $activeCategory->id == $cat->id) ? 'active' : '' }}">
                        <span class="cat-name">{{ $cat->name }}</span>
                        <span class="cat-count">{{ $cat->blogs_count }}</span>
                    </a>
                    @endforeach

                    @if($categories->count() > 4)
                    <a href="#" class="category-pill bg-light more-pill" data-bs-toggle="modal" data-bs-target="#categoriesModal">
                        <span class="cat-name">More...</span>
                    </a>
                    @endif

                    @if(request('q'))
                    <a href="{{ route('web.blogs.index', array_merge(request()->except('q'), ['category' => 'all'])) }}" class="category-pill active bg-danger border-danger text-white">
                        <span class="cat-name"><i class="fas fa-search me-1"></i> "{{ Str::limit(request('q'), 15) }}"</span>
                        <span class="cat-count bg-white text-danger px-2"><i class="fas fa-times"></i></span>
                    </a>
                    @endif

                </div>
                <div class="d-flex justify-content-lg-end align-items-center flex-shrink-0">
                    <div class="sort-tabs">
                        <span class="text-muted small fw-bold me-2 ms-2 d-none d-sm-inline">Sort:</span>
                        <a href="?sort=latest" class="sort-tab {{ $activeSort == 'latest' ? 'active' : '' }}">Latest</a>
                        <a href="?sort=popular" class="sort-tab {{ $activeSort == 'popular' ? 'active' : '' }}">Popular</a>
                        <a href="?sort=oldest" class="sort-tab {{ $activeSort == 'oldest' ? 'active' : '' }}">Oldest</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 3. MAIN CONTENT --}}
<section class="py-5 bg-body-tertiary" id="all-blogs">
    <div class="container py-3">
        <div class="row g-5">
            <div class="col-lg-8">

                @if($featuredBlog && !request()->has('category') && !request()->has('q') && request('page', 1) == 1 && request('sort', 'latest') == 'latest')
                <div class="blog-featured-card mb-5">
                    <div class="row g-0">
                        <div class="col-md-6 overflow-hidden position-relative">
                            <a href="{{ route('web.blogs.show', $featuredBlog->slug) }}" class="d-block h-100">
                                <img src="{{ $featuredBlog->featured_image ? asset($featuredBlog->featured_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800' }}" loading="lazy" class="img-fluid h-100 w-100 object-fit-cover blog-img-zoom" alt="{{ $featuredBlog->title }}">
                                <div class="featured-img-overlay"></div>
                            </a>
                            <div class="reading-time-badge"><i class="far fa-clock me-1"></i>{{ $featuredBlog->reading_time ?? ceil(str_word_count(strip_tags($featuredBlog->content)) / 200) }} min read</div>
                        </div>
                        <div class="col-md-6 p-4 p-lg-5 d-flex flex-column justify-content-center bg-white position-relative">
                            <div class="position-absolute top-0 end-0 p-3"><div class="featured-star-badge" data-bs-toggle="tooltip" title="Featured Story"><i class="fas fa-star"></i></div></div>
                            @if($featuredBlog->category)
                            <a href="{{ route('web.blogs.category', $featuredBlog->category->slug) }}" class="blog-cat-badge mb-3 align-self-start">
                                @if($featuredBlog->category->icon)<i class="{{ $featuredBlog->category->icon }} me-1"></i>@endif{{ $featuredBlog->category->name }}
                            </a>
                            @endif
                            <h2 class="h3 fw-bold mb-3"><a href="{{ route('web.blogs.show', $featuredBlog->slug) }}" class="text-dark text-decoration-none hover-text-primary transition-all">{{ $featuredBlog->title }}</a></h2>
                            <p class="text-muted mb-4 lh-lg">{{ $featuredBlog->excerpt ?? Str::limit(strip_tags($featuredBlog->content), 120) }}</p>
                            <div class="d-flex align-items-center gap-3 text-muted small fw-bold mb-4">
                                <span><i class="far fa-eye me-1 text-primary"></i>{{ number_format($featuredBlog->views ?? 0) }} views</span>
                            </div>
                            <div class="mt-auto d-flex align-items-center justify-content-between border-top pt-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $featuredBlog->author && $featuredBlog->author->avatar ? asset($featuredBlog->author->avatar) : 'https://placehold.co/100' }}" class="rounded-circle shadow-sm border border-2 border-white object-fit-cover" style="width:42px;height:42px;" alt="Author">
                                    <div>
                                        <div class="fw-bold text-dark small">{{ $featuredBlog->author ? $featuredBlog->author->name : 'Admin' }}</div>
                                        <div class="text-muted" style="font-size:.75rem;">{{ $featuredBlog->published_at ? \Carbon\Carbon::parse($featuredBlog->published_at)->format('M d, Y') : $featuredBlog->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('web.blogs.show', $featuredBlog->slug) }}" class="btn-read-more" aria-label="Read article"><i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row g-4">
                    @forelse($blogs as $blog)
                        @if($featuredBlog && $blog->id == $featuredBlog->id && !request()->has('category') && !request()->has('q') && request('page', 1) == 1 && request('sort', 'latest') == 'latest')
                            @continue
                        @endif
                        <div class="col-md-6 fade-up-card">
                            <div class="blog-card h-100">
                                <div class="blog-card-img-wrap position-relative overflow-hidden">
                                    <a href="{{ route('web.blogs.show', $blog->slug) }}">
                                        <img src="{{ $blog->featured_image ? asset($blog->featured_image) : 'https://placehold.co/600x400/e9ecef/495057?text=No+Image' }}" loading="lazy" class="blog-card-img blog-img-zoom" alt="{{ $blog->title }}">
                                    </a>
                                
                                    <div class="reading-time-badge"><i class="far fa-clock me-1"></i>{{ $blog->reading_time ?? ceil(str_word_count(strip_tags($blog->content)) / 200) }} min</div>
                                </div>
                                <div class="blog-card-body">
                                    <div class="d-flex align-items-center gap-3 text-muted mb-2" style="font-size:.78rem;font-weight:600;">
                                        <span><i class="far fa-eye me-1 text-primary"></i>{{ number_format($blog->views ?? 0) }}</span>
                                        <span class="ms-auto">{{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <h5 class="blog-card-title"><a href="{{ route('web.blogs.show', $blog->slug) }}" class="text-dark text-decoration-none hover-text-primary transition-all">{{ $blog->title }}</a></h5>
                                    <p class="blog-card-excerpt">{{ $blog->excerpt ? $blog->excerpt : Str::limit(strip_tags($blog->content), 90) }}</p>
                                    <div class="blog-card-footer">
                                        @if($blog->author)
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $blog->author && $blog->author->avatar ? asset($blog->author->avatar) : 'https://placehold.co/100' }}" class="rounded-circle object-fit-cover border border-2 border-white shadow-sm" style="width:32px;height:32px;" alt="{{ $blog->author->name }}">
                                            <span class="fw-bold text-dark" style="font-size:.8rem;">{{ $blog->author->name }}</span>
                                        </div>
                                        @else<div></div>@endif
                                        <a href="{{ route('web.blogs.show', $blog->slug) }}" class="btn-read-more btn-read-more-sm" aria-label="Read more"><i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="blog-empty-state text-center py-5">
                                <div class="mb-4" style="color:#7367f0;opacity:.4;"><i class="far fa-compass" style="font-size:4rem;"></i></div>
                                <h3 class="fw-bold mb-3">No travel guides found</h3>
                                <p class="text-muted lead mb-4 mx-auto" style="max-width:420px;">We could not find articles matching your filters. Try a different category or clear your search.</p>
                                <a href="{{ route('web.blogs.index') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm">Browse All Guides</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($blogs->hasPages())
                <div class="d-flex justify-content-center mt-5 pt-4">
                    <nav aria-label="Blog pagination">
                        <ul class="pagination pagination-lg premium-pagination mb-0 gap-2">
                            @if ($blogs->onFirstPage())
                                <li class="page-item disabled"><span class="page-link rounded-circle border-0 d-flex align-items-center justify-content-center bg-light text-muted" style="width:44px;height:44px;"><i class="fas fa-chevron-left"></i></span></li>
                            @else
                                <li class="page-item"><a class="page-link rounded-circle border-0 shadow-sm d-flex align-items-center justify-content-center hover-bg-primary hover-text-white transition-all" style="width:44px;height:44px;" href="{{ $blogs->previousPageUrl() }}" rel="prev"><i class="fas fa-chevron-left"></i></a></li>
                            @endif
                            @foreach ($blogs->links()->elements as $element)
                                @if (is_string($element))<li class="page-item disabled"><span class="page-link border-0 bg-transparent">{{ $element }}</span></li>@endif
                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        @if ($page == $blogs->currentPage())
                                            <li class="page-item active" aria-current="page"><span class="page-link rounded-circle border-0 shadow-sm bg-primary d-flex align-items-center justify-content-center" style="width:44px;height:44px;">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link rounded-circle border-0 shadow-sm d-flex align-items-center justify-content-center hover-bg-primary hover-text-white transition-all text-dark bg-white" style="width:44px;height:44px;" href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                            @if ($blogs->hasMorePages())
                                <li class="page-item"><a class="page-link rounded-circle border-0 shadow-sm d-flex align-items-center justify-content-center hover-bg-primary hover-text-white transition-all" style="width:44px;height:44px;" href="{{ $blogs->nextPageUrl() }}" rel="next"><i class="fas fa-chevron-right"></i></a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link rounded-circle border-0 d-flex align-items-center justify-content-center bg-light text-muted" style="width:44px;height:44px;"><i class="fas fa-chevron-right"></i></span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif

            </div>{{-- /col-lg-8 --}}

            {{-- SIDEBAR --}}
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 160px; z-index: 10;">

                    <!-- <div class="sidebar-widget mb-4">
                        <div class="sidebar-widget-header"><i class="fas fa-layer-group text-primary me-2"></i> Categories</div>
                        <div class="sidebar-widget-body">
                            @foreach($categories as $cat)
                            @php $catColors=['#7367f0','#28c76f','#ff9f43','#00cfe8','#ea5455','#6c757d']; $catColor=$catColors[$loop->index % count($catColors)]; $isActive=request('category')==$cat->slug; @endphp
                            <a href="?category={{ $cat->slug }}" class="sidebar-cat-item {{ $isActive ? 'active' : '' }}" style="border-left-color:{{ $catColor }};">
                                <div class="sidebar-cat-icon" style="background:{{ $catColor }}22;color:{{ $catColor }};"><i class="{{ $cat->icon ?? 'fas fa-hashtag' }}"></i></div>
                                <span class="sidebar-cat-name">{{ $cat->name }}</span>
                                <span class="sidebar-cat-count" style="background:{{ $catColor }}18;color:{{ $catColor }};">{{ $cat->blogs_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div> -->

                    @if(isset($mostViewedBlogs) && $mostViewedBlogs->count() > 0)
                    <div class="sidebar-widget mb-4">
                        <div class="sidebar-widget-header"><i class="fas fa-fire text-danger me-2"></i> Most Viewed</div>
                        <div class="sidebar-widget-body">
                            @foreach($mostViewedBlogs as $mvb)
                            <a href="{{ route('web.blogs.show', $mvb->slug) }}" class="sidebar-article-item">
                                <div class="sidebar-rank">{{ $loop->index + 1 }}</div>
                                <img src="{{ $mvb->featured_image ? asset($mvb->featured_image) : 'https://placehold.co/100' }}" loading="lazy" class="sidebar-article-img" alt="{{ $mvb->title }}">
                                <div class="sidebar-article-info">
                                    <div class="sidebar-article-title">{{ Str::limit($mvb->title, 45) }}</div>
                                    <div class="sidebar-article-meta"><i class="far fa-eye me-1 text-primary"></i>{{ number_format($mvb->views ?? 0) }} Views</div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(isset($recentBlogs) && $recentBlogs->count() > 0)
                    <div class="sidebar-widget mb-4">
                        <div class="sidebar-widget-header"><i class="far fa-clock text-primary me-2"></i> Recent Articles</div>
                        <div class="sidebar-widget-body">
                            @foreach($recentBlogs as $rb)
                            <a href="{{ route('web.blogs.show', $rb->slug) }}" class="sidebar-article-item">
                                <img src="{{ $rb->featured_image ? asset($rb->featured_image) : 'https://placehold.co/100' }}" loading="lazy" class="sidebar-article-img" alt="{{ $rb->title }}">
                                <div class="sidebar-article-info">
                                    <div class="sidebar-article-title">{{ Str::limit($rb->title, 45) }}</div>
                                    <div class="sidebar-article-meta"><i class="far fa-calendar-alt me-1 text-primary"></i>{{ $rb->published_at ? \Carbon\Carbon::parse($rb->published_at)->format('M d, Y') : $rb->created_at->format('M d, Y') }}</div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(isset($tags) && $tags->count() > 0)
                    <div class="sidebar-widget mb-4">
                        <div class="sidebar-widget-header"><i class="fas fa-tags text-primary me-2"></i> Popular Tags</div>
                        <div class="sidebar-widget-body">
                            <div class="tag-cloud">
                                @foreach($tags as $tag)
                                <a href="{{ route('web.blogs.index', ['tag' => $tag->id]) }}" class="tag-chip">{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>{{-- /col-lg-4 --}}

        </div>
    </div>
</section>

<!-- Categories Modal -->
<div class="modal fade" id="categoriesModal" tabindex="-1" aria-labelledby="categoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold fs-4" id="categoriesModalLabel">All Blog Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <!-- Large Search Input -->
                <div class="position-relative mb-4">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-4 text-muted fs-5"></i>
                    <input type="text" id="categorySearch" class="form-control form-control-lg rounded-pill ps-5 bg-light border-0 py-3" placeholder="Search blog categories..." autocomplete="off">
                </div>

                <!-- Flat Grid Categories -->
                <div id="categoryListContainer">
                    <div class="row g-3">
                        @foreach($categories->sortBy('name') as $cat)
                        <div class="col-md-3 col-sm-6 category-item" data-name="{{ strtolower($cat->name) }}">
                            <a href="{{ route('web.blogs.category', $cat->slug) }}" class="modal-category-card">
                                <div>
                                    <div class="fw-bold text-heading" style="font-size: 0.9rem;">
                                        @if($cat->icon)<i class="{{ $cat->icon }} me-2 opacity-75 text-primary"></i>@endif{{ $cat->name }}
                                    </div>
                                    <div class="text-muted fs-xs mt-1">{{ $cat->blogs_count }} {{ Str::plural('Article', $cat->blogs_count) }}</div>
                                </div>
                                <i class="fas fa-chevron-right text-muted opacity-50 fs-xs"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- No Results State -->
                <div id="noResultsState" class="text-center py-5 d-none">
                    <i class="fas fa-search fa-3x text-muted mb-3 opacity-25"></i>
                    <h5 class="fw-bold text-secondary">No categories found</h5>
                    <p class="text-muted">Try adjusting your search terms.</p>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@700;900&display=swap');
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
.filter-pill { display:inline-flex;align-items:center;gap:4px;padding:6px 16px;border-radius:50px;font-size:.82rem;font-weight:600;border:1.5px solid #dee2e6;background:#fff;color:#555;text-decoration:none;cursor:pointer;transition:all .2s;white-space:nowrap; }
.filter-pill:hover { border-color:var(--primary-color);color:var(--primary-color);background:#eef6ff; }
.filter-pill-active { background:var(--primary-color);color:#fff !important;border-color:var(--primary-color); }
.filter-pill-active:hover { background:#0056b3;border-color:#0056b3; }
.filter-pill-clear { background:#fff3f3;color:#ea5455;border-color:#f5c6c6; }
.filter-pill-clear:hover { background:#ea5455;color:#fff;border-color:#ea5455; }
.filter-count { display:inline-flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.35);width:18px;height:18px;border-radius:50%;font-size:.7rem;font-weight:700;margin-left:4px; }
.sort-tabs { display:inline-flex;align-items:center;background:#f0f0f5;border-radius:50px;padding:4px;border:1px solid #e5e5ef; }
.sort-tab { padding:5px 16px;border-radius:50px;font-size:.82rem;font-weight:600;color:#888;text-decoration:none;transition:all .2s; }
.sort-tab:hover { color:#0d6efd; }
.sort-tab.active { background:#fff;color:#0d6efd;box-shadow:0 2px 8px rgba(13,110,253,.18); }
.blog-img-zoom { transition:transform .55s cubic-bezier(.4,0,.2,1); }
.blog-featured-card:hover .blog-img-zoom,.blog-card:hover .blog-img-zoom { transform:scale(1.06); }
.reading-time-badge { position:absolute;bottom:12px;left:12px;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);color:#fff;font-size:.75rem;font-weight:600;padding:4px 12px;border-radius:50px;display:flex;align-items:center; }
.blog-featured-card { border-radius:20px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,.10);background:#fff;transition:transform .3s,box-shadow .3s; }
.blog-featured-card:hover { transform:translateY(-4px);box-shadow:0 16px 48px rgba(0,0,0,.14); }
.featured-img-overlay { position:absolute;inset:0;background:linear-gradient(135deg,rgba(13,110,253,.1) 0%,transparent 60%);pointer-events:none; }
.blog-cat-badge { display:inline-flex;align-items:center;background:var(--primary-color);color:#fff;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;padding:4px 12px;border-radius:50px;text-decoration:none;transition:background .2s; }
.blog-cat-badge:hover { background:#0056b3;color:#fff; }
.featured-star-badge { width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#ff9f43,#ffd89b);color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(255,159,67,.4);font-size:.85rem; }
.btn-read-more { width:40px;height:40px;border-radius:50%;border:2px solid var(--primary-color);color:var(--primary-color);display:flex;align-items:center;justify-content:center;text-decoration:none;transition:all .2s; }
.btn-read-more:hover { background:var(--primary-color);color:#fff;transform:scale(1.1); }
.btn-read-more-sm { width:34px;height:34px;border-width:1.5px; }
.blog-card { border-radius:16px;overflow:hidden;background:#fff;border:1px solid #f0f0f5;box-shadow:0 2px 12px rgba(0,0,0,.06);transition:transform .3s,box-shadow .3s;display:flex;flex-direction:column; }
.blog-card:hover { transform:translateY(-6px);box-shadow:0 12px 36px rgba(0,0,0,.12); }
.blog-card-img-wrap { height:210px;overflow:hidden;position:relative; }
.blog-card-img { width:100%;height:100%;object-fit:cover;display:block; }
.blog-card-cat-badge { position:absolute;top:12px;left:12px;background:rgba(255,255,255,.92);color:var(--primary-color);font-size:.72rem;font-weight:700;padding:4px 12px;border-radius:50px;text-decoration:none;transition:all .2s;backdrop-filter:blur(4px); }
.blog-card-cat-badge:hover { background:var(--primary-color);color:#fff; }
.blog-card-actions { position:absolute;top:12px;right:12px;display:flex;flex-direction:column;gap:6px;opacity:0;transition:opacity .25s; }
.blog-card:hover .blog-card-actions { opacity:1; }
.blog-action-btn { width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.9);border:none;color:#555;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.8rem;transition:all .2s;box-shadow:0 2px 6px rgba(0,0,0,.12); }
.blog-action-btn:hover { background:var(--primary-color);color:#fff; }
.blog-card-body { padding:20px;flex:1;display:flex;flex-direction:column; }
.blog-card-title { font-size:.95rem;font-weight:700;margin-bottom:8px;line-height:1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; max-height: 2.8em; }
.blog-card-excerpt { color:#6c757d;font-size:.85rem;line-height:1.6;flex:1;margin-bottom:16px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; max-height: 3.2em; }
.blog-card-footer { display:flex;align-items:center;justify-content:space-between;border-top:1px solid #f0f0f5;padding-top:14px;margin-top:auto; }
.sidebar-widget { background:#fff;border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.07);overflow:hidden; }
.sidebar-widget-header { font-size:.95rem;font-weight:700;padding:18px 20px;border-bottom:1px solid #f5f5f8;display:flex;align-items:center; }
.sidebar-widget-body { padding:16px 20px; }
.sidebar-cat-item { display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;border-left:3px solid var(--primary-color);margin-bottom:8px;text-decoration:none;color:#333;background:#fafafa;transition:all .2s; }
.sidebar-cat-item:last-child { margin-bottom:0; }
.sidebar-cat-item:hover,.sidebar-cat-item.active { background:#eef6ff;color:var(--primary-color); }
.sidebar-cat-icon { width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0; }
.sidebar-cat-name { flex:1;font-size:.87rem;font-weight:600; }
.sidebar-cat-count { font-size:.78rem;font-weight:700;padding:2px 10px;border-radius:50px; }
.sidebar-article-item { display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f5f5f8;text-decoration:none;color:#333;transition:all .2s; }
.sidebar-article-item:last-child { border-bottom:none;padding-bottom:0; }
.sidebar-article-item:hover { color:var(--primary-color); }
.sidebar-article-item:hover .sidebar-article-img { transform:scale(1.06); }
.sidebar-rank { width:26px;height:26px;border-radius:8px;background:linear-gradient(135deg,var(--primary-color),#60a5fa);color:#fff;font-size:.72rem;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
.sidebar-article-img { width:64px;height:54px;border-radius:10px;object-fit:cover;flex-shrink:0;transition:transform .3s; }
.sidebar-article-info { flex:1;min-width:0; }
.sidebar-article-title { font-size:.82rem;font-weight:600;line-height:1.4;margin-bottom:4px; }
.sidebar-article-meta { font-size:.72rem;color:#999;font-weight:600; }
.tag-cloud { display:flex;flex-wrap:wrap;gap:8px; }
.tag-chip { display:inline-flex;align-items:center;padding:5px 14px;border-radius:50px;font-size:.78rem;font-weight:600;background:#eef6ff;color:var(--primary-color);border:1px solid #cce3ff;text-decoration:none;transition:all .2s; }
.tag-chip:hover { background:var(--primary-color);color:#fff;border-color:var(--primary-color);transform:translateY(-2px) scale(1.05); }
.blog-empty-state { background:#fff;border-radius:20px;border:1px solid #f0f0f5; }
</style>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el){ new bootstrap.Tooltip(el); });
    var filterBar = document.getElementById('stickyFilterBar');
    window.addEventListener('scroll', function() {
        if(window.scrollY > 400){ filterBar.classList.replace('py-3','py-2'); }
        else { filterBar.classList.replace('py-2','py-3'); }
    }, {passive:true});
    function animateCounter(el) {
        var target = parseInt(el.dataset.target.toString().replace(/,/g,''), 10);
        if(isNaN(target)) return;
        var duration = 1400, step = Math.ceil(target/(duration/16)), current = 0;
        var timer = setInterval(function() {
            current = Math.min(current + step, target);
            el.textContent = current.toLocaleString();
            if(current >= target) clearInterval(timer);
        }, 16);
    }
    var statEls = document.querySelectorAll('.stat-num');
    if('IntersectionObserver' in window) {
        var obs = new IntersectionObserver(function(entries) {
            entries.forEach(function(e){ if(e.isIntersecting){ animateCounter(e.target); obs.unobserve(e.target); } });
        }, {threshold:0.5});
        statEls.forEach(function(el){ obs.observe(el); });
    } else { statEls.forEach(animateCounter); }
    document.querySelectorAll('.category-pill, .sort-tab, .modal-category-card').forEach(function(el) {
        el.addEventListener('click', function() {
            sessionStorage.setItem('scrollToGrid', '1');
        });
    });

    const modalSearchInput = document.getElementById('categorySearch');
    if (modalSearchInput) {
        modalSearchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const categoryItems = document.querySelectorAll('#categoriesModal .category-item');
            const noResultsState = document.getElementById('noResultsState');
            let totalMatches = 0;
            
            categoryItems.forEach(item => {
                const name = item.getAttribute('data-name');
                if (name.includes(searchTerm)) {
                    item.style.setProperty('display', 'block', 'important');
                    totalMatches++;
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });
            
            if (totalMatches === 0) {
                noResultsState.classList.remove('d-none');
            } else {
                noResultsState.classList.add('d-none');
            }
        });
    }

    if(sessionStorage.getItem('scrollToGrid')==='1') {
        sessionStorage.removeItem('scrollToGrid');
        var grid = document.querySelector('.blog-card, .blog-featured-card, .blog-empty-state');
        if(grid) setTimeout(function(){ grid.scrollIntoView({behavior:'smooth',block:'start'}); }, 200);
    }
});
</script>
@endsection
