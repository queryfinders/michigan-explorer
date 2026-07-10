<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Alpine Plugins -->
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    // AlpineJS Components
    document.addEventListener('alpine:init', () => {
        
        Alpine.data('smartSearch', () => ({
            isOpen: false,
            isLoading: false,
            keyword: '',
            activeIndex: -1,
            groupedResults: {},
            
            // Mock categories for UI demonstration
            categories: {
                'Hotels': { icon: 'fas fa-hotel', path: '/hotels' },
                'Restaurants': { icon: 'fas fa-utensils', path: '/restaurants' },
                'Attractions': { icon: 'fas fa-map-marked-alt', path: '/attractions' },
                'Events': { icon: 'fas fa-calendar-alt', path: '/events' }
            },

            fetchSuggestions() {
                if (this.keyword.trim() === '') {
                    this.groupedResults = {};
                    this.isOpen = false;
                    return;
                }
                
                this.isLoading = true;
                this.isOpen = true;
                
                // Simulate API call for premium UI experience
                setTimeout(() => {
                    this.isLoading = false;
                    
                    // Basic client-side mock filtering
                    let term = this.keyword.toLowerCase();
                    let mockData = {
                        'Hotels': [
                            { id: 1, title: 'Blue Chip Casino Hotel', url: '/search?keyword=Blue+Chip' },
                            { id: 2, title: 'Four Winds Resort', url: '/search?keyword=Four+Winds' }
                        ],
                        'Restaurants': [
                            { id: 3, title: 'Shoreline Brewery', url: '/search?keyword=Shoreline' },
                            { id: 4, title: 'Patricks Grille', url: '/search?keyword=Patricks' }
                        ],
                        'Attractions': [
                            { id: 5, title: 'Indiana Dunes', url: '/search?keyword=Indiana+Dunes' },
                            { id: 6, title: 'Washington Park', url: '/search?keyword=Washington+Park' }
                        ],
                        'Events': [
                            { id: 7, title: 'Summer Festival', url: '/search?keyword=Festival' }
                        ]
                    };

                    let results = {};
                    let globalIndex = 0;
                    
                    for (let [cat, items] of Object.entries(mockData)) {
                        let filtered = items.filter(item => item.title.toLowerCase().includes(term) || term.includes(cat.toLowerCase().substring(0,4)));
                        if (filtered.length > 0) {
                            results[cat] = filtered.map(item => {
                                item.index = globalIndex++;
                                item.icon = this.categories[cat].icon;
                                return item;
                            });
                        }
                    }
                    
                    this.groupedResults = results;
                    this.activeIndex = -1;
                }, 500);
            },

            navigate(direction) {
                let totalItems = 0;
                Object.values(this.groupedResults).forEach(group => totalItems += group.length);
                
                if (totalItems === 0) return;
                
                this.activeIndex += direction;
                if (this.activeIndex < 0) this.activeIndex = totalItems - 1;
                if (this.activeIndex >= totalItems) this.activeIndex = 0;
            },

            selectCurrent() {
                if (this.activeIndex >= 0) {
                    for (let group of Object.values(this.groupedResults)) {
                        for (let item of group) {
                            if (item.index === this.activeIndex) {
                                window.location.href = item.url;
                                return;
                            }
                        }
                    }
                }
                // If nothing is selected, submit form normally
                if (this.keyword.trim() !== '') {
                    window.location.href = '/search?keyword=' + encodeURIComponent(this.keyword);
                }
            },

            onSubmit(e) {
                if (this.activeIndex >= 0) {
                    e.preventDefault();
                    this.selectCurrent();
                }
            },
            
            highlight(text) {
                if (!this.keyword) return text;
                const regex = new RegExp(`(${this.keyword})`, 'gi');
                return text.replace(regex, '<span class="autocomplete-match">$1</span>');
            }
        }));

        Alpine.data('counter', (target) => ({
            count: 0,
            target: target,
            started: false,
            start() {
                if (this.started) return;
                this.started = true;

                const duration = 1800;
                const startTime = performance.now();
                const easeOutExpo = (t) => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t));

                const tick = (now) => {
                    const progress = Math.min((now - startTime) / duration, 1);
                    this.count = Math.floor(this.target * easeOutExpo(progress));
                    if (progress < 1) {
                        requestAnimationFrame(tick);
                    } else {
                        this.count = this.target;
                    }
                };

                requestAnimationFrame(tick);
            }
        }));
    });

    // General Scripts for the Premium Theme
    $(document).ready(function() {
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50,
        });
        
        console.log("Michigan Explorer Theme Initialized.");
    });
</script>