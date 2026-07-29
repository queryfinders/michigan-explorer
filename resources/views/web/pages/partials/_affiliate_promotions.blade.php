<!-- 7. Affiliate Promotions -->
@php
    $promo = $homepagePromotion ?? null;
    $promoBg = $promo
        ? asset($promo->desktop_image)
        : asset('images/promo_banner_1783508311655.jpg');
    $promoMobileBg = $promo && $promo->mobile_image
        ? asset($promo->mobile_image)
        : $promoBg;
    $promoBadge    = $promo ? $promo->badge_text : 'Special Promotion';
    $promoTitle    = $promo ? $promo->title      : 'Save 20% on Romantic Lakefront Escapes';
    $promoSubtitle = $promo ? $promo->subtitle   : 'Book your next getaway through our exclusive affiliate partners and enjoy premium upgrades.';
    $promoCtaText  = $promo ? $promo->cta_text   : 'Claim Offer';
    $promoCtaHref  = $promo
        ? route('affiliate.redirect', ['type' => 'promotion', 'id' => $promo->id])
        : '#';
@endphp
<section class="py-0">
    <div class="container-fluid px-0">
        <div class="card border-0 rounded-0 text-white position-relative promo-banner-wrapper">
            {{-- Desktop image --}}
            <img src="{{ $promoBg }}" class="promo-bg-img d-none d-md-block" loading="lazy" alt="{{ $promoTitle }}">
            {{-- Mobile image (portrait) --}}
            <img src="{{ $promoMobileBg }}" class="promo-bg-img d-block d-md-none" loading="lazy" alt="{{ $promoTitle }}">
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-primary"></div>
            <div class="container position-relative z-index-1">
                <div class="row">
                    <div class="col-lg-6">
                        <span class="badge bg-secondary mb-3 fs-6 px-3 py-2 rounded-pill">{{ $promoBadge }}</span>
                        <h2 class="display-4 fw-bold mb-4 text-white font-heading">{{ $promoTitle }}</h2>
                        <p class="fs-4 mb-5 text-light">{{ $promoSubtitle }}</p>
                        <a href="{{ $promoCtaHref }}" class="btn btn-secondary btn-lg rounded-pill px-5" @if($promo) target="_blank" @endif>{{ $promoCtaText }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
