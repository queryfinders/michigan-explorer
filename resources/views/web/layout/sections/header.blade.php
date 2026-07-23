
<nav class="navbar navbar-expand-xl fixed-top main-navbar {{ request()->routeIs('web.home') ? '' : 'scrolled inner-nav' }}" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="{{ route('web.home') }}">Michigan Explorer</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto align-items-xl-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('web.home') ? 'active' : '' }}" href="{{ route('web.home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('web.hotels.*') ? 'active' : '' }}" href="{{ route('web.hotels.index') }}">Hotels</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('web.restaurants.*') ? 'active' : '' }}" href="{{ route('web.restaurants.index') }}">Restaurants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('web.attractions.*') ? 'active' : '' }}" href="{{ route('web.attractions.index') }}">Attractions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('web.events.*') ? 'active' : '' }}" href="{{ route('web.events.index') }}">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('web.blogs.*') ? 'active' : '' }}" href="{{ route('web.blogs.index') }}">Travel Guides</a>
                </li>
                <li class="nav-item ms-xl-3 mt-2 mt-xl-0">
                    <a href="{{ route('web.contact') }}" class="btn btn-primary btn-sm rounded-pill px-4 py-2">Contact Us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Scroll Floating Search Bar Below Navbar -->
@if(!request()->routeIs('web.home'))
<div class="smart-search-container" id="scrollSearch" style="top: 170px !important; display: none;">
    <form action="{{ route('web.search') }}" method="GET" class="smart-search-box">
        <i class="fas fa-search smart-search-icon"></i>
        <input type="text" name="keyword" placeholder="Search hotels, restaurants, attractions, events or destinations..." class="smart-search-input" value="{{ request('keyword') }}">
        <button type="submit" class="smart-search-btn">Search</button>
    </form>
</div>
@endif

<script>
    // Sticky Header Scroll Effect
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('mainNav');
        const scrollSearch = document.getElementById('scrollSearch');
        
        // Handle normal scroll sticky class
        if (!navbar.classList.contains('inner-nav')) {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
        
        // Handle scrolled-active class for search bar visibility
        if (window.scrollY > 150) {
            navbar.classList.add('scrolled-active');
            if (scrollSearch) {
                scrollSearch.style.display = 'block';
                scrollSearch.classList.add('sticky-active');
            }
        } else {
            navbar.classList.remove('scrolled-active');
            if (scrollSearch) {
                scrollSearch.classList.remove('sticky-active');
                scrollSearch.style.display = 'none';
            }
        }
    });
</script>
