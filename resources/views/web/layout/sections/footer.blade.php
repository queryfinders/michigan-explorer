@php
    // Only show footer_banner on pages that don't already render their own detail promotion.
    // Detail pages (hotel, restaurant, attraction, blog) pass $detailPromotion via their controller.
    $showFooterBanner = !isset($detailPromotion) || is_null($detailPromotion);
    $footerPromotion = $showFooterBanner
        ? \App\Models\AffiliatePromotion::forPlacement('footer_banner')
        : null;
@endphp
@if($footerPromotion)
    <x-promo-banner :promotion="$footerPromotion" />
@endif

<footer class="main-footer position-relative pt-5 border-top">
    <div class="container pt-4">
        <div class="row g-5 mb-5">
            <div class="col-lg-4 col-md-6 pe-lg-5">
                <h3 class="text-white fw-bold mb-4 footer-title">Michigan Explorer</h3>
                <p class="text-white-50 lh-lg mb-4 footer-description">Your premium guide to discovering the best hotels, fine dining, hidden attractions, and vibrant events across the beautiful state of Michigan.</p>
                @php 
                    $globalSettings = \App\Models\Setting::pluck('value', 'key');
                    $fb = $globalSettings['social_facebook'] ?? '';
                    $tw = $globalSettings['social_twitter'] ?? '';
                    $ig = $globalSettings['social_instagram'] ?? '';
                    $yt = $globalSettings['social_youtube'] ?? '';
                @endphp
                <div class="social-icons d-flex gap-3">
                    @if($fb)<a href="{{ $fb }}" aria-label="Facebook" target="_blank" class="d-flex align-items-center justify-content-center text-white text-decoration-none rounded-circle social-icon"><i class="fab fa-facebook-f"></i></a>@endif
                    @if($tw)<a href="{{ $tw }}" aria-label="X" target="_blank" class="d-flex align-items-center justify-content-center text-white text-decoration-none rounded-circle social-icon"><i class="fab fa-x-twitter"></i></a>@endif
                    @if($ig)<a href="{{ $ig }}" aria-label="Instagram" target="_blank" class="d-flex align-items-center justify-content-center text-white text-decoration-none rounded-circle social-icon"><i class="fab fa-instagram"></i></a>@endif
                    @if($yt)<a href="{{ $yt }}" aria-label="YouTube" target="_blank" class="d-flex align-items-center justify-content-center text-white text-decoration-none rounded-circle social-icon"><i class="fab fa-youtube"></i></a>@endif
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
                        <!-- <li class="mb-3"><a href="#" class="text-white-50 text-decoration-none transition-base">Privacy Policy</a></li>
                        <li class="mb-3"><a href="#" class="text-white-50 text-decoration-none transition-base">Terms & Conditions</a></li> -->
                    <!-- <li class="mb-3"><a href="{{ route('web.sitemap') }}" class="text-white-50 text-decoration-none transition-base">Sitemap</a></li> -->
                </ul>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <h4 class="text-white mb-4 fw-bold text-uppercase tracking-wider fs-6">Stay Updated</h4>
                <p class="text-white-50 mb-4">Subscribe to our newsletter for the latest luxury guides and exclusive escapes.</p>
                <form id="footerNewsletterForm" method="POST" action="{{ route('newsletter.subscribe') }}" class="footer-newsletter">
                    @csrf
                    <input type="hidden" name="source" value="footer">
                    <div class="input-group p-1 bg-white rounded-pill overflow-hidden shadow-sm">
                        <input type="email" name="email" class="form-control border-0 shadow-none px-4 text-dark" placeholder="Email address" required>
                        <button class="btn btn-primary rounded-pill px-4 fw-bold" type="submit">Join</button>
                    </div>
                </form>

                <!-- Ask AI Section -->
                <div class="mt-4 pt-2">
                    <h5 class="text-white mb-3 fw-bold text-uppercase tracking-wider fs-6">Ask AI About Michigan Explorer</h5>
                    @php
                        $aiPrompt = urlencode("Tell me about Michigan Explorer. What luxury hotels, fine dining restaurants, attractions, events, and travel guides do they offer, and how can I explore Michigan? Please use information from " . url('/'));
                    @endphp
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <!-- ChatGPT -->
                        <a href="https://chatgpt.com/?prompt={{ $aiPrompt }}" target="_blank" rel="noopener noreferrer" class="ai-tool-btn d-flex align-items-center justify-content-center rounded-3 text-white text-decoration-none transition-all" aria-label="Ask ChatGPT" title="Ask ChatGPT">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11.217 19.384a3.501 3.501 0 0 0 6.783 -1.217v-5.167l-6 -3.35" />
                                <path d="M5.214 15.014a3.501 3.501 0 0 0 4.446 5.266l4.34 -2.534v-6.946" />
                                <path d="M6 7.63c-1.391 -.236 -2.787 .395 -3.534 1.689a3.474 3.474 0 0 0 1.271 4.745l4.263 2.514l6 -3.348" />
                                <path d="M12.783 4.616a3.501 3.501 0 0 0 -6.783 1.217v5.067l6 3.45" />
                                <path d="M18.786 8.986a3.501 3.501 0 0 0 -4.446 -5.266l-4.34 2.534v6.946" />
                                <path d="M18 16.302c1.391 .236 2.787 -.395 3.534 -1.689a3.474 3.474 0 0 0 -1.271 -4.745l-4.308 -2.514l-5.955 3.42" />
                            </svg>
                        </a>
                        <!-- Claude (Anthropic) -->
                        <a href="https://claude.ai/new?q={{ $aiPrompt }}" target="_blank" rel="noopener noreferrer" class="ai-tool-btn d-flex align-items-center justify-content-center rounded-3 text-white text-decoration-none transition-all fw-bold" aria-label="Ask Claude" title="Ask Claude">
                            <span style="font-weight: 800; font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 15px; display:flex; align-items:center; justify-content:center;">A\</span>
                        </a>
                        <!-- Perplexity -->
                        <a href="https://www.perplexity.ai/?q={{ $aiPrompt }}" target="_blank" rel="noopener noreferrer" class="ai-tool-btn d-flex align-items-center justify-content-center rounded-3 text-white text-decoration-none transition-all" aria-label="Ask Perplexity" title="Ask Perplexity">
                            <svg fill="currentColor" width="20" height="20" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.3977 7.0896h-2.3106V.0676l-7.5094 6.3542V.1577h-1.1554v6.1966L4.4904 0v7.0896H1.6023v10.3976h2.8882V24l6.932-6.3591v6.2005h1.1554v-6.0469l6.9318 6.1807v-6.4879h2.8882V7.0896zm-3.4657-4.531v4.531h-5.355l5.355-4.531zm-13.2862.0676 4.8691 4.4634H5.6458V2.6262zM2.7576 16.332V8.245h7.8476l-6.1149 6.1147v1.9723H2.7576zm2.8882 5.0404v-3.8852h.0001v-2.6488l5.7763-5.7764v7.0111l-5.7764 5.2993zm12.7086.0248-5.7766-5.1509V9.0618l5.7766 5.7766v6.5588zm2.8882-5.0652h-1.733v-1.9723L13.3948 8.245h7.8478v8.087z"/>
                            </svg>
                        </a>
                        <!-- Google AI / Gemini -->
                        <a href="https://www.google.com/search?udm=50&q={{ $aiPrompt }}" target="_blank" rel="noopener noreferrer" class="ai-tool-btn d-flex align-items-center justify-content-center rounded-3 text-white text-decoration-none transition-all" aria-label="Ask Google AI" title="Ask Google Gemini">
                            <svg fill="currentColor" width="20" height="20" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.04 19.32Q12 21.51 12 24q0-2.49.93-4.68.96-2.19 2.58-3.81t3.81-2.55Q21.51 12 24 12q-2.49 0-4.68-.93a12.3 12.3 0 0 1-3.81-2.58 12.3 12.3 0 0 1-2.58-3.81Q12 2.49 12 0q0 2.49-.96 4.68-.93 2.19-2.55 3.81a12.3 12.3 0 0 1-3.81 2.58Q2.49 12 0 12q2.49 0 4.68.96 2.19.93 3.81 2.55t2.55 3.81"/>
                            </svg>
                        </a>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('web.llms') }}" target="_blank" class="ai-learn-link text-white-50 text-decoration-none small d-inline-flex align-items-center gap-2">
                            <span>✨</span>
                            <span>Hey AI, learn about us</span>
                            <span class="ai-learn-arrow">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-top pt-4 mt-4 footer-bottom-border">
            <p class="mb-0 text-white-50 small">&copy; {{ date('Y') }} Michigan Explorer. Designed for luxury travel. All Rights Reserved.</p>
        </div>
    </div>
    
    <!-- Back to top button -->
    <a href="#" aria-label="Back to top" class="btn btn-primary shadow d-flex align-items-center justify-content-center" id="backToTop" style="border-radius: 50rem; width: 50px; height: 50px;">
        <i class="fas fa-arrow-up fs-5"></i>
    </a>
</footer>

<style>
    .ai-tool-btn {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        color: #ffffff;
        transition: all 0.25s ease;
    }
    .ai-tool-btn:hover {
        background: #f59e0b !important;
        border-color: #f59e0b !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);
    }
    .hover-text-white:hover {
        color: #ffffff !important;
    }
    .ai-learn-link {
        font-size: 0.88rem;
        color: rgba(255, 255, 255, 0.65) !important;
        transition: color 0.2s ease;
    }
    .ai-learn-link:hover {
        color: #ffffff !important;
    }
    .ai-learn-arrow {
        transition: transform 0.2s ease;
        display: inline-block;
    }
    .ai-learn-link:hover .ai-learn-arrow {
        transform: translateX(4px);
    }
    .footer-newsletter .input-group {
        background: #ffffff !important;
        border: none !important;
    }
    .footer-newsletter .input-group .form-control {
        border: 0 !important;
        border-color: transparent !important;
        outline: none !important;
        box-shadow: none !important;
        background-color: transparent !important;
    }
    .footer-newsletter .input-group .form-control:focus {
        border: 0 !important;
        border-color: transparent !important;
        outline: none !important;
        box-shadow: none !important;
        background-color: transparent !important;
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