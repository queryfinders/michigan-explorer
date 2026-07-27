@forelse($restaurants as $restaurant)
    <div class="col-lg-4 col-md-6 restaurant-card-item">
        <x-restaurant-card :restaurant="$restaurant" />
    </div>
@empty
    <div class="col-12 text-center py-5">
        <h5 class="fw-bold">No Restaurants Found</h5>
        <p class="text-muted">There are no restaurants matching your selected criteria.</p>
    </div>
@endforelse

<!-- Infinite Scroll Pagination Metadata -->
<div class="col-12 d-none" id="infinite-scroll-pagination-wrapper">
    @if($restaurants->hasMorePages())
        <a href="{{ $restaurants->nextPageUrl() }}" id="next-page-link" rel="next">Next Page</a>
    @endif
</div>
