@extends('web.layout.app_layout')

@section('title', 'Michigan Explorer - Luxury Travel & Tourism')

@section('webLayoutContent')

    @include('web.pages.partials._hero')
    @include('web.pages.partials._featured_hotels')
    @include('web.pages.partials._event_strip')
    @include('web.pages.partials._featured_restaurants')
    @include('web.pages.partials._featured_attractions')
    @include('web.pages.partials._upcoming_events')
    @include('web.pages.partials._affiliate_promotions')
    @include('web.pages.partials._travel_guides')
    @include('web.pages.partials._events_widget')
    @include('web.pages.partials._newsletter')

@endsection

@section('webLayoutScript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchContainer = document.getElementById('heroSearchContainer');
        if (!searchContainer) return;
        
        const initialOffset = searchContainer.offsetTop + 120;
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > initialOffset) {
                searchContainer.classList.add('sticky-active');
            } else {
                searchContainer.classList.remove('sticky-active');
            }
        });
    });
</script>
@endsection
