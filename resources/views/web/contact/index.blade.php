@extends('web.layout.app_layout')

@php
    $pageTitle = ($page && $page->title) ? $page->title : 'Contact Us';
    $metaTitle = ($page && $page->seo && $page->seo->meta_title) ? $page->seo->meta_title : 'Contact Us - Michigan Explorer';
    $metaDescription = ($page && $page->seo && $page->seo->meta_description) 
        ? $page->seo->meta_description 
        : 'Get in touch with the Michigan Explorer team. We\'re here to help you plan your next great adventure!';
    $canonicalUrl = route('web.contact');
    
    $bannerImage = ($page && $page->featured_image) ? asset($page->featured_image) : asset('images/contact_hero_banner.jpg');
    $bannerTitle = ($page && $page->banner_title) ? $page->banner_title : $pageTitle;
    $bannerSubtitle = ($page && $page->banner_subtitle) ? $page->banner_subtitle : 'We\'re here to help you plan your next great adventure in Michigan.';
@endphp

@section('title', $metaTitle)

@section('meta_description')
<meta name="description" content="{{ $metaDescription }}">
@endsection

@section('og_tags')
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:type" content="website">
@endsection

@section('canonical')
<link rel="canonical" href="{{ $canonicalUrl }}">
@endsection

@section('webLayoutContent')
<!-- Hero Banner -->
<section class="hotel-listing-hero position-relative custom-contact-hero-banner" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.8)), url('{{ $bannerImage }}');">
    <div class="content d-flex flex-column justify-content-center align-items-center h-100 text-center w-100 position-relative custom-z-index-2">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb justify-content-center text-white opacity-75 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('web.home') }}" class="text-white text-decoration-none hover-text-primary transition-all">Home</a></li>
                <li class="breadcrumb-item active text-white fw-bold" aria-current="page">{{ $pageTitle }}</li>
            </ol>
        </nav>

        <h1 class="display-3 fw-bold text-white mb-3 custom-letter-spacing-minus-1">{{ $bannerTitle }}</h1>
        <p class="lead text-white opacity-75 mb-0 custom-max-w-600">{{ $bannerSubtitle }}</p>
    </div>
</section>

<!-- Contact Section -->
<section class="section-padding bg-light position-relative py-5">
    <div class="container py-4">
        <div class="row g-5">
            
            <!-- Left Side: Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white">
                    <div class="card-body p-4 p-lg-5">
                        <div class="mb-4">
                            <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-semibold mb-3">Get in Touch</span>
                            <h2 class="fw-bold mb-3">Send us a message</h2>
                            <p class="text-muted fs-6">Fill out the form below and our team will get back to you within 24 hours.</p>
                        </div>
                        
                        @if(session('success'))
                            <div class="alert alert-success border-0 bg-success-light text-success rounded-3 mb-5 p-4 d-flex align-items-center">
                                <i class="fa-solid fa-circle-check fs-4 me-3"></i> 
                                <div>
                                    <h5 class="alert-heading fw-bold mb-1">Message Sent!</h5>
                                    <p class="mb-0">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <form id="contactUsForm" action="{{ route('web.contact.submit') }}" method="POST" novalidate>
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="full_name" class="form-label fw-semibold text-dark">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="full_name" class="form-control form-control-lg bg-light border-0 px-4 py-3" id="full_name" placeholder="John Doe">
                                        <div class="error-feedback text-danger small mt-1" id="err-full_name"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label fw-semibold text-dark">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0 px-4 py-3" id="email" placeholder="name@example.com">
                                        <div class="error-feedback text-danger small mt-1" id="err-email"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label fw-semibold text-dark">Phone Number</label>
                                        <input type="text" name="phone" class="form-control form-control-lg bg-light border-0 px-4 py-3" id="phone" placeholder="(123) 456-7890">
                                        <div class="error-feedback text-danger small mt-1" id="err-phone"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subject" class="form-label fw-semibold text-dark">Subject</label>
                                        <input type="text" name="subject" class="form-control form-control-lg bg-light border-0 px-4 py-3" id="subject" placeholder="How can we help?">
                                        <div class="error-feedback text-danger small mt-1" id="err-subject"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="message" class="form-label fw-semibold text-dark">Your Message <span class="text-danger">*</span></label>
                                        <textarea name="message" class="form-control form-control-lg bg-light border-0 px-4 py-3 custom-textarea-fixed" id="message" placeholder="Tell us more about your inquiry..."></textarea>
                                        <div class="error-feedback text-danger small mt-1" id="err-message"></div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" data-callback="onCaptchaVerified"></div>
                                    <div class="error-feedback text-danger small mt-1" id="err-g-recaptcha-response"></div>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" id="contactSubmitBtn" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center transition-all w-100">
                                        <span>Send Message</span>
                                        <i class="fa-solid fa-paper-plane ms-2 text-white"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side: Contact Info -->
            <div class="col-lg-5">
                <div class="d-flex flex-column gap-4 h-100">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white hover-shadow transition-all flex-grow-1">
                        <div class="icon-box bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center mb-4 custom-icon-box-70">
                            <i class="fa-solid fa-location-dot fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Headquarters</h4>
                        <p class="text-muted mb-0">{!! nl2br(e($settings['contact_headquarters'] ?? "100 Traverse City\nMichigan, MI 49684\nUnited States")) !!}</p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white hover-shadow transition-all flex-grow-1">
                        <div class="icon-box bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center mb-4 custom-icon-box-70">
                            <i class="fa-solid fa-phone fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Phone</h4>
                        <p class="text-muted mb-2">Mon-Fri from 8am to 5pm.</p>
                        @php $phone = $settings['contact_phone'] ?? '+1 (800) 123-4567'; @endphp
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="text-dark fw-semibold text-decoration-none fs-5 hover-text-primary transition-all">{{ $phone }}</a>
                    </div>
                    
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white hover-shadow transition-all flex-grow-1">
                        <div class="icon-box bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center mb-4 custom-icon-box-70">
                            <i class="fa-solid fa-envelope fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Email</h4>
                        <p class="text-muted mb-2">Our friendly team is here to help.</p>
                        @php $email = $settings['contact_email'] ?? 'hello@michiganexplorer.com'; @endphp
                        <a href="mailto:{{ $email }}" class="text-dark fw-semibold text-decoration-none fs-5 hover-text-primary transition-all">{{ $email }}</a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="position-relative contact-map-wrapper">
    @php $mapUrl = $settings['contact_map_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d182236.42571253018!2d-85.74830113876077!3d44.75056708688463!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x881e32b2d7da29dd%3A0x47bfdc3f3f745621!2sTraverse%20City%2C%20MI!5e0!3m2!1sen!2sus!4v1716301389278!5m2!1sen!2sus'; @endphp
    @if(str_starts_with(trim($mapUrl), '<iframe'))
        {!! $mapUrl !!}
    @else
        <iframe src="{{ $mapUrl }}" width="100%" height="450" class="custom-iframe-block" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    @endif
</section>

<!-- Additional Style for hover effects -->

@endsection

@section('webLayoutScript')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
// Global callback function invoked by Google when captcha is solved
function onCaptchaVerified() {
    const errEl = document.getElementById('err-g-recaptcha-response');
    if (errEl) {
        errEl.textContent = '';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const contactForm = document.getElementById('contactUsForm');
    if (!contactForm) return;

    // Automatically hide validation error messages when user types or updates data
    contactForm.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('input', function() {
            const fieldName = this.getAttribute('name');
            if (fieldName) {
                let errEl = document.getElementById('err-' + fieldName);
                if (!errEl) {
                    errEl = document.getElementById('err-' + fieldName.replace(/_/g, '-'));
                }
                if (errEl) {
                    errEl.textContent = '';
                }
            }
        });
    });

    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = document.getElementById('contactSubmitBtn');
        const formData = new FormData(form);
        const csrfToken = form.querySelector('input[name="_token"]').value;

        // Clear all previous errors
        document.querySelectorAll('.error-feedback').forEach(el => el.textContent = '');



        // Disable submit button
        const originalBtnHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sending...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => {
            if (response.status === 429) {
                throw new Error('Too many requests. Maximum 5 contact submissions per hour.');
            }
            return response.json().then(data => {
                if (!response.ok) {
                    return Promise.reject({ status: response.status, data: data });
                }
                return data;
            });
        })
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;

            Swal.fire({
                icon: 'success',
                title: 'Thank You!',
                html: 'Your message has been received successfully.<br>Our team will contact you within 24 hours.',
                confirmButtonColor: '#7367f0'
            });
            form.reset();
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.reset();
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.reset();
            }
            
            if (err.status === 422 && err.data && err.data.errors) {
                console.log("Validation Errors Received:", err.data.errors);
                // Populate specific validation errors below each input field
                for (const [field, messages] of Object.entries(err.data.errors)) {
                    let errEl = document.getElementById('err-' + field);
                    if (!errEl) {
                        // Support keys with dashes converted to underscores by Laravel
                        errEl = document.getElementById('err-' + field.replace(/_/g, '-'));
                    }
                    if (errEl) {
                        console.log("Setting error for field: " + field + " to: " + messages[0]);
                        errEl.textContent = messages[0];
                    } else {
                        console.warn("No error element found for field: " + field);
                    }
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.message || (err.data ? err.data.message : 'An error occurred. Please try again.'),
                    confirmButtonColor: '#7367f0'
                });
            }
        });
    });
});
</script>
@endsection
