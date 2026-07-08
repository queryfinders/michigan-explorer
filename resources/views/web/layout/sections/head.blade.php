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

<link rel="icon" href="{{ asset('website/assets/images/favicon/favicon-32x32.png') }}" sizes="32x32">
<link rel="icon" href="{{ asset('website/assets/images/favicon/favicon-192x192.png') }}" sizes="192x192">
<link rel="apple-touch-icon" href="{{ asset('website/assets/images/favicon/favicon-180x180.png') }}" sizes="180x180">


<link rel="stylesheet" type="text/css" href="{{ asset('website/assets/styles/custom.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('website/assets/styles/all.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('website/assets/styles/bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('website/assets/styles/slick.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('website/assets/styles/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('website/assets/styles/responsive.css') }}">




