@extends('web.layout.app_layout')

@php
    $pageTitle = isset($currentCategory) ? $currentCategory->name . ' in Michigan' : 'Discover Michigan\'s Top Attractions';
    $metaTitle = isset($currentCategory) ? $currentCategory->name . ' - Michigan Explorer' : 'Attractions - Michigan Explorer';
    $metaDescription = isset($currentCategory) && $currentCategory->description 
        ? Str::limit(strip_tags($currentCategory->description), 160) 
        : 'Explore breathtaking parks, historic lighthouses, fun casinos, zoos, beaches, and world-class museums across Michigan.';
    $canonicalUrl = isset($currentCategory) ? route('web.attractions.category', $currentCategory->slug) : route('web.attractions.index');
@endphp

@section('title', $metaTitle)

@section('meta_description')
<meta name="description" content="{{ $metaDescription }}">
@endsection

@section('og_tags')
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:type" content="website">
@endsection

@section('canonical')
<link rel="canonical" href="{{ $canonicalUrl }}">
@endsection

@section('webLayoutContent')
<!-- 1. Hero Banner -->
<section class="hotel-listing-hero position-relative" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7)), url('{{ asset('images/attraction_nature_1783508280642.png') }}');">
    <div class="content">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb justify-content-center text-white opacity-75">
                <li class="breadcrumb-item"><a href="{{ route('web.home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('web.attractions.index') }}" class="text-white text-decoration-none">Attractions</a></li>
                @if(isset($currentCategory))
                <li class="breadcrumb-item active text-white fw-bold" aria-current="page">{{ $currentCategory->name }}</li>
                @endif
            </ol>
        </nav>

        <h1 class="display-3 fw-bold text-white mb-3 auto-style-7">{{ $pageTitle }}</h1>
        <p class="lead text-white opacity-75 mb-4">{{ $metaDescription }}</p>
        
        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="#all-attractions" class="btn btn-secondary rounded-pill px-4 py-2 fw-bold shadow-sm">Browse {{ isset($currentCategory) ? $currentCategory->name : 'Featured Attractions' }}</a>
        </div>
    </div>
</section>

<!-- 2. Browse by Category -->
<section class="category-filter-bar-sticky py-4 border-bottom bg-white shadow-sm position-relative z-index-1">
    <div class="container">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <h6 class="text-uppercase text-muted fw-bold small mb-0 tracking-wider text-nowrap">Browse by Category</h6>
            <div class="category-filter-wrapper d-flex align-items-center flex-wrap gap-2">
                
                <a href="{{ route('web.attractions.index', ['scroll' => 1]) }}" class="category-pill {{ !isset($currentCategory) ? 'active' : '' }}">
                    <span class="cat-name">All Places</span>
                    <span class="cat-count">112</span>
                </a>

                @php
                    // Check if current category is in the featured list
                    $isCurrentFeatured = false;
                    if(isset($currentCategory) && isset($featuredCategories)) {
                        foreach($featuredCategories as $cat) {
                            if($cat->id === $currentCategory->id) {
                                $isCurrentFeatured = true;
                                break;
                            }
                        }
                    }
                    
                    $displayCategories = isset($featuredCategories) ? $featuredCategories->toArray() : [];
                    
                    // If current category is not featured, replace the last item with it
                    if(isset($currentCategory) && !$isCurrentFeatured && count($displayCategories) > 0) {
                        $displayCategories[count($displayCategories) - 1] = $currentCategory->toArray();
                    }
                    
                    // Limit to 5 categories to ensure it fills reasonable space without wrapping on desktop
                    $displayCategories = array_slice($displayCategories, 0, 8);
                @endphp

                @foreach($displayCategories as $cat)
                    @php $catObj = (object)$cat; @endphp
                    <a href="{{ route('web.attractions.category', $catObj->slug) }}" class="category-pill {{ (isset($currentCategory) && $currentCategory->id === $catObj->id) ? 'active' : '' }}">
                        <span class="cat-name">{{ $catObj->name }}</span>
                        <span class="cat-count">{{ $catObj->attractions_count ?? 30 }}</span>
                    </a>
                @endforeach

                <!-- More Categories Button -->
                <a href="#" class="category-pill bg-light" data-bs-toggle="modal" data-bs-target="#categoriesModal">
                    <span class="cat-name">More...</span>
                </a>

            </div>
        </div>
    </div>
</section>

<!-- 3. Attraction Listing Grid -->
<section class="py-5 auto-style-8">
    <div class="container py-4">
        
        <div class="mb-5">
            <h2 class="fw-bold mb-0 auto-style-9">{{ isset($currentCategory) ? 'Showing ' . $attractions->total() . ' ' . $currentCategory->name : 'Available Attractions' }}</h2>
            <p class="text-muted mt-2 mb-0">Showing {{ $attractions->count() }} of {{ $attractions->total() }} attractions found</p>
        </div>

        <div class="row g-4" id="all-attractions">
            @forelse($attractions as $index => $attraction)
            <!-- Attraction Card -->
            <div class="col-lg-4 col-md-6">
                <x-attraction-card :attraction="$attraction" :featured="($attraction->is_featured ?? 0) == 1" />
            </div>
            @empty
            <!-- Static Fallback Data for Empty State -->
            @for($i=1; $i<=6; $i++)
            <div class="col-lg-4 col-md-6">
                <x-attraction-card :attraction="(object)[
                    'name' => 'Sleeping Bear Dunes',
                    'slug' => 'demo',
                    'city' => 'Empire',
                    'description' => 'Experience towering sand dunes and spectacular views of Lake Michigan at this national lakeshore.',
                    'distance' => '2.5 miles away',
                    'travel_time_car' => '10 min drive',
                    'travel_time_walk' => '45 min walk',
                ]" :featured="$i === 1" />
            </div>
            @endfor
            @endforelse
        </div>

        <!-- 4. Pagination -->
        <div class="d-flex justify-content-center mt-5 pt-4 border-top">
            {{ $attractions->links('pagination::bootstrap-5') }}
        </div>
        
    </div>
</section>

<!-- Categories Modal -->
<div class="modal fade" id="categoriesModal" tabindex="-1" aria-labelledby="categoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold fs-4" id="categoriesModalLabel">All Attraction Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <!-- Large Search Input -->
                <div class="position-relative mb-4">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-4 text-muted fs-5"></i>
                    <input type="text" id="categorySearch" class="form-control form-control-lg rounded-pill ps-5 bg-light border-0 py-3" placeholder="Search categories..." autocomplete="off">
                </div>

                <!-- Flat Grid Categories -->
                <div id="categoryListContainer">
                    <div class="row g-3">
                        @foreach((isset($allCategories) ? $allCategories : collect())->sortBy('name') as $cat)
                        <div class="col-md-3 col-sm-6 category-item" data-name="{{ strtolower($cat->name) }}">
                            <a href="{{ route('web.attractions.category', $cat->slug) }}" class="modal-category-card">
                                <div>
                                    <div class="fw-bold text-heading" style="font-size: 0.9rem;">{{ $cat->name }}</div>
                                    <div class="text-muted fs-xs mt-1">{{ $cat->attractions_count ?? 0 }} {{ Str::plural('Place', $cat->attractions_count ?? 0) }}</div>
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

@endsection

@section('webLayoutScript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('categorySearch');
        if (!searchInput) return;
        
        const categoryItems = document.querySelectorAll('.category-item');
        const noResultsState = document.getElementById('noResultsState');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let totalMatches = 0;
            
            categoryItems.forEach(item => {
                const name = item.getAttribute('data-name');
                if (name.includes(searchTerm)) {
                    item.style.display = 'block';
                    totalMatches++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            if (totalMatches === 0) {
                noResultsState.classList.remove('d-none');
            } else {
                noResultsState.classList.add('d-none');
            }
        });
    });
</script>
@endsection
