@extends('web.layout.app_layout')

@php
    $pageTitle = ($page && $page->title) ? $page->title : 'Contact Us';
    $metaTitle = ($page && $page->seo && $page->seo->meta_title) ? $page->seo->meta_title : 'Contact Us - Michigan Explorer';
    $metaDescription = ($page && $page->seo && $page->seo->meta_description) 
        ? $page->seo->meta_description 
        : 'Get in touch with the Michigan Explorer team. We\'re here to help you plan your next great adventure!';
    $canonicalUrl = route('web.contact');
    
    $bannerImage = ($page && $page->featured_image) ? asset($page->featured_image) : asset('images/contact_hero_banner.png');
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
<section class="hotel-listing-hero position-relative" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.8)), url('{{ $bannerImage }}'); padding: 120px 0; background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="content d-flex flex-column justify-content-center align-items-center h-100 text-center w-100 position-relative" style="z-index: 2;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb justify-content-center text-white opacity-75 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('web.home') }}" class="text-white text-decoration-none hover-text-primary transition-all">Home</a></li>
                <li class="breadcrumb-item active text-white fw-bold" aria-current="page">{{ $pageTitle }}</li>
            </ol>
        </nav>

        <h1 class="display-3 fw-bold text-white mb-3" style="letter-spacing: -1px;">{{ $bannerTitle }}</h1>
        <p class="lead text-white opacity-75 mb-0" style="max-width: 600px;">{{ $bannerSubtitle }}</p>
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
                        
                        <form action="{{ route('web.contact.submit') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label fw-semibold text-dark">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control form-control-lg bg-light border-0 px-4 py-3" id="name" placeholder="John Doe" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label fw-semibold text-dark">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0 px-4 py-3" id="email" placeholder="name@example.com" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label fw-semibold text-dark">Phone Number</label>
                                        <input type="text" name="phone" class="form-control form-control-lg bg-light border-0 px-4 py-3" id="phone" placeholder="(123) 456-7890">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subject" class="form-label fw-semibold text-dark">Subject</label>
                                        <input type="text" name="subject" class="form-control form-control-lg bg-light border-0 px-4 py-3" id="subject" placeholder="How can we help?">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="message" class="form-label fw-semibold text-dark">Your Message <span class="text-danger">*</span></label>
                                        <textarea name="message" class="form-control form-control-lg bg-light border-0 px-4 py-3" id="message" style="height: 150px; resize: none;" placeholder="Tell us more about your inquiry..." required></textarea>
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center transition-all w-100">
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
                        <div class="icon-box bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-location-dot fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Headquarters</h4>
                        <p class="text-muted mb-0">100 Traverse City<br>Michigan, MI 49684<br>United States</p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white hover-shadow transition-all flex-grow-1">
                        <div class="icon-box bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-phone fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Phone</h4>
                        <p class="text-muted mb-2">Mon-Fri from 8am to 5pm.</p>
                        <a href="tel:+18001234567" class="text-dark fw-semibold text-decoration-none fs-5 hover-text-primary transition-all">+1 (800) 123-4567</a>
                    </div>
                    
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white hover-shadow transition-all flex-grow-1">
                        <div class="icon-box bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-envelope fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Email</h4>
                        <p class="text-muted mb-2">Our friendly team is here to help.</p>
                        <a href="mailto:hello@michiganexplorer.com" class="text-dark fw-semibold text-decoration-none fs-5 hover-text-primary transition-all">hello@michiganexplorer.com</a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="position-relative">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d182236.42571253018!2d-85.74830113876077!3d44.75056708688463!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x881e32b2d7da29dd%3A0x47bfdc3f3f745621!2sTraverse%20City%2C%20MI!5e0!3m2!1sen!2sus!4v1716301389278!5m2!1sen!2sus" width="100%" height="450" style="border:0; display:block;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>

<!-- Additional Style for hover effects -->
<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
    .hover-text-primary:hover {
        color: var(--bs-primary) !important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .form-control:focus {
        background-color: #fff !important;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
    }
    .icon-box i {
        color: var(--bs-primary) !important;
    }
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
</style>
@endsection
