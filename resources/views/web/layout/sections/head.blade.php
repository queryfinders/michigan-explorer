<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>@yield('title')</title>
<link rel="icon" href="{!! asset('website/assets/images/favicon/favicon.png') !!}">
<meta name="msapplication-TileColor" content="#79bde9">
<meta name="msapplication-TileImage" content="/mstile-144x144.png">
<meta name="theme-color" content="#ffffff">
<meta name="csrf-token" content="{{ csrf_token() }}">

<script type="application/ld+json">
 @yield('structured_data')
</script>
@yield('meta_description')
@yield('og_tags')
@yield('canonical')

<link rel="icon" href="{{ asset('website/assets/images/favicon/favicon-32x32.png') }}" sizes="32x32">
<link rel="icon" href="{{ asset('website/assets/images/favicon/favicon-192x192.png') }}" sizes="192x192">
<link rel="apple-touch-icon" href="{{ asset('website/assets/images/favicon/favicon-180x180.png') }}" sizes="180x180">


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('css/theme.css') }}">




