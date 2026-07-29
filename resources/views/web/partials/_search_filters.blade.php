<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h5 class="fw-bold mb-0 text-heading"><i class="fas fa-sliders-h me-2 text-primary"></i> Filter {{ ucfirst($tab) }}</h5>
            @php $filterCount = count(request()->except(['tab', 'page', 'sort', 'q'])); @endphp
            @if($filterCount > 0)
                <a href="{{ route('web.search', ['tab' => $tab, 'q' => request('q')]) }}" class="btn btn-sm btn-light rounded-pill px-3 border shadow-sm" title="Reset Filters">
                    <i class="fas fa-undo me-1"></i> Reset
                </a>
            @endif
        </div>
        
        <form action="{{ route('web.search') }}" method="GET" id="searchFiltersForm">
            <input type="hidden" name="tab" value="{{ $tab }}">
            @if(request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif

            <div class="filter-wrapper" style="max-height: calc(100vh - 150px); overflow-y: auto; overflow-x: hidden; padding-right: 10px;">
            
            @php $first_filter = true; @endphp

            <!-- Common Filter: City -->
            @if(isset($filters['cities']) && count($filters['cities']) > 0)
            <div class="mb-3 border-bottom pb-2 w-100 d-block">
                <label class="fw-bold mb-2 d-flex justify-content-between align-items-center text-uppercase small tracking-wider text-muted cursor-pointer w-100 {{ $first_filter ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseCity" aria-expanded="{{ $first_filter ? 'true' : 'false' }}" style="cursor: pointer;">
                    City <i class="fas fa-chevron-down"></i>
                </label>
                <div class="collapse {{ $first_filter ? 'show' : '' }}" id="collapseCity">
                    <div class="filter-scroll mb-3">
                        @foreach($filters['cities'] as $city)
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-checkbox" type="checkbox" name="city[]" value="{{ $city }}" id="city_{{ Str::slug($city) }}" {{ in_array($city, (array)request('city')) ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="city_{{ Str::slug($city) }}">
                                {{ $city }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @php $first_filter = false; @endphp
            @endif

            <!-- Common Filter: Category -->
            @if(isset($filters['categories']) && count($filters['categories']) > 0)
            <div class="mb-3 border-bottom pb-2 w-100 d-block">
                <label class="fw-bold mb-2 d-flex justify-content-between align-items-center text-uppercase small tracking-wider text-muted cursor-pointer w-100 {{ $first_filter ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseCategory" aria-expanded="{{ $first_filter ? 'true' : 'false' }}" style="cursor: pointer;">
                    Category <i class="fas fa-chevron-down"></i>
                </label>
                <div class="collapse {{ $first_filter ? 'show' : '' }}" id="collapseCategory">
                    <div class="filter-scroll mb-3">
                        @foreach($filters['categories'] as $category)
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-checkbox" type="checkbox" name="category[]" value="{{ $category->id }}" id="cat_{{ $category->id }}" {{ in_array($category->id, (array)request('category')) ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="cat_{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @php $first_filter = false; @endphp
            @endif

            <!-- Price Range (Hotels & Restaurants) -->
            @if(in_array($tab, ['hotels', 'restaurants']))
            <div class="mb-3 border-bottom pb-2 w-100 d-block">
                <label class="fw-bold mb-2 d-flex justify-content-between align-items-center text-uppercase small tracking-wider text-muted cursor-pointer w-100 {{ $first_filter ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapsePrice" aria-expanded="{{ $first_filter ? 'true' : 'false' }}" style="cursor: pointer;">
                    Price Range <i class="fas fa-chevron-down"></i>
                </label>
                <div class="collapse {{ $first_filter ? 'show' : '' }}" id="collapsePrice">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted">$</span>
                            <input type="number" class="form-control filter-input" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                        </div>
                        <span class="text-muted">-</span>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted">$</span>
                            <input type="number" class="form-control filter-input" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                        </div>
                    </div>
                </div>
            </div>
            @php $first_filter = false; @endphp
            @endif

            <!-- Amenities (Hotels Only) -->
            @if($tab == 'hotels' && isset($filters['amenities']) && count($filters['amenities']) > 0)
            <div class="mb-3 border-bottom pb-2 w-100 d-block">
                <label class="fw-bold mb-2 d-flex justify-content-between align-items-center text-uppercase small tracking-wider text-muted cursor-pointer w-100 {{ $first_filter ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseAmenities" aria-expanded="{{ $first_filter ? 'true' : 'false' }}" style="cursor: pointer;">
                    Amenities <i class="fas fa-chevron-down"></i>
                </label>
                <div class="collapse {{ $first_filter ? 'show' : '' }}" id="collapseAmenities">
                    <div class="filter-scroll mb-3">
                        @foreach($filters['amenities'] as $amenity)
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-checkbox" type="checkbox" name="amenities[]" value="{{ $amenity->id }}" id="amenity_{{ $amenity->id }}" {{ in_array($amenity->id, (array)request('amenities')) ? 'checked' : '' }}>
                            <label class="form-check-label text-muted d-flex align-items-center" for="amenity_{{ $amenity->id }}">
                                <i class="fas {{ $amenity->icon }} me-2 opacity-75 text-primary text-center" style="width: 16px;"></i> {{ $amenity->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @php $first_filter = false; @endphp
            @endif

            <!-- Booking Features (Hotels Only) -->
            @if($tab == 'hotels' && isset($filters['booking_features']) && count($filters['booking_features']) > 0)
            <div class="mb-3 border-bottom pb-2 w-100 d-block">
                <label class="fw-bold mb-2 d-flex justify-content-between align-items-center text-uppercase small tracking-wider text-muted cursor-pointer w-100 {{ $first_filter ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseFeatures" aria-expanded="{{ $first_filter ? 'true' : 'false' }}" style="cursor: pointer;">
                    Booking Features <i class="fas fa-chevron-down"></i>
                </label>
                <div class="collapse {{ $first_filter ? 'show' : '' }}" id="collapseFeatures">
                    <div class="filter-scroll mb-3">
                        @foreach($filters['booking_features'] as $feature)
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-checkbox" type="checkbox" name="booking_features[]" value="{{ $feature->id }}" id="bf_{{ $feature->id }}" {{ in_array($feature->id, (array)request('booking_features')) ? 'checked' : '' }}>
                            <label class="form-check-label text-muted d-flex align-items-center" for="bf_{{ $feature->id }}">
                                <i class="fas {{ $feature->icon }} me-2 opacity-75 text-primary text-center" style="width: 16px;"></i> {{ $feature->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @php $first_filter = false; @endphp
            @endif

            <!-- Event Specific Filters -->
            @if($tab == 'events')
            <div class="mb-3 border-bottom pb-2 w-100 d-block">
                <label class="fw-bold mb-2 d-flex justify-content-between align-items-center text-uppercase small tracking-wider text-muted cursor-pointer w-100 {{ $first_filter ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseEventType" aria-expanded="{{ $first_filter ? 'true' : 'false' }}" style="cursor: pointer;">
                    Event Type <i class="fas fa-chevron-down"></i>
                </label>
                <div class="collapse {{ $first_filter ? 'show' : '' }}" id="collapseEventType">
                    <div class="mb-3">
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-checkbox" type="radio" name="type" value="" id="type_all" {{ !request('type') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="type_all">All Events</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-checkbox" type="radio" name="type" value="free" id="type_free" {{ request('type') == 'free' ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="type_free">Free Events</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-checkbox" type="radio" name="type" value="paid" id="type_paid" {{ request('type') == 'paid' ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="type_paid">Paid Events</label>
                        </div>
                    </div>
                </div>
            </div>
            @php $first_filter = false; @endphp
            
            <div class="mb-3 border-bottom pb-2 w-100 d-block">
                <label class="fw-bold mb-2 d-flex justify-content-between align-items-center text-uppercase small tracking-wider text-muted cursor-pointer w-100 {{ $first_filter ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseTiming" aria-expanded="{{ $first_filter ? 'true' : 'false' }}" style="cursor: pointer;">
                    Timing <i class="fas fa-chevron-down"></i>
                </label>
                <div class="collapse {{ $first_filter ? 'show' : '' }}" id="collapseTiming">
                    <div class="mb-3">
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-checkbox" type="checkbox" name="upcoming" value="1" id="event_upcoming" {{ request('upcoming') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="event_upcoming">Upcoming Only</label>
                        </div>
                    </div>
                </div>
            </div>
            @php $first_filter = false; @endphp
            @endif


            
            </div>
        </form>
    </div>
</div>

<style>
    /* Styling for accordion icons */
    [data-bs-toggle="collapse"] .fa-chevron-down {
        transition: transform 0.3s ease;
    }
    [data-bs-toggle="collapse"].collapsed .fa-chevron-down {
        transform: rotate(-90deg);
    }
</style>


