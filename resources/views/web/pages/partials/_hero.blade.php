<!-- 1. PREMIUM HERO SECTION -->
<section class="hero-premium position-relative overflow-hidden">
    <div class="hero-bg-parallax">
        <div class="hero-bg-zoom" role="img" aria-label="{{ $page->featured_image_alt ?? 'Michigan Explorer Banner' }}" style="background-image: url('{{ $page && $page->featured_image ? asset($page->featured_image) : asset('images/hero_banner_1783508250640.jpg') }}');"></div>
    </div>

    <div class="container position-relative text-white py-5 my-5">
        
        <!-- Typography with Stagger Animation -->
        <div class="text-center mb-3">
            <h1 class="display-3 fw-bold mb-4 font-heading text-shadow-md" data-aos="fade-up" data-aos-duration="1000">
                {{ $page->banner_title ?? 'Discover the True Beauty of Michigan' }}
            </h1>
            <p class="lead fs-5 mx-auto hero-subtitle text-shadow-sm lh-18 mx-auto" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                {{ $page->banner_subtitle ?? 'Experience luxury stays, amazing restaurants, breathtaking attractions, exciting events, and unforgettable adventures across Michigan.' }}
            </p>
        </div>

        <!-- Alpine.js Smart Search Component -->
        <div x-data="smartSearch()" id="heroSearchContainer" class="smart-search-container" data-aos="zoom-in" data-aos-delay="400" data-aos-duration="1000" @click.away="isOpen = false">
            
            <form action="{{ route('web.search') }}" method="GET" class="smart-search-box" @submit="onSubmit">
                <i class="fas fa-search smart-search-icon" x-show="!isLoading"></i>
                <div class="search-loader" x-show="isLoading" class="d-none"></div>
                
                <input 
                    type="text" 
                    name="keyword" 
                    class="smart-search-input" 
                    placeholder="Search hotels, restaurants, attractions, events or destinations..." 
                    autocomplete="off"
                    x-model="keyword"
                    @input.debounce.300ms="fetchSuggestions"
                    @focus="isOpen = true; if(keyword.length > 0) fetchSuggestions()"
                    @keydown.down.prevent="navigate(1)"
                    @keydown.up.prevent="navigate(-1)"
                    @keydown.enter.prevent="selectCurrent"
                    @keydown.escape="isOpen = false"
                >
                
                <button type="submit" class="smart-search-btn d-none d-sm-block">Search</button>
                <button type="submit" class="smart-search-btn d-block d-sm-none px-3 rounded-circle btn-icon-50 p-0"><i class="fas fa-search"></i></button>
            </form>

            <!-- Autocomplete Dropdown -->
            <div class="autocomplete-dropdown" :class="{ 'show': isOpen && keyword.length > 0 }">
                
                <div x-show="!isLoading && Object.keys(groupedResults).length === 0 && keyword.length > 0" class="p-4 text-center text-muted">
                    <i class="fas fa-search-minus fs-2 mb-2 text-muted opacity-50"></i>
                    <p class="mb-0">No results found for "<span x-text="keyword" class="fw-bold"></span>"</p>
                </div>

                <template x-for="(group, category) in groupedResults" :key="category">
                    <div class="autocomplete-group">
                        <div class="autocomplete-group-title" x-text="category"></div>
                        <template x-for="item in group.items" :key="item.id">
                            <a :href="item.url" class="autocomplete-item d-flex align-items-center" :class="{ 'active': activeIndex === item.index }" @mouseenter="activeIndex = item.index">
                                <img :src="item.image" alt="" class="rounded me-3 shadow-sm hero-search-thumb">
                                <div>
                                    <div class="fw-bold text-dark lh-1 mb-1" x-html="highlight(item.title)"></div>
                                    <div class="small text-muted lh-1"><i :class="group.icon" class="me-1"></i><span x-text="item.location"></span></div>
                                </div>
                            </a>
                        </template>
                        <template x-if="group.has_more">
                            <div class="text-center p-2 border-top bg-light">
                                <a :href="group.view_all_url" class="small fw-bold text-primary text-decoration-none">View All <span x-text="category"></span> &rarr;</a>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
        
        <!-- Popular Searches (Chips) -->
        @if(isset($searchShortcuts) && $searchShortcuts->count() > 0)
        <div class="text-center pt-4" data-aos="fade-up" data-aos-delay="600">
            <p class="small text-white mb-3 fw-bold text-uppercase tracking-wider text-shadow-dark">Popular Searches</p>
            <div class="popular-chips-wrapper justify-content-center">
                @foreach($searchShortcuts as $shortcut)
                <a href="{{ route('web.search_shortcuts.track', $shortcut->id) }}" target="{{ $shortcut->open_in == 'new_tab' ? '_blank' : '_self' }}" class="premium-chip">
                    @if($shortcut->icon)
                        <i class="{{ $shortcut->icon }} {{ $shortcut->icon_color }}"></i> 
                    @endif
                    {{ $shortcut->title }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator d-inline-flex align-items-center gap-2" onclick="window.scrollTo({top: window.innerHeight, behavior: 'smooth'})">
        <span>Explore More</span>
        <i class="fas fa-chevron-down"></i>
    </div>
</section>
