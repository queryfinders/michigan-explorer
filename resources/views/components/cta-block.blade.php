@props([
    'title' => 'Ready to book your stay?',
    'subtitle' => 'Book through our trusted travel partners for the best available rates.',
    'buttonText' => 'Find Best Rates',
    'buttonUrl' => '#'
])



<div class="cta-block-premium overflow-hidden position-relative my-5">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-cubes-pattern opacity-10"></div>
    
    <div class="container position-relative z-index-1">
        <h2 class="display-5 fw-bold mb-3 font-heading letter-spacing-tight">{{ $title }}</h2>
        <p class="fs-5 mb-5 opacity-75 mx-auto mw-700px lh-16">{{ $subtitle }}</p>
        
        <a href="{{ $buttonUrl }}" class="btn btn-cta-premium btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg" target="_blank">
            {{ $buttonText }} <i class="fas fa-external-link-alt ms-2"></i>
        </a>
    </div>
</div>
