@forelse($attractions as $index => $attraction)
    <!-- Attraction Card -->
    <div class="col-lg-4 col-md-6 attraction-card-item">
        <x-attraction-card :attraction="$attraction" :featured="($attraction->is_featured ?? 0) == 1" />
    </div>
@empty
    <!-- Static Fallback Data for Empty State -->
    @for($i=1; $i<=6; $i++)
    <div class="col-lg-4 col-md-6 attraction-card-item">
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

<!-- Infinite Scroll Pagination Metadata -->
<div class="col-12 d-none" id="infinite-scroll-pagination-wrapper">
    @if($attractions->hasMorePages())
        <a href="{{ $attractions->nextPageUrl() }}" id="next-page-link" rel="next">Next Page</a>
    @endif
</div>
