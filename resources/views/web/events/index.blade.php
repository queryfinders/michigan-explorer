@extends('web.layout.app_layout')

@section('webLayoutContent')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- 1. Hero Banner -->
<section class="hotel-listing-hero position-relative" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7)), url('{{ isset($page) && $page->featured_image && !isset($currentCategory) ? asset($page->featured_image) : asset('images/attraction_nature_1783508280642.jpg') }}');">
    <div class="content">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb justify-content-center text-white opacity-75">
                <li class="breadcrumb-item"><a href="{{ route('web.home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('web.events.index') }}" class="text-white text-decoration-none">Events</a></li>
                @if(isset($currentCategory))
                <li class="breadcrumb-item active text-white fw-bold" aria-current="page">{{ $currentCategory->name }}</li>
                @endif
            </ol>
        </nav>

        <h1 class="display-3 fw-bold text-white mb-3 auto-style-7">
            {{ isset($currentCategory) ? $currentCategory->name . ' Events' : (isset($page) && $page->banner_title ? $page->banner_title : 'Upcoming Events') }}
        </h1>
        <p class="lead text-white opacity-75 mb-4">
            {{ isset($currentCategory) ? 'Discover the best ' . strtolower($currentCategory->name) . ' happening near you.' : (isset($page) && $page->banner_subtitle ? $page->banner_subtitle : 'Discover concerts, festivals, workshops, and more happening across Michigan.') }}
        </p>
    </div>
</section>

<!-- 2. Browse by Category -->
<section class="category-filter-bar-sticky py-4 border-bottom bg-white shadow-sm position-relative z-index-1" data-page="events">
    <div class="container">
        <div class="category-bar-inner d-flex flex-column align-items-start gap-2">
            <h6 class="text-uppercase text-muted fw-bold small mb-0 tracking-wider text-nowrap mt-2">Browse by Category</h6>
            <div class="category-filter-wrapper d-flex align-items-center flex-wrap gap-2">
                
                <a href="{{ route('web.events.index', ['scroll' => 1]) }}" class="category-pill {{ !isset($currentCategory) ? 'active' : '' }}">
                    <span class="cat-name">All Events</span>
                    <span class="cat-count">{{ $totalEventsCount ?? 0 }}</span>
                </a>
                
                @php
                    $displayCategories = isset($featuredCategories) ? $featuredCategories->toArray() : [];
                    // Limit to 5 categories
                    $displayCategories = array_slice($displayCategories, 0, 5);
                @endphp

                @foreach($displayCategories as $cat)
                    @php $catObj = (object)$cat; @endphp
                    <a href="{{ route('web.events.category', $catObj->slug) }}" class="category-pill {{ (isset($currentCategory) && $currentCategory->id === $catObj->id) ? 'active' : '' }}">
                        <span class="cat-name">{{ $catObj->name }}</span>
                        <span class="cat-count">{{ $catObj->events_count ?? 0 }}</span>
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

<!-- 3. Time Filters (Weekly, Monthly, Past) -->
<section class="pt-4 pb-2 bg-light">
    <div class="container">
        <div class="row align-items-center g-3">
            <div class="col-md-6 d-flex flex-wrap justify-content-start gap-2" id="time-filter-buttons">
                @php $currentRoute = isset($currentCategory) ? route('web.events.category', $currentCategory->slug) : route('web.events.index'); @endphp
                
                <a href="{{ $currentRoute }}" class="btn {{ empty($filter) ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-4 btn-sm fw-semibold" data-filter="all-upcoming">All Upcoming</a>
                <a href="{{ $currentRoute }}?filter=this-week" class="btn {{ $filter == 'this-week' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-4 btn-sm fw-semibold" data-filter="this-week">This Week</a>
                <a href="{{ $currentRoute }}?filter=this-month" class="btn {{ $filter == 'this-month' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-4 btn-sm fw-semibold" data-filter="this-month">This Month</a>
                <a href="{{ $currentRoute }}?filter=past" class="btn {{ $filter == 'past' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-4 btn-sm fw-semibold" data-filter="past">Past Events</a>
            </div>
            
            <div class="col-md-6 d-flex justify-content-md-end">
                <div id="date-range-filter" class="d-flex align-items-center justify-content-md-end gap-2 flex-wrap w-100 w-md-auto">
                    <span class="small fw-semibold text-muted">Filter by Date:</span>
                    <div class="d-flex align-items-center rounded-pill border bg-white px-3 py-1 shadow-sm gap-1 custom-h-38px">
                        <i class="far fa-calendar-alt text-primary me-1"></i>
                        <input type="text" id="filter-start-date" class="border-0 bg-transparent text-center focus-none custom-date-filter-input" placeholder="Start Date" readonly>
                        <span class="text-muted small mx-1">to</span>
                        <input type="text" id="filter-end-date" class="border-0 bg-transparent text-center focus-none custom-date-filter-input" placeholder="End Date" readonly>
                    </div>
                    <button type="button" id="clear-date-filter" class="btn btn-sm btn-light rounded-pill px-3 d-none custom-h-38px">Clear</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Main Event Listing -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4" id="events-list-container">
            @include('web.events._events_grid')
        </div>
        <!-- Infinite Scroll Loading Spinner -->
        <div class="d-none justify-content-center mt-4" id="infinite-scroll-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</section>

<!-- Modal: Categories -->
<div class="modal fade" id="categoriesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold fs-4">All Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    @foreach($allCategories as $cat)
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('web.events.category', $cat->slug) }}" class="modal-category-card">
                            <div>
                                <div class="fw-bold text-heading custom-font-size-09rem">{{ $cat->name }}</div>
                                <div class="text-muted fs-xs mt-1">{{ $cat->events_count ?? 0 }} {{ Str::plural('Event', $cat->events_count ?? 0) }}</div>
                            </div>
                            <i class="fas fa-chevron-right text-muted opacity-50 fs-xs"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('webLayoutScript')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$(document).ready(function() {
    let activeFilter = '{{ !empty($filter) ? $filter : 'all-upcoming' }}'; 
    let currentUrl = '{{ $currentRoute }}';

    // Initialize Flatpickr
    const startPicker = flatpickr("#filter-start-date", {
        dateFormat: "Y-m-d",
        disableMobile: true,
        onChange: function(selectedDates, dateStr) {
            endPicker.set('minDate', dateStr);
            triggerAutoFilter();
        }
    });

    const endPicker = flatpickr("#filter-end-date", {
        dateFormat: "Y-m-d",
        disableMobile: true,
        onChange: function(selectedDates, dateStr) {
            startPicker.set('maxDate', dateStr);
            triggerAutoFilter();
        }
    });

    function getAjaxParams() {
        let params = {};
        if (activeFilter !== 'all-upcoming') {
            params.filter = activeFilter;
        }
        const startVal = $('#filter-start-date').val();
        const endVal = $('#filter-end-date').val();
        if (startVal) params.start_date = startVal;
        if (endVal) params.end_date = endVal;
        return params;
    }

    function loadEvents() {
        const params = getAjaxParams();
        $.ajax({
            url: currentUrl,
            type: 'GET',
            data: params,
            dataType: 'html',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#events-list-container').html(response);
            },
            error: function(xhr) {
                console.error("AJAX failed to load events.", xhr);
            }
        });
    }

    // Auto Apply when both Start and End dates are chosen
    function triggerAutoFilter() {
        const startVal = $('#filter-start-date').val();
        const endVal = $('#filter-end-date').val();
        
        if (startVal && endVal) {
            // Show Clear button
            $('#clear-date-filter').removeClass('d-none');
            // Run search
            loadEvents();
        }
    }

    // Toggle Date Filter Visibility based on Active Filter
    function toggleDateFilterVisibility() {
        if (activeFilter === 'all-upcoming' || activeFilter === 'past') {
            $('#date-range-filter').removeClass('d-none').addClass('d-flex');
        } else {
            $('#date-range-filter').removeClass('d-flex').addClass('d-none');
            // Reset dates when switching to pre-defined intervals
            startPicker.clear();
            endPicker.clear();
            $('#clear-date-filter').addClass('d-none');
        }
    }

    // Time filter button click handler
    $('#time-filter-buttons a.btn').on('click', function(e) {
        e.preventDefault();
        
        // Highlight active button
        $('#time-filter-buttons a.btn').removeClass('btn-dark').addClass('btn-outline-secondary');
        $(this).removeClass('btn-outline-secondary').addClass('btn-dark');

        // Set active filter
        activeFilter = $(this).data('filter');
        
        toggleDateFilterVisibility();
        loadEvents();
    });

    // Clear button click
    $('#clear-date-filter').on('click', function() {
        startPicker.clear();
        endPicker.clear();
        $(this).addClass('d-none');
        loadEvents();
    });

    let isLoading = false;

    // Detect scroll to bottom
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 400) {
            const nextLink = $('#next-page-link');
            if (nextLink.length > 0 && !isLoading) {
                loadMoreEvents(nextLink.attr('href'));
            }
        }
    });

    function loadMoreEvents(url) {
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

                // Append new event cards
                const newItems = tempDiv.find('.col-lg-4.col-md-6');
                $('#events-list-container').append(newItems);

                // Add the new pagination wrapper at the bottom
                const newPagination = tempDiv.find('#infinite-scroll-pagination-wrapper');
                $('#events-list-container').append(newPagination);

                isLoading = false;
                $('#infinite-scroll-spinner').removeClass('d-flex').addClass('d-none');
            },
            error: function(xhr) {
                console.error("AJAX failed to load more events.", xhr);
                isLoading = false;
                $('#infinite-scroll-spinner').removeClass('d-flex').addClass('d-none');
            }
        });
    }

    // Initial check
    toggleDateFilterVisibility();
});
</script>
@endsection
