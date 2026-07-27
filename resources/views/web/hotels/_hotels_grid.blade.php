@forelse($hotels as $hotel)
    <div class="col-lg-4 col-md-6 hotel-card-item">
        <x-hotel-card :hotel="$hotel" />
    </div>
@empty
    <div class="col-12 text-center py-5">
        <h5 class="fw-bold">No Hotels Found</h5>
        <p class="text-muted">There are no hotels matching your selected criteria.</p>
    </div>
@endforelse

<!-- Infinite Scroll Pagination Metadata -->
<div class="col-12 d-none" id="infinite-scroll-pagination-wrapper">
    @if($hotels->hasMorePages())
        <a href="{{ $hotels->nextPageUrl() }}" id="next-page-link" rel="next">Next Page</a>
    @endif
</div>
