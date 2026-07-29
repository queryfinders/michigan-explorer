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
@php
    $bannerImage = ($page && $page->featured_image) ? asset($page->featured_image) : asset('images/attraction_nature_1783508280642.jpg');
    $bannerTitle = ($page && $page->banner_title) ? $page->banner_title : $pageTitle;
    $bannerSubtitle = ($page && $page->banner_subtitle) ? $page->banner_subtitle : $metaDescription;
    $bannerBtnText = ($page && $page->banner_button_text) ? $page->banner_button_text : null;
    $bannerBtnLink = ($page && $page->banner_button_link) ? $page->banner_button_link : '#';
@endphp

<!-- 1. Hero Banner -->
<section class="hotel-listing-hero position-relative" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7)), url('{{ $bannerImage }}');">
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

        <h1 class="display-3 fw-bold text-white mb-3 auto-style-7">{{ $bannerTitle }}</h1>
        <p class="lead text-white opacity-75 mb-4">{{ $bannerSubtitle }}</p>
        
        @if($bannerBtnText)
        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="{{ $bannerBtnLink }}" class="btn btn-secondary rounded-pill px-4 py-2 fw-bold shadow-sm">{{ $bannerBtnText }}</a>
        </div>
        @endif
    </div>
</section>

<!-- 2. Browse by Category -->
<section class="category-filter-bar-sticky py-4 border-bottom bg-white shadow-sm position-relative z-index-1">
    <div class="container">
        <div class="category-bar-inner d-flex flex-column align-items-start gap-2">
            <h6 class="text-uppercase text-muted fw-bold small mb-0 tracking-wider text-nowrap mt-2">Browse by Category</h6>
            <div class="category-filter-wrapper d-flex align-items-center flex-wrap gap-2">
                
                <a href="{{ route('web.attractions.index', ['scroll' => 1]) }}" class="category-pill {{ !isset($currentCategory) ? 'active' : '' }}">
                    <span class="cat-name">All Places</span>
                    <span class="cat-count">{{ $totalAttractionsCount ?? 0 }}</span>
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
                    
                    // Limit to 6 categories
                    $displayCategories = array_slice($displayCategories, 0, 6);
                @endphp

                @foreach($displayCategories as $cat)
                    @php $catObj = (object)$cat; @endphp
                    <a href="{{ route('web.attractions.category', $catObj->slug) }}" class="category-pill {{ (isset($currentCategory) && $currentCategory->id === $catObj->id) ? 'active' : '' }}">
                        <span class="cat-name">{{ $catObj->name }}</span>
                        <span class="cat-count">{{ $catObj->attractions_count ?? 0 }}</span>
                    </a>
                @endforeach

                <!-- More Categories Button -->
                <a href="#" class="category-pill bg-light more-pill" data-bs-toggle="modal" data-bs-target="#categoriesModal">
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
            @include('web.attractions._attractions_grid')
        </div>

        <!-- Infinite Scroll Loading Spinner -->
        <div class="d-none justify-content-center mt-5" id="infinite-scroll-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
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

    let isLoading = false;

    // Detect scroll to bottom
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 400) {
            const nextLink = $('#next-page-link');
            if (nextLink.length > 0 && !isLoading) {
                loadMoreAttractions(nextLink.attr('href'));
            }
        }
    });

    function loadMoreAttractions(url) {
        isLoading = true;
        $('#infinite-scroll-spinner').removeClass('d-none').addClass('d-flex');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                const tempDiv = $('<div>').html(response);
                
                // Remove the old pagination wrapper
                $('#infinite-scroll-pagination-wrapper').remove();

                // Append new attraction cards
                const newItems = tempDiv.find('.attraction-card-item');
                $('#all-attractions').append(newItems);

                // Add the new pagination wrapper at the bottom
                const newPagination = tempDiv.find('#infinite-scroll-pagination-wrapper');
                $('#all-attractions').append(newPagination);

                isLoading = false;
                $('#infinite-scroll-spinner').removeClass('d-flex').addClass('d-none');
            },
            error: function(xhr) {
                console.error("AJAX failed to load more attractions.", xhr);
                isLoading = false;
                $('#infinite-scroll-spinner').removeClass('d-flex').addClass('d-none');
            }
        });
    }
</script>
@endsection
