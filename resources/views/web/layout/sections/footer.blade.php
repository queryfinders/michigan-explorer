<footer class="main-footer position-relative pt-5 border-top">
    <div class="container pt-4">
        <div class="row g-5 mb-5">
            <div class="col-lg-4 col-md-6 pe-lg-5">
                <h3 class="text-white fw-bold mb-4 footer-title">Michigan Explorer</h3>
                <p class="text-white-50 lh-lg mb-4 footer-description">Your premium guide to discovering the best hotels, fine dining, hidden attractions, and vibrant events across the beautiful state of Michigan.</p>
                <div class="social-icons d-flex gap-3">
                    <a href="#" aria-label="Facebook" class="d-flex align-items-center justify-content-center text-white text-decoration-none rounded-circle social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter" class="d-flex align-items-center justify-content-center text-white text-decoration-none rounded-circle social-icon"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" aria-label="Instagram" class="d-flex align-items-center justify-content-center text-white text-decoration-none rounded-circle social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="YouTube" class="d-flex align-items-center justify-content-center text-white text-decoration-none rounded-circle social-icon"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 col-6">
                <h4 class="text-white mb-4 fw-bold text-uppercase tracking-wider fs-6">Quick Links</h4>
                <ul class="list-unstyled footer-links">
                    <li class="mb-3"><a href="{{ route('web.home') }}" class="text-white-50 text-decoration-none transition-base">Home</a></li>
                    <li class="mb-3"><a href="{{ route('web.hotels.index') }}" class="text-white-50 text-decoration-none transition-base">Luxury Hotels</a></li>
                    <li class="mb-3"><a href="{{ route('web.restaurants.index') }}" class="text-white-50 text-decoration-none transition-base">Fine Dining</a></li>
                    <li class="mb-3"><a href="{{ route('web.attractions.index') }}" class="text-white-50 text-decoration-none transition-base">Attractions</a></li>
                    <li class="mb-3"><a href="{{ route('web.events.index') }}" class="text-white-50 text-decoration-none transition-base">Upcoming Events</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-6 col-6">
                <h4 class="text-white mb-4 fw-bold text-uppercase tracking-wider fs-6">Support</h4>
                <ul class="list-unstyled footer-links">
                    <li class="mb-3"><a href="{{ route('web.contact') }}" class="text-white-50 text-decoration-none transition-base">Contact Us</a></li>
                    <li class="mb-3"><a href="#" class="text-white-50 text-decoration-none transition-base">Privacy Policy</a></li>
                    <li class="mb-3"><a href="#" class="text-white-50 text-decoration-none transition-base">Terms & Conditions</a></li>
                    <!-- <li class="mb-3"><a href="{{ route('web.sitemap') }}" class="text-white-50 text-decoration-none transition-base">Sitemap</a></li> -->
                </ul>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <h4 class="text-white mb-4 fw-bold text-uppercase tracking-wider fs-6">Stay Updated</h4>
                <p class="text-white-50 mb-4">Subscribe to our newsletter for the latest luxury guides and exclusive escapes.</p>
                <form action="#" class="footer-newsletter">
                    <div class="input-group p-1 bg-white rounded-pill overflow-hidden shadow-sm">
                        <input type="email" class="form-control border-0 shadow-none px-4 text-dark" placeholder="Email address" required>
                        <button class="btn btn-primary rounded-pill px-4 fw-bold" type="submit">Join</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-top pt-4 mt-4 footer-bottom-border">
            <p class="mb-2 mb-md-0 text-white-50 small">&copy; {{ date('Y') }} Michigan Explorer. Designed for luxury travel.</p>
            <div class="d-flex gap-3 small">
                <a href="#" class="text-white-50 text-decoration-none transition-base">Privacy</a>
                <a href="#" class="text-white-50 text-decoration-none transition-base">Terms</a>
                <!-- <a href="#" class="text-white-50 text-decoration-none transition-base">Sitemap</a> -->
            </div>
        </div>
    </div>
    
    <!-- Back to top button -->
    <a href="#" aria-label="Back to top" class="btn btn-primary shadow d-flex align-items-center justify-content-center" id="backToTop" style="border-radius: 50rem; width: 50px; height: 50px;">
        <i class="fas fa-arrow-up fs-5"></i>
    </a>
</footer>



<script>
    // Back to top logic
    window.addEventListener('scroll', function() {
        const topBtn = document.getElementById('backToTop');
        if (window.scrollY > 400) {
            topBtn.style.setProperty('display', 'flex', 'important');
        } else {
            topBtn.style.setProperty('display', 'none', 'important');
        }
    });
</script>