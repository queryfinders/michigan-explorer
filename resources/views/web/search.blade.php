@extends('web.layout.app_layout')

@section('title', 'Global Search Results - Michigan Explorer')

@section('webLayoutContent')
<!-- 1. PREMIUM HERO SECTION -->
<section class="hero-premium position-relative" style="min-height: 70vh; padding-top: 100px; z-index: 10; overflow: visible !important;">
    <div class="hero-bg-parallax position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: -1;">
        <div class="hero-bg-zoom" role="img" aria-label="Michigan Explorer Search" style="background-image: url('{{ asset('images/hero_banner_1783508250640.png') }}');"></div>
    </div>

    <div class="container position-relative z-index-1 text-white py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center text-white opacity-75">
                <li class="breadcrumb-item"><a href="{{ route('web.home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white fw-bold" aria-current="page">Search</li>
            </ol>
        </nav>

        <!-- Typography -->
        <div class="text-center mb-4">
            <h1 class="display-4 fw-bold mb-3 text-white font-heading text-shadow-md">Search Results</h1>
            @if($q)
                <p class="lead fs-5 mx-auto text-shadow-sm">Showing results for: <span class="fw-bold text-warning">"{{ $q }}"</span></p>
            @endif
        </div>

        <!-- Search Box -->
        <div class="smart-search-container mx-auto" style="max-width: 800px;" x-data="smartSearch('{{ addslashes($q ?? '') }}')" @click.away="isOpen = false">
            <form action="{{ route('web.search') }}" method="GET" class="smart-search-box" @submit="onSubmit">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <i class="fas fa-search smart-search-icon" x-show="!isLoading"></i>
                <div class="search-loader" x-show="isLoading" class="d-none"></div>
                <input 
                    type="text" 
                    name="q" 
                    class="smart-search-input" 
                    placeholder="Search hotels, restaurants, attractions, events or destinations..." 
                    autocomplete="off"
                    x-model="keyword"
                    @input.debounce.300ms="fetchSuggestions"
                    @focus="isOpen = true; if(keyword.length > 0) fetchSuggestions()"
                    @keydown.down.prevent="navigate(1)"
                    @keydown.up.prevent="navigate(-1)"
                    @keydown.enter.prevent="selectCurrent"
                    @keydown.escape="isOpen = false"
                >
                <button type="submit" class="smart-search-btn d-none d-sm-block">Search</button>
                <button type="submit" class="smart-search-btn d-block d-sm-none px-3 rounded-circle btn-icon-50 p-0"><i class="fas fa-search"></i></button>
            </form>

            <!-- Autocomplete Dropdown -->
            <div class="autocomplete-dropdown" :class="{ 'show': isOpen && keyword.length > 0 }">
                <div x-show="!isLoading && Object.keys(groupedResults).length === 0 && keyword.length > 0" class="p-4 text-center text-muted">
                    <i class="fas fa-search-minus fs-2 mb-2 text-muted opacity-50"></i>
                    <p class="mb-0">No results found for "<span x-text="keyword" class="fw-bold"></span>"</p>
                </div>

                <template x-for="(group, category) in groupedResults" :key="category">
                    <div class="autocomplete-group">
                        <div class="autocomplete-group-title" x-text="category"></div>
                        <template x-for="item in group.items" :key="item.id">
                            <a :href="item.url" class="autocomplete-item d-flex align-items-center" :class="{ 'active': activeIndex === item.index }" @mouseenter="activeIndex = item.index">
                                <img :src="item.image" alt="" class="rounded me-3 shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-dark lh-1 mb-1" x-html="highlight(item.title)"></div>
                                    <div class="small text-muted lh-1"><i :class="group.icon" class="me-1"></i><span x-text="item.location"></span></div>
                                </div>
                            </a>
                        </template>
                        <template x-if="group.has_more">
                            <div class="text-center p-2 border-top bg-light">
                                <a :href="group.view_all_url" class="small fw-bold text-primary text-decoration-none">View All <span x-text="category"></span> &rarr;</a>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>

<!-- 2. GLOBAL SEARCH SUMMARY & TABS -->
<section class="py-4 border-bottom bg-white shadow-sm position-sticky z-index-1" style="top: 70px; z-index: 1020;">
    <div class="container">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <div>
                <h4 class="fw-bold mb-1">{{ $counts['all'] }} Results Found</h4>
                @if($tab != 'all')
                    @php $tabDisplayName = ucfirst(str_replace('_', ' ', $tab)); @endphp
                    <p class="text-muted small mb-0">Showing {{ $results->firstItem() ?? 0 }}–{{ $results->lastItem() ?? 0 }} of {{ $results->total() ?? 0 }} {{ $tabDisplayName }}</p>
                @endif
            </div>
            @if($tab != 'all')
            <!-- Mobile Filter Toggle -->
            <button class="btn btn-outline-primary d-lg-none mt-3 mt-md-0 rounded-pill px-4" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileFiltersOffcanvas">
                <i class="fas fa-filter me-2"></i> Filters
            </button>
            @endif
        </div>

        <!-- Tabs -->
        <ul class="nav nav-pills custom-pills flex-nowrap overflow-auto hide-scrollbar pb-2" style="white-space: nowrap;">
            <li class="nav-item me-2">
                <a class="nav-link rounded-pill px-4 {{ $tab == 'all' ? 'active shadow-sm' : 'bg-light text-dark' }}" href="{{ request()->fullUrlWithQuery(['tab' => 'all', 'page' => null]) }}">
                    All <span class="badge bg-{{ $tab == 'all' ? 'white text-primary' : 'secondary text-white' }} ms-1 rounded-pill">{{ $counts['all'] }}</span>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link rounded-pill px-4 {{ $tab == 'hotels' ? 'active shadow-sm' : 'bg-light text-dark' }}" href="{{ request()->fullUrlWithQuery(['tab' => 'hotels', 'page' => null]) }}">
                    Hotels <span class="badge bg-{{ $tab == 'hotels' ? 'white text-primary' : 'secondary text-white' }} ms-1 rounded-pill">{{ $counts['hotels'] }}</span>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link rounded-pill px-4 {{ $tab == 'restaurants' ? 'active shadow-sm' : 'bg-light text-dark' }}" href="{{ request()->fullUrlWithQuery(['tab' => 'restaurants', 'page' => null]) }}">
                    Restaurants <span class="badge bg-{{ $tab == 'restaurants' ? 'white text-primary' : 'secondary text-white' }} ms-1 rounded-pill">{{ $counts['restaurants'] }}</span>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link rounded-pill px-4 {{ $tab == 'attractions' ? 'active shadow-sm' : 'bg-light text-dark' }}" href="{{ request()->fullUrlWithQuery(['tab' => 'attractions', 'page' => null]) }}">
                    Attractions <span class="badge bg-{{ $tab == 'attractions' ? 'white text-primary' : 'secondary text-white' }} ms-1 rounded-pill">{{ $counts['attractions'] }}</span>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link rounded-pill px-4 {{ $tab == 'events' ? 'active shadow-sm' : 'bg-light text-dark' }}" href="{{ request()->fullUrlWithQuery(['tab' => 'events', 'page' => null]) }}">
                    Events <span class="badge bg-{{ $tab == 'events' ? 'white text-primary' : 'secondary text-white' }} ms-1 rounded-pill">{{ $counts['events'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 {{ $tab == 'travel_guides' ? 'active shadow-sm' : 'bg-light text-dark' }}" href="{{ request()->fullUrlWithQuery(['tab' => 'travel_guides', 'page' => null]) }}">
                    Travel Guides <span class="badge bg-{{ $tab == 'travel_guides' ? 'white text-primary' : 'secondary text-white' }} ms-1 rounded-pill">{{ $counts['blogs'] }}</span>
                </a>
            </li>
        </ul>
    </div>
</section>

<!-- 3. MAIN CONTENT -->
<section class="py-5 bg-light-gray min-vh-50">
    <div class="container">
        <div class="row">
            
            <!-- LEFT SIDEBAR FILTERS (Only show if not "all") -->
            @if($tab != 'all')
            <div class="col-lg-3 d-none d-lg-block">
                @include('web.partials._search_filters')
            </div>
            
            <!-- Mobile Offcanvas Filters -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileFiltersOffcanvas" aria-labelledby="mobileFiltersLabel">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title fw-bold" id="mobileFiltersLabel"><i class="fas fa-filter me-2 text-primary"></i>Filters</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    @include('web.partials._search_filters')
                </div>
            </div>
            @endif

            <!-- RIGHT RESULTS GRID -->
            <div class="col-lg-{{ $tab == 'all' ? '12' : '9' }}">
                
                @if($tab != 'all')
                <!-- Active Filters Chips & Sorting -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @if($q)
                            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill d-flex align-items-center shadow-sm">
                                Keyword: {{ $q }}
                                <a href="{{ request()->fullUrlWithQuery(['q' => null, 'page' => null]) }}" class="text-muted ms-2 text-decoration-none"><i class="fas fa-times"></i></a>
                            </span>
                        @endif
                        @if(request('city'))
                            @foreach((array)request('city') as $c)
                            @php
                                $cityQuery = request('city');
                                if(is_array($cityQuery)) { $k = array_search($c, $cityQuery); if($k !== false) unset($cityQuery[$k]); }
                            @endphp
                            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill d-flex align-items-center shadow-sm">
                                {{ $c }}
                                <a href="{{ request()->fullUrlWithQuery(['city' => empty($cityQuery) ? null : $cityQuery, 'page' => null]) }}" class="text-muted ms-2 text-decoration-none"><i class="fas fa-times"></i></a>
                            </span>
                            @endforeach
                        @endif
                        @if(request('category') && isset($filters['categories']))
                            @foreach((array)request('category') as $catId)
                                @php 
                                    $cat = collect($filters['categories'])->firstWhere('id', $catId); 
                                    $catQuery = request('category');
                                    if(is_array($catQuery)) { $k = array_search($catId, $catQuery); if($k !== false) unset($catQuery[$k]); }
                                @endphp
                                @if($cat)
                                <span class="badge bg-white text-dark border px-3 py-2 rounded-pill d-flex align-items-center shadow-sm">
                                    {{ $cat->name }}
                                    <a href="{{ request()->fullUrlWithQuery(['category' => empty($catQuery) ? null : $catQuery, 'page' => null]) }}" class="text-muted ms-2 text-decoration-none"><i class="fas fa-times"></i></a>
                                </span>
                                @endif
                            @endforeach
                        @endif
                        @if(request('amenities') && isset($filters['amenities']))
                            @foreach((array)request('amenities') as $amId)
                                @php 
                                    $am = collect($filters['amenities'])->firstWhere('id', $amId); 
                                    $amQuery = request('amenities');
                                    if(is_array($amQuery)) { $k = array_search($amId, $amQuery); if($k !== false) unset($amQuery[$k]); }
                                @endphp
                                @if($am)
                                <span class="badge bg-white text-dark border px-3 py-2 rounded-pill d-flex align-items-center shadow-sm">
                                    {{ $am->name }}
                                    <a href="{{ request()->fullUrlWithQuery(['amenities' => empty($amQuery) ? null : $amQuery, 'page' => null]) }}" class="text-muted ms-2 text-decoration-none"><i class="fas fa-times"></i></a>
                                </span>
                                @endif
                            @endforeach
                        @endif
                        
                        @if(count(request()->except(['tab', 'page', 'sort'])) > 0)
                            <a href="{{ route('web.search', ['tab' => $tab]) }}" class="text-danger small fw-bold text-decoration-none ms-2 hover-elevate">Clear All</a>
                        @endif
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <label class="text-muted small fw-bold me-2 text-nowrap">Sort By:</label>
                        <div class="dropdown">
                            <button class="btn bg-white shadow-sm border-0 rounded-pill dropdown-toggle px-4 py-2 small fw-bold text-dark d-flex align-items-center justify-content-between" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 170px;">
                                @php
                                    $sortLabels = [
                                        'newest' => 'Newest',
                                        'featured' => 'Featured',
                                        'alphabetical' => 'Alphabetical (A-Z)',
                                        'price_low' => 'Price: Low to High',
                                        'price_high' => 'Price: High to Low',
                                    ];
                                    $currentSort = request('sort', 'newest');
                                    echo $sortLabels[$currentSort] ?? 'Newest';
                                @endphp
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 mt-2 py-2">
                                <li><a class="dropdown-item py-2 px-4 {{ request('sort', 'newest') == 'newest' ? 'active fw-bold bg-primary text-white' : 'text-muted' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Newest</a></li>
                                @if($tab != 'travel_guides')
                                <li><a class="dropdown-item py-2 px-4 {{ request('sort') == 'featured' ? 'active fw-bold bg-primary text-white' : 'text-muted' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}">Featured</a></li>
                                @endif
                                <li><a class="dropdown-item py-2 px-4 {{ request('sort') == 'alphabetical' ? 'active fw-bold bg-primary text-white' : 'text-muted' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'alphabetical']) }}">Alphabetical (A-Z)</a></li>
                                @if(in_array($tab, ['hotels']))
                                <li><hr class="dropdown-divider opacity-10 mx-3"></li>
                                <li><a class="dropdown-item py-2 px-4 {{ request('sort') == 'price_low' ? 'active fw-bold bg-primary text-white' : 'text-muted' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}">Price: Low to High</a></li>
                                <li><a class="dropdown-item py-2 px-4 {{ request('sort') == 'price_high' ? 'active fw-bold bg-primary text-white' : 'text-muted' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}">Price: High to Low</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                @endif


                <!-- TAB CONTENT -->
                @if($tab == 'all')
                    <!-- ALL TAB (Grouped) -->
                    @if($counts['all'] == 0)
                        @include('web.partials._empty_search_state')
                    @else
                        @if(count($results['hotels']) > 0)
                            <div class="mb-5 pb-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3 class="fw-bold mb-0"><i class="fas fa-hotel text-primary me-2"></i> Hotels</h3>
                                    <a href="{{ request()->fullUrlWithQuery(['tab' => 'hotels']) }}" class="btn btn-outline-primary rounded-pill btn-sm px-3">View All Hotels &rarr;</a>
                                </div>
                                <div class="row g-4">
                                    @foreach($results['hotels'] as $hotel)
                                    <div class="col-md-4">
                                        <x-hotel-card :hotel="$hotel" :featured="false" :compact="true" />
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(count($results['restaurants']) > 0)
                            <div class="mb-5 pb-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3 class="fw-bold mb-0"><i class="fas fa-utensils text-primary me-2"></i> Restaurants</h3>
                                    <a href="{{ request()->fullUrlWithQuery(['tab' => 'restaurants']) }}" class="btn btn-outline-primary rounded-pill btn-sm px-3">View All Restaurants &rarr;</a>
                                </div>
                                <div class="row g-4">
                                    @foreach($results['restaurants'] as $restaurant)
                                    <div class="col-md-4">
                                        <x-restaurant-card :restaurant="$restaurant" :featured="false" :compact="true" />
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(count($results['attractions']) > 0)
                            <div class="mb-5 pb-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3 class="fw-bold mb-0"><i class="fas fa-map-marked-alt text-primary me-2"></i> Attractions</h3>
                                    <a href="{{ request()->fullUrlWithQuery(['tab' => 'attractions']) }}" class="btn btn-outline-primary rounded-pill btn-sm px-3">View All Attractions &rarr;</a>
                                </div>
                                <div class="row g-4">
                                    @foreach($results['attractions'] as $attraction)
                                    <div class="col-md-4">
                                        <x-attraction-card :attraction="$attraction" :featured="false" />
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(count($results['events']) > 0)
                            <div class="mb-5 pb-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3 class="fw-bold mb-0"><i class="fas fa-calendar-alt text-primary me-2"></i> Events</h3>
                                    <a href="{{ request()->fullUrlWithQuery(['tab' => 'events']) }}" class="btn btn-outline-primary rounded-pill btn-sm px-3">View All Events &rarr;</a>
                                </div>
                                <div class="row g-4">
                                    @foreach($results['events'] as $event)
                                    <div class="col-md-4">
                                        <x-event-card :event="$event" />
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(count($results['blogs']) > 0)
                            <div class="mb-5 pb-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3 class="fw-bold mb-0"><i class="fas fa-book-open text-primary me-2"></i> Travel Guides</h3>
                                    <a href="{{ request()->fullUrlWithQuery(['tab' => 'travel_guides']) }}" class="btn btn-outline-primary rounded-pill btn-sm px-3">View All Guides &rarr;</a>
                                </div>
                                <div class="row g-4">
                                    @foreach($results['blogs'] as $blog)
                                    <div class="col-md-4">
                                        <x-blog-card :blog="$blog" />
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                @else
                    <!-- SPECIFIC TABS (Grid) -->
                    @if($results->isEmpty())
                        @include('web.partials._empty_search_state')
                    @else
                        <div class="row g-4">
                            @foreach($results as $item)
                                <div class="col-md-6 col-lg-4">
                                    @if($tab == 'hotels')
                                        <x-hotel-card :hotel="$item" :featured="$item->is_featured == 1" />
                                    @elseif($tab == 'restaurants')
                                        <x-restaurant-card :restaurant="$item" :featured="$item->is_featured == 1" />
                                    @elseif($tab == 'attractions')
                                        <x-attraction-card :attraction="$item" :featured="$item->is_featured == 1" />
                                    @elseif($tab == 'events')
                                        <x-event-card :event="$item" />
                                    @elseif($tab == 'travel_guides')
                                        <x-blog-card :blog="$item" />
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-5 pt-4 border-top">
                            {{ $results->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                @endif
                
            </div>
        </div>
    </div>
</section>

@endsection
