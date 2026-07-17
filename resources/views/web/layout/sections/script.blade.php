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
        
        Alpine.data('smartSearch', (initialKeyword = '') => ({
            isOpen: false,
            isLoading: false,
            keyword: initialKeyword,
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
                if (this.keyword.trim() === '' || this.keyword.length < 2) {
                    this.groupedResults = {};
                    this.isOpen = false;
                    return;
                }
                
                this.isLoading = true;
                this.isOpen = true;
                
                if (this.debounceTimer) clearTimeout(this.debounceTimer);

                this.debounceTimer = setTimeout(() => {
                    fetch('/search/autocomplete?q=' + encodeURIComponent(this.keyword))
                        .then(res => res.json())
                        .then(data => {
                            let results = {};
                            let globalIndex = 0;
                            
                            for (let [cat, groupData] of Object.entries(data)) {
                                if (groupData.items && groupData.items.length > 0) {
                                    results[cat] = { ...groupData };
                                    results[cat].items = groupData.items.map(item => {
                                        item.index = globalIndex++;
                                        return item;
                                    });
                                }
                            }
                            
                            this.groupedResults = results;
                            this.activeIndex = -1;
                            this.isLoading = false;
                        })
                        .catch(err => {
                            console.error('Error fetching suggestions:', err);
                            this.isLoading = false;
                        });
                }, 300);
            },

            navigate(direction) {
                let totalItems = 0;
                Object.values(this.groupedResults).forEach(group => {
                    if(group.items) totalItems += group.items.length;
                });
                
                if (totalItems === 0) return;
                
                this.activeIndex += direction;
                if (this.activeIndex < 0) this.activeIndex = totalItems - 1;
                if (this.activeIndex >= totalItems) this.activeIndex = 0;
            },

            selectCurrent() {
                if (this.activeIndex >= 0) {
                    for (let group of Object.values(this.groupedResults)) {
                        for (let item of group.items || []) {
                            if (item.index === this.activeIndex) {
                                window.location.href = item.url;
                                return;
                            }
                        }
                    }
                }
                // If nothing is selected, submit form normally
                if (this.keyword.trim() !== '') {
                    window.location.href = '/search?q=' + encodeURIComponent(this.keyword);
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
        
        // Reading Progress Bar (Global)
        const progressBar = document.getElementById('readingProgressBar');
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            if(progressBar) {
                progressBar.style.width = scrolled + '%';
            }
        });
        
        // Smooth Parallax Scroll Effect for Hero Backgrounds
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset || document.documentElement.scrollTop;
            document.querySelectorAll('.hero-bg-parallax').forEach(el => {
                const parent = el.parentElement;
                const parentRect = parent.getBoundingClientRect();
                if (parentRect.bottom >= 0 && parentRect.top <= window.innerHeight) {
                    const relativeOffset = scrolled * 0.15;
                    el.style.transform = `translate3d(0px, ${relativeOffset}px, 0px)`;
                }
            });
        });
        
        console.log("Michigan Explorer Theme Initialized.");
    });
</script>

<script>
function shareCurrentPage(title) {
    if (navigator.share) {
        navigator.share({
            title: title,
            url: window.location.href
        }).catch(console.error);
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => alert('Link copied to clipboard!'));
    }
}
</script>