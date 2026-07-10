<footer class="main-footer position-relative pt-5 pb-4 border-top" style="background-color: var(--dark-bg); border-color: rgba(255,255,255,0.05) !important;">
    <div class="container pt-4">
        <div class="row g-5 mb-5">
            <div class="col-lg-4 col-md-6 pe-lg-5">
                <h3 class="text-white fw-bold mb-4" style="font-family: var(--font-heading); font-size: 1.8rem; letter-spacing: -0.5px;">Michigan Explorer</h3>
                <p class="text-white-50 lh-lg mb-4" style="font-size: 1.05rem;">Your premium guide to discovering the best hotels, fine dining, hidden attractions, and vibrant events across the beautiful state of Michigan.</p>
                <div class="social-icons d-flex gap-3">
                    <a href="#" class="d-flex align-items-center justify-content-center text-white text-decoration-none rounded-circle" style="width: 45px; height: 45px; background: rgba(255,255,255,0.05); transition: all 0.3s ease;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="d-flex align-items-center justify-content-center text-white text-decoration-none rounded-circle" style="width: 45px; height: 45px; background: rgba(255,255,255,0.05); transition: all 0.3s ease;"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="d-flex align-items-center justify-content-center text-white text-decoration-none rounded-circle" style="width: 45px; height: 45px; background: rgba(255,255,255,0.05); transition: all 0.3s ease;"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="d-flex align-items-center justify-content-center text-white text-decoration-none rounded-circle" style="width: 45px; height: 45px; background: rgba(255,255,255,0.05); transition: all 0.3s ease;"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 col-6">
                <h5 class="text-white mb-4 fw-bold text-uppercase tracking-wider fs-6">Quick Links</h5>
                <ul class="list-unstyled footer-links">
                    <li class="mb-3"><a href="{{ route('web.home') }}" class="text-white-50 text-decoration-none transition-base">Home</a></li>
                    <li class="mb-3"><a href="{{ route('web.hotels.index') }}" class="text-white-50 text-decoration-none transition-base">Luxury Hotels</a></li>
                    <li class="mb-3"><a href="{{ route('web.restaurants.index') }}" class="text-white-50 text-decoration-none transition-base">Fine Dining</a></li>
                    <li class="mb-3"><a href="{{ route('web.attractions.index') }}" class="text-white-50 text-decoration-none transition-base">Attractions</a></li>
                    <li class="mb-3"><a href="{{ route('web.events.index') }}" class="text-white-50 text-decoration-none transition-base">Upcoming Events</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-6 col-6">
                <h5 class="text-white mb-4 fw-bold text-uppercase tracking-wider fs-6">Support</h5>
                <ul class="list-unstyled footer-links">
                    <li class="mb-3"><a href="{{ route('web.contact') }}" class="text-white-50 text-decoration-none transition-base">Contact Us</a></li>
                    <li class="mb-3"><a href="#" class="text-white-50 text-decoration-none transition-base">Privacy Policy</a></li>
                    <li class="mb-3"><a href="#" class="text-white-50 text-decoration-none transition-base">Terms & Conditions</a></li>
                    <li class="mb-3"><a href="{{ route('web.sitemap') }}" class="text-white-50 text-decoration-none transition-base">Sitemap</a></li>
                </ul>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <h5 class="text-white mb-4 fw-bold text-uppercase tracking-wider fs-6">Stay Updated</h5>
                <p class="text-white-50 mb-4">Subscribe to our newsletter for the latest luxury guides and exclusive escapes.</p>
                <form action="#" class="footer-newsletter">
                    <div class="input-group p-1 bg-white rounded-pill overflow-hidden shadow-sm">
                        <input type="email" class="form-control border-0 shadow-none px-4 text-dark" placeholder="Email address" required>
                        <button class="btn btn-primary rounded-pill px-4 fw-bold" type="submit">Join</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-top pt-4 mt-4" style="border-color: rgba(255,255,255,0.05) !important;">
            <p class="mb-2 mb-md-0 text-white-50 small">&copy; {{ date('Y') }} Michigan Explorer. Designed for luxury travel.</p>
            <div class="d-flex gap-3 small">
                <a href="#" class="text-white-50 text-decoration-none transition-base">Privacy</a>
                <a href="#" class="text-white-50 text-decoration-none transition-base">Terms</a>
                <a href="#" class="text-white-50 text-decoration-none transition-base">Sitemap</a>
            </div>
        </div>
    </div>
    
    <!-- Back to top button -->
    <a href="#" class="btn btn-primary rounded-circle shadow d-flex align-items-center justify-content-center" id="backToTop" style="position: fixed; bottom: 30px; right: 30px; display: none !important; width: 55px; height: 55px; z-index: 999; transition: all 0.3s ease;">
        <i class="fas fa-arrow-up fs-5"></i>
    </a>
</footer>

<style>
.social-icons a:hover {
    background: var(--primary-color) !important;
    transform: translateY(-3px);
}
.footer-links a:hover {
    color: #fff !important;
    padding-left: 5px;
}
.transition-base {
    transition: all 0.3s ease;
}
#backToTop:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(255, 159, 28, 0.4) !important;
}
</style>

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