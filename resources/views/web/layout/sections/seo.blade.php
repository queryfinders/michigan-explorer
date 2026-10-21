@php
    $defaultTitle = 'Michigan Explorer - Luxury Travel & Tourism';
    $defaultDesc = 'Discover the true beauty of Michigan. Explore luxury stays, amazing restaurants, breathtaking attractions, exciting events, and unforgettable adventures.';
    
    // Determine the active SEO object dynamically
    $activeSeo = null;
    if (isset($seo)) {
        $activeSeo = $seo;
    } elseif (isset($page) && method_exists($page, 'relationLoaded') && $page->relationLoaded('seo') && $page->seo) {
        $activeSeo = $page->seo;
    } elseif (isset($blog) && method_exists($blog, 'relationLoaded') && $blog->relationLoaded('seo') && $blog->seo) {
        $activeSeo = $blog->seo;
    } elseif (isset($hotel) && method_exists($hotel, 'relationLoaded') && $hotel->relationLoaded('seo') && $hotel->seo) {
        $activeSeo = $hotel->seo;
    } elseif (isset($restaurant) && method_exists($restaurant, 'relationLoaded') && $restaurant->relationLoaded('seo') && $restaurant->seo) {
        $activeSeo = $restaurant->seo;
    } elseif (isset($attraction) && method_exists($attraction, 'relationLoaded') && $attraction->relationLoaded('seo') && $attraction->seo) {
        $activeSeo = $attraction->seo;
    } elseif (isset($event) && method_exists($event, 'relationLoaded') && $event->relationLoaded('seo') && $event->seo) {
        $activeSeo = $event->seo;
    }
@endphp

@if($activeSeo)
    @section('seo_title', $activeSeo->meta_title ?? $defaultTitle)
    
    @section('seo_description')
        <meta name="description" content="{{ $activeSeo->meta_description ?? $defaultDesc }}">
    @endsection
    
    @section('seo_og_tags')
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $activeSeo->og_title ?? $activeSeo->meta_title ?? $defaultTitle }}">
        <meta property="og:description" content="{{ $activeSeo->og_description ?? $activeSeo->meta_description ?? $defaultDesc }}">
        @if($activeSeo->og_image)
            <meta property="og:image" content="{{ asset($activeSeo->og_image) }}">
        @endif

        <!-- Twitter -->
        <meta property="twitter:card" content="{{ $activeSeo->twitter_card ?? 'summary_large_image' }}">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="{{ $activeSeo->og_title ?? $activeSeo->meta_title ?? $defaultTitle }}">
        <meta property="twitter:description" content="{{ $activeSeo->og_description ?? $activeSeo->meta_description ?? $defaultDesc }}">
        @if($activeSeo->og_image)
            <meta property="twitter:image" content="{{ asset($activeSeo->og_image) }}">
        @endif
    @endsection
    
    @if($activeSeo->schema_markup)
        @section('seo_structured_data')
            @php
                $schemaJson = preg_replace('#https?://(?:127\.0\.0\.1|localhost|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(?::\d+)?#i', rtrim(url('/'), '/'), $activeSeo->schema_markup);
                $schemaJson = str_replace(rtrim(url('/'), '/') . '/home"', rtrim(url('/'), '/') . '"', $schemaJson);
            @endphp
            {!! $schemaJson !!}
        @endsection
    @endif
@else
    {{-- Fallback default values --}}
    @section('title', $defaultTitle)
    @section('meta_description')
        <meta name="description" content="{{ $defaultDesc }}">
    @endsection
@endif
