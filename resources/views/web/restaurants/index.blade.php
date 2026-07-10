@extends('web.layout.app_layout')

@php
    $pageTitle = isset($currentCategory) ? $currentCategory->name . ' in Michigan' : 'Discover Michigan\'s Best Restaurants';
    $metaTitle = isset($currentCategory) ? $currentCategory->name . ' - Michigan Explorer' : 'Restaurants - Michigan Explorer';
    $metaDescription = isset($currentCategory) && $currentCategory->description 
        ? Str::limit(strip_tags($currentCategory->description), 160) 
        : 'Explore local favorites, fine dining, waterfront restaurants, cafés, and unforgettable food experiences across Michigan.';
    $canonicalUrl = isset($currentCategory) ? route('web.restaurants.category', $currentCategory->slug) : route('web.restaurants.index');
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
<section class="hotel-listing-hero position-relative" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('{{ asset('images/fine_dining_1783508270763.png') }}');">
    <div class="content">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb justify-content-center text-white opacity-75">
                <li class="breadcrumb-item"><a href="{{ route('web.home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('web.restaurants.index') }}" class="text-white text-decoration-none">Restaurants</a></li>
                @if(isset($currentCategory))
                <li class="breadcrumb-item active text-white fw-bold" aria-current="page">{{ $currentCategory->name }}</li>
                @endif
            </ol>
        </nav>

        <h1 class="display-3 fw-bold text-white mb-3" style="font-family: var(--font-heading);">{{ $pageTitle }}</h1>
        <p class="lead text-white opacity-75 mb-4">{{ $metaDescription }}</p>
        
        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="#all-restaurants" class="btn btn-secondary rounded-pill px-4 py-2 fw-bold shadow-sm">Browse {{ isset($currentCategory) ? $currentCategory->name : 'Featured Restaurants' }}</a>
        </div>
    </div>
</section>

<!-- 2. Browse by Category -->
<section class="py-4 border-bottom bg-white shadow-sm position-relative z-index-1">
    <div class="container">
        <h6 class="text-uppercase text-muted fw-bold small mb-3 tracking-wider">Browse by Category</h6>
        <div class="category-filter-wrapper d-flex align-items-center">
            
            <a href="{{ route('web.restaurants.index') }}" class="category-pill {{ !isset($currentCategory) ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                <span class="cat-name">All Places</span>
                <span class="cat-count">86</span>
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
            @endphp

            @foreach($displayCategories as $cat)
                @php $catObj = (object)$cat; @endphp
                <a href="{{ route('web.restaurants.category', $catObj->slug) }}" class="category-pill {{ (isset($currentCategory) && $currentCategory->id === $catObj->id) ? 'active' : '' }}">
                    <i class="fas {{ $catObj->icon ?? 'fa-utensils' }}"></i>
                    <span class="cat-name">{{ $catObj->name }}</span>
                    <span class="cat-count">{{ $catObj->restaurants_count ?? 24 }}</span>
                </a>
            @endforeach

            <!-- More Categories Button -->
            <a href="#" class="category-pill bg-light" data-bs-toggle="modal" data-bs-target="#categoriesModal">
                <i class="fas fa-ellipsis-h"></i>
                <span class="cat-name">More</span>
                <span class="cat-count">Explore All</span>
            </a>

        </div>
    </div>
</section>

<!-- 3. Restaurant Listing Grid -->
<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container py-4">
        
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="fw-bold mb-0" style="color: #1A1D20;">{{ isset($currentCategory) ? 'Showing ' . $restaurants->total() . ' ' . $currentCategory->name : 'Available Restaurants' }}</h2>
                <p class="text-muted mt-2 mb-0">Showing {{ $restaurants->count() }} of {{ $restaurants->total() }} restaurants found</p>
            </div>
        </div>

        <div class="row g-4" id="all-restaurants">
            @forelse($restaurants as $index => $restaurant)
            <!-- Restaurant Card -->
            <div class="col-lg-4 col-md-6">
                <x-restaurant-card :restaurant="$restaurant" :featured="$index % 4 == 0" />
            </div>
            @empty
            <!-- Static Fallback Data for Empty State -->
            @for($i=1; $i<=6; $i++)
            <div class="col-lg-4 col-md-6">
                <x-restaurant-card :restaurant="(object)[
                    'name' => 'Lakeside Prime Steakhouse',
                    'slug' => 'demo',
                    'city' => 'Traverse City',
                    'description' => 'Savor exquisite culinary masterpieces with breathtaking waterfront views.',
                    'starting_price' => '45',
                    'affiliate_url' => route('web.restaurants.show', 'demo'),
                    'category' => (object)['name' => 'Fine Dining']
                ]" :featured="$i === 1 || $i === 4" />
            </div>
            @endfor
            @endforelse
        </div>

        <!-- 4. Pagination -->
        <div class="d-flex justify-content-center mt-5 pt-4 border-top">
            {{ $restaurants->links('pagination::bootstrap-5') }}
        </div>
        
    </div>
</section>

<!-- Categories Modal -->
<div class="modal fade" id="categoriesModal" tabindex="-1" aria-labelledby="categoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold fs-4" id="categoriesModalLabel">All Restaurant Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <!-- Large Search Input -->
                <div class="position-relative mb-4">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-4 text-muted fs-5"></i>
                    <input type="text" id="categorySearch" class="form-control form-control-lg rounded-pill ps-5 bg-light border-0 py-3" placeholder="Search dining categories..." autocomplete="off">
                </div>

                <!-- Alphabetically Grouped Categories -->
                <div id="categoryListContainer">
                    @php
                        $groupedCategories = (isset($allCategories) ? $allCategories : collect())->groupBy(function($item, $key) {
                            return strtoupper(substr($item->name, 0, 1));
                        });
                    @endphp

                    @foreach($groupedCategories as $letter => $catGroup)
                        <div class="category-group mb-4">
                            <h4 class="fw-bold text-primary mb-3 border-bottom pb-2">{{ $letter }}</h4>
                            <div class="row g-3">
                                @foreach($catGroup as $cat)
                                <div class="col-md-4 col-sm-6 category-item" data-name="{{ strtolower($cat->name) }}">
                                    <a href="{{ route('web.restaurants.category', $cat->slug) }}" class="text-decoration-none text-dark d-flex align-items-center p-2 rounded-3 hover-bg-light transition-all">
                                        <div class="icon-box bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas {{ $cat->icon ?? 'fa-utensils' }} text-secondary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small">{{ $cat->name }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $cat->restaurants_count ?? 24 }} Places</div>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
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
        
        const categoryGroups = document.querySelectorAll('.category-group');
        const noResultsState = document.getElementById('noResultsState');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let totalMatches = 0;
            
            categoryGroups.forEach(group => {
                let groupMatches = 0;
                const items = group.querySelectorAll('.category-item');
                
                items.forEach(item => {
                    const name = item.getAttribute('data-name');
                    if (name.includes(searchTerm)) {
                        item.style.display = 'block';
                        groupMatches++;
                        totalMatches++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                if (groupMatches === 0) {
                    group.style.display = 'none';
                } else {
                    group.style.display = 'block';
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
