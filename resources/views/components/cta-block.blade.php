@props([
    'title' => 'Ready to book your stay?',
    'subtitle' => 'Book through our trusted travel partners for the best available rates.',
    'buttonText' => 'Find Best Rates',
    'buttonUrl' => '#'
])

<style>
.cta-block-premium {
    background: linear-gradient(135deg, var(--primary-color) 0%, rgba(13, 110, 253, 0.8) 100%);
    color: var(--white);
    padding: 100px 20px;
    text-align: center;
    border-radius: 24px;
    box-shadow: 0 30px 60px rgba(13, 110, 253, 0.15);
}
.btn-cta-premium {
    background: #F5A623;
    border: none;
    color: white !important;
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.btn-cta-premium:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(245, 166, 35, 0.3) !important;
    background: #e0961b;
}
</style>

<div class="cta-block-premium overflow-hidden position-relative my-5">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://www.transparenttextures.com/patterns/cubes.png') repeat; opacity: 0.1;"></div>
    
    <div class="container position-relative z-index-1">
        <h2 class="display-5 fw-bold mb-3" style="font-family: var(--font-heading); letter-spacing: -0.5px;">{{ $title }}</h2>
        <p class="fs-5 mb-5 opacity-75 mx-auto" style="max-width: 700px; line-height: 1.6;">{{ $subtitle }}</p>
        
        <a href="{{ $buttonUrl }}" class="btn btn-cta-premium btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg" target="_blank">
            {{ $buttonText }} <i class="fas fa-external-link-alt ms-2"></i>
        </a>
    </div>
</div>
