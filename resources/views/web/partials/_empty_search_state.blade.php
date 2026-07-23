@php
    $tabName = $tab != 'all' ? str_replace('_', ' ', $tab) : 'results';
@endphp

<div class="text-center py-5 my-4 bg-white rounded shadow-sm border">
    <div class="mb-4">
        <i class="fas fa-search-location text-muted opacity-50" style="font-size: 5rem;"></i>
    </div>
    
    @if($q)
        <h3 class="fw-bold mb-2">No {{ $tabName }} found for <span class="text-primary">"{{ $q }}"</span></h3>
    @else
        <h3 class="fw-bold mb-2">No {{ $tabName }} found</h3>
    @endif
    
    <div class="text-muted mb-4 mx-auto" style="max-width: 600px;">
        <p class="mb-2">We couldn't find any exact matches for your search.</p>
        <div class="small bg-light p-3 rounded d-inline-block mx-auto text-start border">
            <strong class="d-block mb-2 text-dark">Suggestions:</strong>
            <ul class="list-unstyled mb-0 text-muted">
                <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Try a different keyword</li>
                @if(request()->except(['q', 'tab', 'page']))
                <li class="mb-1"><i class="fas fa-check text-success me-2"></i><a href="{{ request()->fullUrlWithQuery(array_merge(request()->except(['q', 'tab', 'page']), ['q' => null])) }}" class="text-decoration-none">Remove some filters</a></li>
                @endif
                <li><i class="fas fa-check text-success me-2"></i>Check spelling</li>
            </ul>
        </div>
    </div>
    
    <!-- Context Aware Links -->
    @if($tab != 'all' && collect($counts)->except(['all', $tab])->sum() > 0)
        <div class="mb-4 p-3 bg-light rounded d-inline-block">
            <p class="mb-2 fw-bold text-dark">However, we found results in other categories:</p>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                @foreach(['hotels', 'restaurants', 'attractions', 'events', 'blogs'] as $altTab)
                    @php $actualTabName = $altTab == 'blogs' ? 'travel_guides' : $altTab; @endphp
                    @if($actualTabName != $tab && ($counts[$altTab] ?? 0) > 0)
                        <a href="{{ request()->fullUrlWithQuery(['tab' => $actualTabName]) }}" class="badge bg-white text-primary border p-2 text-decoration-none shadow-sm hover-elevate">
                            {{ ucwords(str_replace('_', ' ', $actualTabName)) }} ({{ $counts[$altTab] }})
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <!-- Dynamic Actions -->
    <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
        <a href="{{ route('web.search', ['clear' => 1]) }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold d-inline-flex align-items-center justify-content-center">Clear Search</a>
        
        @if($tab == 'hotels')
            <a href="{{ route('web.search', ['tab' => 'hotels']) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center">Browse All Hotels</a>
            <a href="{{ route('web.search', ['tab' => 'hotels', 'featured' => 1]) }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold d-inline-flex align-items-center justify-content-center">Featured Hotels</a>
        @elseif($tab == 'restaurants')
            <a href="{{ route('web.search', ['tab' => 'restaurants']) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center">Browse All Restaurants</a>
            <a href="{{ route('web.search', ['tab' => 'restaurants', 'featured' => 1]) }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold d-inline-flex align-items-center justify-content-center">Popular Restaurants</a>
        @elseif($tab == 'attractions')
            <a href="{{ route('web.search', ['tab' => 'attractions']) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center">Browse All Attractions</a>
            <a href="{{ route('web.search', ['tab' => 'attractions', 'featured' => 1]) }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold d-inline-flex align-items-center justify-content-center">Top Attractions</a>
        @elseif($tab == 'events')
            <a href="{{ route('web.search', ['tab' => 'events']) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center">Browse All Events</a>
            <a href="{{ route('web.search', ['tab' => 'events', 'upcoming' => 1]) }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold d-inline-flex align-items-center justify-content-center">Upcoming Events</a>
        @elseif($tab == 'travel_guides')
            <a href="{{ route('web.search', ['tab' => 'travel_guides']) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center">Browse All Guides</a>
        @else
            <a href="{{ route('web.search', ['tab' => 'hotels']) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center">Explore Hotels</a>
        @endif
    </div>
</div>

@if(isset($recommendations) && $recommendations->isNotEmpty())
    <div class="mt-5">
        <h4 class="fw-bold mb-4 border-bottom pb-2">Recommended {{ ucfirst($tabName) }}</h4>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
            @foreach($recommendations as $item)
                <div class="col">
                    @if($tab == 'hotels')
                        @include('web.partials.cards._hotel_card', ['hotel' => $item])
                    @elseif($tab == 'restaurants')
                        @include('web.partials.cards._restaurant_card', ['restaurant' => $item])
                    @elseif($tab == 'attractions')
                        @include('web.partials.cards._attraction_card', ['attraction' => $item])
                    @elseif($tab == 'events')
                        @include('web.partials.cards._event_card', ['event' => $item])
                    @elseif($tab == 'travel_guides')
                        @include('web.partials.cards._blog_card', ['blog' => $item])
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
