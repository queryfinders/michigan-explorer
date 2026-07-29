@include('web.layout.sections.seo')
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
    @hasSection('seo_title')
        @yield('seo_title')
    @else
        @yield('title')
    @endif
</title>
<link rel="icon" href="{{ asset('assets/img/favicon/favicon.png') }}">
<meta name="msapplication-TileColor" content="#79bde9">
<meta name="msapplication-TileImage" content="/mstile-144x144.png">
<meta name="theme-color" content="#ffffff">
<meta name="csrf-token" content="{{ csrf_token() }}">

<script type="application/ld+json">
    @hasSection('seo_structured_data')
        @yield('seo_structured_data')
    @else
        @yield('structured_data')
    @endif
</script>
@yield('custom_schema')
@hasSection('seo_description')
    @yield('seo_description')
@else
    @yield('meta_description')
@endif

@hasSection('seo_og_tags')
    @yield('seo_og_tags')
@else
    @yield('og_tags')
@endif

@hasSection('seo_canonical')
    @yield('seo_canonical')
@else
    @yield('canonical')
@endif

<link rel="apple-touch-icon" href="{{ asset('assets/img/favicon/favicon.png') }}">


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('css/theme.css') }}">




