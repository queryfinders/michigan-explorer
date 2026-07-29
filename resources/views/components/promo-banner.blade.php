@props(['promotion'])

@if($promotion)
@php
    $bg = asset($promotion->desktop_image);
    $mobileBg = $promotion->mobile_image ? asset($promotion->mobile_image) : $bg;
    $ctaHref = route('affiliate.redirect', ['type' => 'promotion', 'id' => $promotion->id]);
@endphp
<section class="py-0 mt-4">
    <div class="container-fluid px-0">
        <div class="card border-0 rounded-0 text-white position-relative promo-banner-wrapper">
            <img src="{{ $bg }}" class="promo-bg-img d-none d-md-block" loading="lazy" alt="{{ $promotion->title }}">
            <img src="{{ $mobileBg }}" class="promo-bg-img d-block d-md-none" loading="lazy" alt="{{ $promotion->title }}">
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-primary"></div>
            <div class="container position-relative z-index-1 py-5">
                <div class="row">
                    <div class="col-lg-7">
                        <span class="badge bg-secondary mb-3 fs-6 px-3 py-2 rounded-pill">{{ $promotion->badge_text }}</span>
                        <h2 class="display-5 fw-bold mb-3 text-white font-heading">{{ $promotion->title }}</h2>
                        <p class="fs-5 mb-4 text-light">{{ $promotion->subtitle }}</p>
                        <a href="{{ $ctaHref }}" target="_blank" class="btn btn-secondary btn-lg rounded-pill px-5">
                            {{ $promotion->cta_text }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
