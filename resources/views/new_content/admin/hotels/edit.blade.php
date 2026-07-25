@extends('layouts/layoutMaster')

@section('title', 'Edit Hotel')

@php
    $popularIcons = [
        'fas fa-wifi', 'fas fa-parking', 'fas fa-swimming-pool', 'fas fa-dumbbell', 
        'fas fa-spa', 'fas fa-coffee', 'fas fa-utensils', 'fas fa-cocktail', 
        'fas fa-concierge-bell', 'fas fa-paw', 'fas fa-tv', 'fas fa-snowflake', 
        'fas fa-hot-tub', 'fas fa-wheelchair', 'fas fa-baby', 'fas fa-key', 
        'fas fa-shield-alt', 'fas fa-tree', 'fas fa-star', 'fas fa-bed', 
        'fas fa-clock', 'fas fa-wine-glass', 'fas fa-luggage-cart', 'fas fa-bicycle',
        'fas fa-hiking', 'fas fa-campground', 'fas fa-map-marker-alt', 'fas fa-map-pin',
        'fas fa-wind', 'fas fa-tshirt', 'fas fa-sink', 'fas fa-glass-martini-alt',
    ];
    $popularBfIcons = [
        'fas fa-check', 'fas fa-check-circle', 'fas fa-times-circle', 'fas fa-dollar-sign',
        'fas fa-credit-card', 'fas fa-calendar-alt', 'fas fa-calendar-check', 'fas fa-clock',
        'fas fa-bolt', 'fas fa-coffee', 'fas fa-utensils', 'fas fa-ban',
        'fas fa-shield-alt', 'fas fa-percent', 'fas fa-tags', 'fas fa-gift',
        'fas fa-mobile-alt', 'fas fa-envelope', 'fas fa-info-circle', 'fas fa-star',
        'fas fa-wallet', 'fas fa-receipt', 'fas fa-file-invoice-dollar', 'fas fa-user-check'
    ];
@endphp
<style>
    .icon-option:hover {
        background-color: #f8f9fa;
        border-color: #4f46e5 !important;
    }
    .icon-picker-grid {
        scrollbar-width: thin;
    }
</style>

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hotels.index') }}">Hotels</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Hotel</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Hotel</h5>
  </div>
  <div class="card-body">
    <form id="hotelEditForm" action="{{ route('hotels.update', $hotel->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- Tabs Navigation -->
      <ul class="nav nav-tabs mb-4" id="hotelFormTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic-pane" type="button" role="tab" aria-controls="basic-pane" aria-selected="true">Basic Info</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-pane" type="button" role="tab" aria-controls="details-pane" aria-selected="false">Details & Pricing</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="featured-tab" data-bs-toggle="tab" data-bs-target="#featured-pane" type="button" role="tab" aria-controls="featured-pane" aria-selected="false">Featured Image</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery-pane" type="button" role="tab" aria-controls="gallery-pane" aria-selected="false">Gallery</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="faqs-tab" data-bs-toggle="tab" data-bs-target="#faqs-pane" type="button" role="tab" aria-controls="faqs-pane" aria-selected="false">FAQs</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button" role="tab" aria-controls="seo-pane" aria-selected="false">SEO & Schema</button>
        </li>
      </ul>

      <!-- Tabs Content -->
      <div class="tab-content p-0" id="hotelFormTabsContent">
        
        <!-- Tab 1: Basic Info -->
        <div class="tab-pane fade show active" id="basic-pane" role="tabpanel" aria-labelledby="basic-tab">
          <div class="row">
            <div class="col-md-4 mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label fw-semibold mb-0" for="hotel_category_id">Category <span class="text-danger">*</span></label>
                <button type="button" class="btn btn-sm btn-link p-0 text-primary fw-semibold"
                        data-bs-toggle="modal" data-bs-target="#addCategoryModal" style="text-decoration: none; font-size: 0.85rem;">
                  <i class="fas fa-plus-circle me-1"></i>Add Category
                </button>
              </div>
              {{-- Hidden input that holds the selected category id --}}
              <input type="hidden" name="hotel_category_id" id="hotel_category_id"
                     value="{{ old('hotel_category_id', $hotel->hotel_category_id) }}" required />
              @error('hotel_category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

              <div class="cuisine-dropdown-wrapper" id="categoryDropdownWrapper">
                <div class="cuisine-dropdown-trigger" id="categoryTrigger" onclick="toggleCategoryDropdown()">
                  <div class="cuisine-tags-area" id="categoryTagsArea">
                    <span class="cuisine-placeholder" id="categoryPlaceholder">
                      <i class="fas fa-layer-group me-2 text-muted"></i>Click to select category...
                    </span>
                  </div>
                  <i class="fas fa-chevron-down cuisine-dropdown-arrow" id="categoryArrow"></i>
                </div>
                <div class="cuisine-dropdown-panel" id="categoryDropdownPanel" style="display:none;">
                  <div class="cuisine-search-wrap">
                    <i class="fas fa-search cuisine-search-icon"></i>
                    <input type="text" class="cuisine-search-input" id="categorySearchInput"
                           placeholder="Search categories..." oninput="filterCategories(this.value)" autocomplete="off" />
                  </div>
                  <div class="cuisine-divider"></div>
                  <div class="cuisine-items-list" id="categoryItemsList">
                    @foreach($categories as $cat)
                    <label class="cuisine-item" id="cat-label-{{ $cat->id }}">
                      <input type="radio" name="_cat_radio" value="{{ $cat->id }}"
                             id="cat_rb_{{ $cat->id }}"
                             class="cat-rb d-none"
                             data-name="{{ $cat->name }}"
                             data-id="{{ $cat->id }}"
                             {{ old('hotel_category_id', $hotel->hotel_category_id) == $cat->id ? 'checked' : '' }}
                             onchange="onCategoryChange(this)" />
                      <span class="cuisine-item-name">{{ $cat->name }}</span>
                      <span class="cuisine-item-check"><i class="fas fa-check"></i></span>
                    </label>
                    @endforeach
                    <div class="cuisine-no-results d-none" id="categoryNoResults">
                      <i class="fas fa-search-minus me-2"></i>No categories found
                    </div>
                  </div>
                  <div class="cuisine-divider"></div>
                  <div class="cuisine-panel-footer">
                    <button type="button" class="btn btn-sm btn-link p-0 text-primary fw-semibold"
                            data-bs-toggle="modal" data-bs-target="#addCategoryModal" onclick="closeCategoryDropdown()">
                      <i class="fas fa-plus-circle me-1"></i>Add New Category
                    </button>
                  </div>
                </div>
              </div>
              <div class="text-danger small mt-1 d-none" id="category-error-msg">The category field is required.</div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') ?? $hotel->name }}"  placeholder="e.g. The Grand Hotel" />
              <div class="invalid-feedback">The name field is required.</div>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') ?? $hotel->slug }}"    placeholder="e.g. the-grand-hotel" />
              <div class="invalid-feedback">The slug field is required.</div>
              @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
          <!-- <div class="mb-3">
            <label class="form-label" for="short_description">Short Description</label>
            <textarea class="form-control" id="short_description" name="short_description" rows="2">{{ $hotel->short_description }}</textarea>
          </div> -->
          <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea class="form-control tinymce" id="description" name="description" rows="6">{{ $hotel->description }}</textarea>
          </div>

          <div class="mb-3">
            <div class="form-check form-switch mt-2">
              <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ $hotel->is_featured == 1 ? 'checked' : '' }}>
              <label class="form-check-label fw-semibold" for="is_featured">Featured Hotel (Shows on Home Page)</label>
            </div>
          </div>
        </div>

        <!-- Tab 2: Details & Pricing -->
        <div class="tab-pane fade" id="details-pane" role="tabpanel" aria-labelledby="details-tab">
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label" for="city">City</label>
              <select class="form-select select2" id="city" name="city">
                <option value="">Select a city</option>
                @foreach(config('michigan_cities') as $m_city)
                  <option value="{{ $m_city }}" {{ old('city', $hotel->city) == $m_city ? 'selected' : '' }}>{{ $m_city }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="zip">Zip Code</label>
              <input type="text" class="form-control" id="zip" name="zip" value="{{ $hotel->zip }}" placeholder="e.g. 49757" />
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="address">Street Address</label>
              <input type="text" class="form-control" id="address" name="address" value="{{ $hotel->address }}" placeholder="e.g. 286 Grand Avenue" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label" for="phone">Phone Number</label>
              <input type="text" class="form-control" id="phone" name="phone" value="{{ $hotel->phone }}" placeholder="e.g. +1 555-123-4567" />
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ $hotel->email }}" placeholder="e.g. info@example.com" />
            </div>
            <!-- <div class="col-md-4 mb-3">
              <label class="form-label" for="website">Website URL</label>
              <input type="url" class="form-control" id="website" name="website" value="{{ $hotel->website }}" placeholder="e.g. https://www.example.com" />
            </div> -->
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="starting_price">Starting Price ($)</label>
              <input type="number" class="form-control" id="starting_price" name="starting_price" value="{{ $hotel->starting_price }}" placeholder="e.g. 199" />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label" for="affiliate_url">Booking Affiliate URL</label>
              <input type="url" class="form-control" id="affiliate_url" name="affiliate_url" value="{{ $hotel->affiliate_url }}" placeholder="e.g. https://booking.com/..." />
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-3">
              <label class="form-label" for="map_iframe">Google Maps Embed Code (Iframe Link)</label>
              <textarea class="form-control" id="map_iframe" name="map_iframe" rows="1" placeholder="Paste the <iframe src='...'></iframe> embed code here">{{ old('map_iframe', $hotel->map_iframe) }}</textarea>
            </div>
          </div>
          <div class="mb-3 mt-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label fw-semibold mb-0">Amenities</label>
              <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAmenityModal">
                <i class="fas fa-plus me-1"></i> Add Amenity
              </button>
            </div>

            {{-- Custom Amenity Dropdown --}}
            <div class="amenity-dropdown-wrapper" id="amenityDropdownWrapper">

              {{-- Trigger Button --}}
              <div class="amenity-dropdown-trigger" id="amenityTrigger" onclick="toggleAmenityDropdown()">
                <div class="amenity-tags-area" id="amenityTagsArea">
                  <span class="amenity-placeholder" id="amenityPlaceholder">
                    <i class="fas fa-concierge-bell me-2 text-muted"></i>Click to select amenities...
                  </span>
                </div>
                <i class="fas fa-chevron-down amenity-dropdown-arrow" id="amenityArrow"></i>
              </div>

              {{-- Dropdown Panel --}}
              <div class="amenity-dropdown-panel" id="amenityDropdownPanel" style="display:none;">
                {{-- Search --}}
                <div class="amenity-search-wrap">
                  <i class="fas fa-search amenity-search-icon"></i>
                  <input type="text" class="amenity-search-input" id="amenitySearchInput"
                         placeholder="Search amenities..." oninput="filterAmenities(this.value)" autocomplete="off" />
                </div>
                <div class="amenity-divider"></div>
                {{-- Items List --}}
                <div class="amenity-items-list" id="amenityItemsList">
                  @foreach($amenities as $amenity)
                  @php $checked = $hotel->amenities->contains($amenity->id); @endphp
                  <label class="amenity-item {{ $checked ? 'amenity-item--checked' : '' }}" id="amenity-label-{{ $amenity->id }}">
                    <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                           id="amenity_cb_{{ $amenity->id }}"
                           class="amenity-cb"
                           data-name="{{ $amenity->name }}"
                           data-id="{{ $amenity->id }}"
                           {{ $checked ? 'checked' : '' }}
                           onchange="onAmenityChange(this)" />
                    <span class="amenity-item-icon"><i class="fas {{ $amenity->icon ?? 'fa-star' }}"></i></span>
                    <span class="amenity-item-name">{{ $amenity->name }}</span>
                    <span class="amenity-item-check"><i class="fas fa-check"></i></span>
                  </label>
                  @endforeach
                  <div class="amenity-no-results d-none" id="amenityNoResults">
                    <i class="fas fa-search-minus me-2"></i>No amenities found
                  </div>
                </div>
                <div class="amenity-divider"></div>
                {{-- Footer --}}
                <div class="amenity-panel-footer">
                  <button type="button" class="btn btn-sm btn-link p-0 text-primary fw-semibold"
                          data-bs-toggle="modal" data-bs-target="#addAmenityModal" onclick="closeAmenityDropdown()">
                    <i class="fas fa-plus-circle me-1"></i>Add New Amenity
                  </button>
                  <span class="amenity-selected-count" id="amenitySelectedCount">0 selected</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Booking Features -->
          <div class="row">
            <div class="col-12 mt-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label fw-semibold mb-0">Booking Features</label>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addBookingFeatureModal">
                  <i class="fas fa-plus me-1"></i> Add Booking Feature
                </button>
              </div>

              {{-- Custom Booking Feature Dropdown --}}
              <div class="amenity-dropdown-wrapper" id="bookingFeatureDropdownWrapper">
                @php
                  $hotelBookingFeatureIds = $hotel->bookingFeatures->pluck('id')->toArray();
                @endphp
                {{-- Trigger Button --}}
                <div class="amenity-dropdown-trigger" id="bookingFeatureTrigger" onclick="toggleBookingFeatureDropdown()">
                  <div class="amenity-tags-area" id="bookingFeatureTagsArea">
                    <span class="amenity-placeholder" id="bookingFeaturePlaceholder" {!! count($hotelBookingFeatureIds) > 0 ? 'style="display:none;"' : '' !!}>
                      <i class="fas fa-check-circle me-2 text-muted"></i>Click to select booking features...
                    </span>
                    @foreach($bookingFeatures as $feature)
                      @if(in_array($feature->id, $hotelBookingFeatureIds))
                        <span class="amenity-selected-tag" data-id="{{ $feature->id }}">
                          @if($feature->icon)
                            <i class="fas {{ $feature->icon }}"></i>
                          @endif
                          {{ $feature->name }}
                        </span>
                      @endif
                    @endforeach
                  </div>
                  <i class="fas fa-chevron-down amenity-dropdown-arrow" id="bookingFeatureArrow"></i>
                </div>

                {{-- Dropdown Panel --}}
                <div class="amenity-dropdown-panel" id="bookingFeatureDropdownPanel" style="display:none;">
                  <div class="amenity-search-wrap">
                    <i class="fas fa-search amenity-search-icon"></i>
                    <input type="text" class="amenity-search-input" id="bookingFeatureSearchInput"
                           placeholder="Search booking features..." oninput="filterBookingFeatures(this.value)" autocomplete="off" />
                  </div>
                  <div class="amenity-divider"></div>
                  <div class="amenity-items-list" id="bookingFeatureItemsList">
                    @foreach($bookingFeatures as $feature)
                    @php
                      $bfChecked = in_array($feature->id, $hotelBookingFeatureIds);
                    @endphp
                    <label class="amenity-item {{ $bfChecked ? 'selected' : '' }}" id="booking-feature-label-{{ $feature->id }}">
                      <input type="checkbox" name="booking_features[]" value="{{ $feature->id }}"
                             id="booking_feature_cb_{{ $feature->id }}"
                             class="booking-feature-cb"
                             data-name="{{ $feature->name }}"
                             data-id="{{ $feature->id }}"
                             onchange="onBookingFeatureChange(this)"
                             {{ $bfChecked ? 'checked' : '' }} />
                      @if($feature->icon)
                      <span class="amenity-item-icon"><i class="fas {{ $feature->icon }}"></i></span>
                      @endif
                      <span class="amenity-item-name">{{ $feature->name }}</span>
                      <span class="amenity-item-check"><i class="fas fa-check"></i></span>
                    </label>
                    @endforeach
                    <div class="amenity-no-results d-none" id="bookingFeatureNoResults">
                      <i class="fas fa-search-minus me-2"></i>No booking features found
                    </div>
                  </div>
                  <div class="amenity-divider"></div>
                  <div class="amenity-panel-footer">
                    <button type="button" class="btn btn-sm btn-link p-0 text-primary fw-semibold"
                            data-bs-toggle="modal" data-bs-target="#addBookingFeatureModal" onclick="closeBookingFeatureDropdown()">
                      <i class="fas fa-plus-circle me-1"></i>Add New Booking Feature
                    </button>
                    <span class="amenity-selected-count" id="bookingFeatureSelectedCount">{{ count($hotelBookingFeatureIds) }} selected</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Hotel Policies -->
          <div class="row">
            <div class="col-12 mt-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label fw-semibold mb-0">Hotel Policies</label>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addHotelPolicyModal">
                  <i class="fas fa-plus me-1"></i> Add Hotel Policy
                </button>
              </div>
              <div class="row g-3">
                @php
                  $policyValues = $hotel->policyValues->keyBy('hotel_policy_id');
                @endphp
                @foreach($hotelPolicies as $policy)
                @php
                  $val = isset($policyValues[$policy->id]) ? $policyValues[$policy->id]->value : '';
                @endphp
                <div class="col-md-6">
                  <label class="form-label" for="policy_{{ $policy->id }}">{{ $policy->name }}</label>
                  @if($policy->input_type === 'textarea')
                    <textarea class="form-control" name="hotel_policies[{{ $policy->id }}]" id="policy_{{ $policy->id }}" rows="2">{{ $val }}</textarea>
                  @else
                    <input type="text" class="form-control" name="hotel_policies[{{ $policy->id }}]" id="policy_{{ $policy->id }}" value="{{ $val }}" />
                  @endif
                </div>
                @endforeach
              </div>
            </div>
          </div>

        </div>

        <!-- Tab 3: Featured Image & Video -->
        <div class="tab-pane fade" id="featured-pane" role="tabpanel" aria-labelledby="featured-tab">
          <div class="mb-3">
            <label class="form-label fw-semibold" for="featured_image_file">Featured Image</label>
            <input type="file" class="form-control" id="featured_image_file" name="featured_image_file" accept="image/*" onchange="previewFeaturedImage(event)" />
            <div class="form-text">Recommended: 1200×800px, JPG/PNG/WebP, max 2MB. This is the main image shown in listings.</div>
          </div>
          @if($hotel->featured_image)
            <div class="mb-3" id="featured-preview-existing">
              <label class="form-label text-muted small">Current Featured Image</label>
              <div>
                <img src="{{ asset($hotel->featured_image) }}" alt="{{ $hotel->featured_image_alt ?? 'Featured Image' }}" class="img-thumbnail" style="max-height:220px;" />
              </div>
            </div>
          @endif
          <div class="mb-3" id="featured-preview-new" style="display:none;">
            <label class="form-label text-muted small">New Image Preview</label>
            <img id="featured-preview-img" src="" alt="Preview" class="img-thumbnail" style="max-height:220px;" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="featured_image_alt">Image Alt Text (SEO)</label>
            <input type="text" class="form-control" id="featured_image_alt" name="featured_image_alt" value="{{ $hotel->featured_image_alt }}" />
            <div class="form-text">Describe the image clearly for search engines and accessibility.</div>
          </div>
          <!-- <div class="mb-3 border-top pt-3">
            <label class="form-label fw-semibold" for="video_file">Promo Video</label>
            <input type="file" class="form-control" id="video_file" name="video_file" accept="video/mp4,video/x-m4v,video/*" />
            <div class="form-text">Supported: MP4, MOV, WebM. Max 30MB. This video will play on the hotel's detail page.</div>
            @if($hotel->video && !str_starts_with($hotel->video, 'http'))
              <div class="mt-2">
                <span class="text-success small"><i class="fas fa-video me-1"></i> Current video uploaded: </span>
                <a href="{{ asset($hotel->video) }}" target="_blank" class="small fw-bold">{{ basename($hotel->video) }}</a>
              </div>
            @endif
          </div> -->
          <div class="mb-3">
            <label class="form-label fw-semibold" for="video_url">Video URL</label>
            <input type="url" class="form-control" id="video_url" name="video_url" value="{{ str_starts_with($hotel->video ?? '', 'http') ? $hotel->video : '' }}" />
            <div class="form-text">Paste a YouTube link directly instead of uploading a video file.</div>
          </div>
        </div>

        <!-- Tab 4: Gallery -->
        <div class="tab-pane fade" id="gallery-pane" role="tabpanel" aria-labelledby="gallery-tab">
          
          <!-- Existing Gallery Images -->
          @if($hotel->images->count() > 0 || !empty($hotel->video))
          <div class="mb-4">
            <label class="form-label fw-semibold">Current Gallery & Video</label>
            <div class="row g-3" id="existing-gallery-grid">
              @if(!empty($hotel->video))
              <div class="col-md-3 col-sm-4 col-6" id="gallery-video-container">
                <div class="card border position-relative bg-light">
                  <div class="card-img-top d-flex align-items-center justify-content-center bg-dark text-white position-relative" style="height:140px;overflow:hidden;">
                    @php
                      $isYoutube = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $hotel->video, $matches);
                      $youtubeId = $isYoutube ? $matches[1] : null;
                    @endphp
                    @if($isYoutube)
                      <img src="https://img.youtube.com/vi/{{ $youtubeId }}/mqdefault.jpg" class="w-100 h-100 object-fit-cover" style="object-fit: cover; height: 140px;" alt="YouTube Thumbnail" />
                    @else
                      <video class="w-100 h-100 object-fit-cover" muted style="object-fit: cover;">
                        <source src="{{ asset($hotel->video) }}" type="video/mp4">
                      </video>
                    @endif
                    <div class="position-absolute d-flex align-items-center justify-content-center text-white bg-dark bg-opacity-50 rounded-circle" style="width: 40px; height: 40px; pointer-events: none; top:50%; left:50%; transform:translate(-50%, -50%);">
                      <i class="fas fa-play"></i>
                    </div>
                  </div>
                  <div class="card-body p-2 text-center">
                    <small class="fw-bold text-success"><i class="fas fa-video me-1"></i> Hotel Video</small>
                  </div>
                  <div class="position-absolute top-0 end-0 m-1">
                    <input type="checkbox" name="delete_video" value="1" id="del_video" class="form-check-input gallery-delete-cb" onchange="toggleVideoDeleteOverlay(this)" />
                    <label for="del_video" class="btn btn-danger btn-sm py-0 px-1" title="Mark for deletion">
                      <i class="fa fa-trash"></i>
                    </label>
                  </div>
                  <div id="overlay-video" class="position-absolute top-0 start-0 w-100 h-100 bg-danger bg-opacity-50 d-none align-items-center justify-content-center rounded">
                    <span class="text-white fw-bold small">Will be deleted</span>
                  </div>
                </div>
              </div>
              @endif
              @foreach($hotel->images as $img)
              <div class="col-md-3 col-sm-4 col-6" id="gallery-item-{{ $img->id }}">
                <div class="card border position-relative">
                  <img src="{{ asset($img->image) }}" alt="{{ $img->alt_text ?? 'Gallery' }}" class="card-img-top" style="height:140px;object-fit:cover;" />
                  <div class="card-body p-2">
                    <label class="form-label small text-muted mb-1" style="font-size:0.75rem;"><i class="fas fa-tag me-1"></i>Alt Text (SEO)</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           name="existing_gallery_alts[{{ $img->id }}]"
                           value="{{ $img->alt_text }}"
                           placeholder="e.g. Hotel lobby view..." />
                  </div>
                  <div class="position-absolute top-0 end-0 m-1">
                    <input type="checkbox" name="delete_gallery_ids[]" value="{{ $img->id }}" id="del_img_{{ $img->id }}" class="form-check-input gallery-delete-cb" onchange="toggleDeleteOverlay(this, {{ $img->id }})" />
                    <label for="del_img_{{ $img->id }}" class="btn btn-danger btn-sm py-0 px-1" title="Mark for deletion">
                      <i class="fa fa-trash"></i>
                    </label>
                  </div>
                  <div id="overlay-{{ $img->id }}" class="position-absolute top-0 start-0 w-100 h-100 bg-danger bg-opacity-50 d-none align-items-center justify-content-center rounded">
                    <span class="text-white fw-bold small">Will be deleted</span>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
            <div class="form-text mt-2 text-danger">Check the trash icon on images to mark them for deletion. They will be removed when you click Update.</div>
          </div>
          @endif

          <!-- Upload New Gallery Images -->
          <div class="mb-3">
            <label class="form-label fw-semibold" for="gallery_images">Add New Gallery Images</label>
            <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" accept="image/*" multiple onchange="previewGalleryImages(event)" />
            <div class="form-text">Select multiple images at once. Max 4MB each. JPG/PNG/WebP supported.</div>
          </div>

          <!-- New Images Preview -->
          <div id="gallery-new-preview" class="row g-3 mt-2"></div>

          <!-- Alt text fields injected via JS -->
          <div id="gallery-alt-fields"></div>
        </div>

        <!-- Tab 5: SEO & Schema -->
        <div class="tab-pane fade" id="seo-pane" role="tabpanel" aria-labelledby="seo-tab">
          <div class="mb-3">
            <label class="form-label" for="meta_title">Meta Title</label>
            <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="e.g. Best Hotel in Michigan | Michigan Explorer" value="{{ $hotel->seo->meta_title ?? '' }}" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="meta_description">Meta Description <span class="text-muted small ms-2 fw-normal" id="meta_desc_count">(0 / 160)</span></label>
            <textarea class="form-control" id="meta_description" name="meta_description" placeholder="e.g. Discover the best hotel in Michigan..." maxlength="160" rows="2">{{ $hotel->seo->meta_description ?? '' }}</textarea>
          </div>
          <div class="mb-3">
              <label class="form-label" for="og_title">OG Title</label>
              <input type="text" class="form-control" id="og_title" name="og_title" placeholder="e.g. Best Hotel in Michigan" value="{{ $hotel->seo->og_title ?? '' }}" />
            </div>
          <div class="mb-3">
            <label class="form-label" for="og_description">OG Description <span class="text-muted small ms-2 fw-normal" id="og_desc_count">(0 / 160)</span></label>
            <textarea class="form-control" id="og_description" name="og_description" placeholder="e.g. Discover the best hotel in Michigan..." maxlength="160" rows="2">{{ $hotel->seo->og_description ?? '' }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label" for="schema_markup">Schema Markup (JSON-LD) - <span class="text-info">Auto-generated</span></label>
            <textarea class="form-control" id="schema_markup" name="schema_markup" rows="8" placeholder="Auto-generated on save" readonly disabled>{{ $hotel->seo->schema_markup ?? '' }}</textarea>
          </div>
        </div>

        <!-- Tab 6: FAQs -->
        <div class="tab-pane fade" id="faqs-pane" role="tabpanel" aria-labelledby="faqs-tab">
          <div id="faqs-container">
            @foreach($hotel->faqs as $index => $faq)
            <div class="card mb-3 faq-item border-info" id="faq_{{ $index }}">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h6 class="card-title mb-0 text-info fw-bold"><i class="fas fa-question-circle me-1"></i> FAQ</h6>
                  <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFaq('faq_{{ $index }}')"><i class="fas fa-trash me-1"></i> Remove</button>
                </div>
                <!-- Hidden ID field for existing FAQs -->
                <input type="hidden" name="faqs[{{ $index }}][id]" value="{{ $faq->id }}">
                <div class="mb-3">
                  <label class="form-label fw-semibold">Question</label>
                  <input type="text" class="form-control" name="faqs[{{ $index }}][question]" value="{{ $faq->question }}" required>
                </div>
                <div class="mb-2">
                  <label class="form-label fw-semibold">Answer</label>
                  <textarea class="form-control tinymce" id="faq_answer_{{ $index }}" name="faqs[{{ $index }}][answer]">{{ $faq->answer }}</textarea>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <button type="button" class="btn btn-outline-primary mt-3" onclick="addFaq()">+ Add FAQ</button>
        </div>
      </div>

      <div class="mt-4 pt-3 border-top">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('hotels.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>


<div class="modal fade" id="addBookingFeatureModal" tabindex="-1" aria-labelledby="addBookingFeatureModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addBookingFeatureModalLabel"><i class="fas fa-plus-circle me-2 text-primary"></i>Add New Booking Feature</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="bf-modal-alert" class="d-none"></div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_bf_name">Feature Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="new_bf_name" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_bf_icon">Icon</label>
          <div class="dropdown">
            <button class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center" type="button" id="bfIconDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" style="background: #fff; border: 1px solid #d9dee3; padding: 8px 12px; height: 38px;">
              <span><i id="bf_selected_icon_display" class="fas fa-check me-2"></i> <span id="bf_selected_icon_text">fas fa-check</span></span>
              <i class="fas fa-chevron-down text-muted"></i>
            </button>
            <div class="dropdown-menu w-100 p-3" aria-labelledby="bfIconDropdownBtn">
              <input type="text" class="form-control mb-3" id="bfIconSearch" placeholder="Search icons...">
              <div class="d-flex flex-wrap gap-2 icon-picker-grid" id="bfIconGrid" style="max-height: 200px; overflow-y: auto;">
                @foreach($popularBfIcons as $ic)
                  <div class="icon-option bf-icon-option p-2 border rounded cursor-pointer text-center" data-icon="{{ $ic }}" style="width: 45px; height: 45px; display:flex; align-items:center; justify-content:center; cursor: pointer;" title="{{ $ic }}">
                    <i class="{{ $ic }} fs-5"></i>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          <input type="hidden" id="new_bf_icon" value="fas fa-check">
          <div class="form-text">Search and select an icon for the booking feature.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveNewBookingFeature()">
          <span id="saveBfBtnText">Save Feature</span>
          <div id="saveBfBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></div>
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addHotelPolicyModal" tabindex="-1" aria-labelledby="addHotelPolicyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addHotelPolicyModalLabel"><i class="fas fa-plus-circle me-2 text-primary"></i>Add New Hotel Policy</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="policy-modal-alert" class="d-none"></div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_policy_name">Policy Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="new_policy_name" placeholder="e.g. Breakfast Schedule" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_policy_type">Input Type</label>
          <select class="form-select" id="new_policy_type">
            <option value="textarea">Large Text Area (Multiple Lines)</option>
            <option value="text">Single Line Text</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveNewHotelPolicy()">
          <span id="savePolicyBtnText">Save Policy</span>
          <div id="savePolicyBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></div>
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCategoryModalLabel"><i class="fas fa-plus-circle me-2 text-primary"></i>Add New Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="category-modal-alert" class="d-none"></div>
          <div class="mb-3">
            <label class="form-label fw-semibold" for="new_category_name">Category Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="new_category_name" onkeyup="document.getElementById('new_category_slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')" />
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" for="new_category_slug">Slug <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="new_category_slug" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="saveNewCategory()">
            <span id="saveCategoryBtnText"><i class="fas fa-plus me-1"></i>Add Category</span>
            <span id="saveCategoryBtnSpinner" class="d-none"><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
          </button>
        </div>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
// Icon Picker Search (Amenity)
      const amenityIconSearch = document.getElementById('amenityIconSearch');
      if (amenityIconSearch) {
          amenityIconSearch.addEventListener('input', function() {
              const term = this.value.toLowerCase();
              document.querySelectorAll('.amenity-icon-option').forEach(opt => {
                  const iconName = opt.getAttribute('data-icon').toLowerCase();
                  opt.style.display = iconName.includes(term) ? 'flex' : 'none';
              });
          });
      }
      // Icon Selection (Amenity)
      document.querySelectorAll('.amenity-icon-option').forEach(opt => {
          opt.addEventListener('click', function() {
              const iconName = this.getAttribute('data-icon');
              document.getElementById('new_amenity_icon').value = iconName;
              document.getElementById('amenity_selected_icon_display').className = iconName + ' me-2';
              document.getElementById('amenity_selected_icon_text').textContent = iconName;
          });
      });

      // Icon Picker Search (Booking Feature)
      const bfIconSearch = document.getElementById('bfIconSearch');
      if (bfIconSearch) {
          bfIconSearch.addEventListener('input', function() {
              const term = this.value.toLowerCase();
              document.querySelectorAll('.bf-icon-option').forEach(opt => {
                  const iconName = opt.getAttribute('data-icon').toLowerCase();
                  opt.style.display = iconName.includes(term) ? 'flex' : 'none';
              });
          });
      }
      // Icon Selection (Booking Feature)
      document.querySelectorAll('.bf-icon-option').forEach(opt => {
          opt.addEventListener('click', function() {
              const iconName = this.getAttribute('data-icon');
              document.getElementById('new_bf_icon').value = iconName;
              document.getElementById('bf_selected_icon_display').className = iconName + ' me-2';
              document.getElementById('bf_selected_icon_text').textContent = iconName;
          });
      });
      });
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateCount(inputId, countId) {
        const input = document.getElementById(inputId);
        const count = document.getElementById(countId);
        if (input && count) {
            const update = () => {
                count.textContent = `(${input.value.length} / 160)`;
            };
            input.addEventListener('input', update);
            update(); // Init on load
        }
    }
    updateCount('meta_description', 'meta_desc_count');
    updateCount('og_description', 'og_desc_count');
});
</script>

<style>
  /* Cuisines dropdown styles */
  .cuisine-dropdown-wrapper { position: relative; width: 100%; }
  .cuisine-dropdown-trigger {
    display: flex; align-items: center; justify-content: space-between;
    min-height: 38px; padding: 6px 12px; border: 1px solid #dee2e6;
    border-radius: 6px; background: #fff; cursor: pointer;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out; gap: 10px; user-select: none;
    width: 100%;
  }
  .cuisine-dropdown-trigger:hover { border-color: #7367f0; }
  .cuisine-dropdown-trigger.open { border-color: #7367f0; box-shadow: 0 0 0 3px rgba(115,103,240,.15); }
  .cuisine-tags-area { display: flex; flex-wrap: wrap; gap: 6px; flex: 1; align-items: center; min-height: 28px; }
  .cuisine-placeholder { color: #9ea5b1; font-size: .92rem; display: flex; align-items: center; }
  .cuisine-tag {
    display: inline-flex; align-items: center; gap: 5px;
    background: #ede9ff; color: #5a50d6; border-radius: 20px;
    padding: 3px 10px 3px 8px; font-size: .8rem; font-weight: 600; white-space: nowrap;
  }
  .cuisine-tag .tag-remove { cursor: pointer; color: #8b82e0; font-size: .75rem; margin-left: 2px; transition: color .15s; }
  .cuisine-tag .tag-remove:hover { color: #dc3545; }
  .cuisine-dropdown-arrow { font-size: .8rem; color: #9ea5b1; transition: transform .25s; flex-shrink: 0; }
  .cuisine-dropdown-arrow.rotated { transform: rotate(180deg); }
  .cuisine-dropdown-panel {
    position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: #fff; border: 1.5px solid #d5d9e0; border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,.12); z-index: 1055; overflow: hidden;
    animation: dropdownFade .18s ease;
  }
  .cuisine-search-wrap { display: flex; align-items: center; padding: 10px 14px; gap: 10px; background: #f8f7ff; }
  .cuisine-search-icon { color: #9ea5b1; font-size: .9rem; flex-shrink: 0; }
  .cuisine-search-input { border: none; outline: none; background: transparent; font-size: .9rem; width: 100%; color: #3a3a3a; }
  .cuisine-search-input::placeholder { color: #b0b8c9; }
  .cuisine-divider { height: 1px; background: #eeedf5; }
  .cuisine-items-list { max-height: 240px; overflow-y: auto; padding: 6px 0; }
  .cuisine-items-list::-webkit-scrollbar { width: 4px; }
  .cuisine-items-list::-webkit-scrollbar-thumb { background: #d5d9e0; border-radius: 4px; }
  .cuisine-item {
    display: flex; align-items: center; gap: 10px; padding: 9px 16px;
    cursor: pointer; margin: 0; font-weight: 400; transition: background .13s;
  }
  .cuisine-item:hover { background: #f4f2ff; }
  .cuisine-item.selected { background: #ede9ff; }
  .cuisine-item.selected:hover { background: #e4dfff; }
  .cuisine-item input[type="radio"] { display: none; }
  .cuisine-item-name { flex: 1; font-size: .9rem; color: #3a3a3a; }
  .cuisine-item.selected .cuisine-item-name { color: #5a50d6; font-weight: 600; }
  .cuisine-item-check { font-size: .8rem; color: #7367f0; display: none; }
  .cuisine-item.selected .cuisine-item-check { display: block; }
  .cuisine-no-results { padding: 16px; text-align: center; color: #9ea5b1; font-size: .88rem; }
  .cuisine-panel-footer { display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; background: #f8f7ff; }

  /* ===== Custom Amenity Multi-Select Dropdown ===== */
  .amenity-dropdown-wrapper { position: relative; }

  .amenity-dropdown-trigger {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 48px;
    padding: 8px 14px;
    border: 1.5px solid #d5d9e0;
    border-radius: 8px;
    background: #fff;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s;
    gap: 10px;
    user-select: none;
  }
  .amenity-dropdown-trigger:hover { border-color: #7367f0; }
  .amenity-dropdown-trigger.open  { border-color: #7367f0; box-shadow: 0 0 0 3px rgba(115,103,240,.15); }

  .amenity-tags-area { display: flex; flex-wrap: wrap; gap: 6px; flex: 1; align-items: center; min-height: 28px; }

  .amenity-placeholder { color: #9ea5b1; font-size: .92rem; display: flex; align-items: center; }

  .amenity-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #ede9ff;
    color: #5a50d6;
    border-radius: 20px;
    padding: 3px 10px 3px 8px;
    font-size: .8rem;
    font-weight: 600;
    line-height: 1.4;
    white-space: nowrap;
  }
  .amenity-tag .tag-remove {
    cursor: pointer;
    color: #8b82e0;
    font-size: .75rem;
    margin-left: 2px;
    transition: color .15s;
  }
  .amenity-tag .tag-remove:hover { color: #dc3545; }

  .amenity-dropdown-arrow {
    font-size: .8rem;
    color: #9ea5b1;
    transition: transform .25s;
    flex-shrink: 0;
  }
  .amenity-dropdown-arrow.rotated { transform: rotate(180deg); }

  .amenity-dropdown-panel {
    position: absolute;
    top: calc(100% + 4px);
    left: 0; right: 0;
    background: #fff;
    border: 1.5px solid #d5d9e0;
    border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,.12);
    z-index: 1055;
    overflow: hidden;
    animation: dropdownFade .18s ease;
  }
  @keyframes dropdownFade {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .amenity-search-wrap {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    gap: 10px;
    background: #f8f7ff;
  }
  .amenity-search-icon { color: #9ea5b1; font-size: .9rem; flex-shrink: 0; }
  .amenity-search-input {
    border: none;
    outline: none;
    background: transparent;
    font-size: .9rem;
    width: 100%;
    color: #3a3a3a;
  }
  .amenity-search-input::placeholder { color: #b0b8c9; }

  .amenity-divider { height: 1px; background: #eeedf5; }

  .amenity-items-list { max-height: 240px; overflow-y: auto; padding: 6px 0; }
  .amenity-items-list::-webkit-scrollbar { width: 4px; }
  .amenity-items-list::-webkit-scrollbar-thumb { background: #d5d9e0; border-radius: 4px; }

  .amenity-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 16px;
    cursor: pointer;
    margin: 0;
    font-weight: 400;
    transition: background .13s;
    border-radius: 0;
  }
  .amenity-item:hover { background: #f4f2ff; }
  .amenity-item--checked { background: #ede9ff; }
  .amenity-item--checked:hover { background: #e4dfff; }

  .amenity-item input[type="checkbox"] { display: none; } /* Hide native checkbox */

  .amenity-item-icon {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    background: #f0eeff;
    color: #7367f0;
    font-size: .85rem;
    flex-shrink: 0;
  }
  .amenity-item--checked .amenity-item-icon { background: #7367f0; color: #fff; }

  .amenity-item-name { flex: 1; font-size: .9rem; color: #3a3a3a; }
  .amenity-item--checked .amenity-item-name { color: #5a50d6; font-weight: 600; }

  .amenity-item-check { font-size: .8rem; color: #7367f0; display: none; }
  .amenity-item--checked .amenity-item-check { display: block; }

  .amenity-no-results { padding: 16px; text-align: center; color: #9ea5b1; font-size: .88rem; }

  .amenity-panel-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 16px;
    background: #f8f7ff;
  }
  .amenity-selected-count {
    font-size: .8rem;
    color: #7367f0;
    font-weight: 700;
    background: #ede9ff;
    border-radius: 20px;
    padding: 2px 10px;
  }
</style>
<script>
  /* ===== Custom Amenity Dropdown Logic ===== */
  let amenityDropdownOpen = false;

  function toggleAmenityDropdown() {
    amenityDropdownOpen ? closeAmenityDropdown() : openAmenityDropdown();
  }

  function openAmenityDropdown() {
    document.getElementById('amenityDropdownPanel').style.display = 'block';
    document.getElementById('amenityTrigger').classList.add('open');
    document.getElementById('amenityArrow').classList.add('rotated');
    document.getElementById('amenitySearchInput').focus();
    amenityDropdownOpen = true;
  }

  function closeAmenityDropdown() {
    document.getElementById('amenityDropdownPanel').style.display = 'none';
    document.getElementById('amenityTrigger').classList.remove('open');
    document.getElementById('amenityArrow').classList.remove('rotated');
    document.getElementById('amenitySearchInput').value = '';
    filterAmenities('');
    amenityDropdownOpen = false;
  }

  // Close on outside click
  document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('amenityDropdownWrapper');
    if (wrapper && !wrapper.contains(e.target)) closeAmenityDropdown();
  });

  function onAmenityChange(cb) {
    const label = document.getElementById('amenity-label-' + cb.dataset.id);
    if (cb.checked) {
      label.classList.add('amenity-item--checked');
    } else {
      label.classList.remove('amenity-item--checked');
    }
    renderAmenityTags();
  }

  function renderAmenityTags() {
    const cbs = document.querySelectorAll('.amenity-cb:checked');
    const tagsArea = document.getElementById('amenityTagsArea');
    const placeholder = document.getElementById('amenityPlaceholder');
    const countEl = document.getElementById('amenitySelectedCount');

    tagsArea.querySelectorAll('.amenity-tag').forEach(t => t.remove());

    if (cbs.length === 0) {
      tagsArea.appendChild(placeholder);
      placeholder.style.display = 'flex';
      countEl.textContent = '0 selected';
      return;
    }

    placeholder.style.display = 'none';
    countEl.textContent = cbs.length + ' selected';

    cbs.forEach(cb => {
      const tag = document.createElement('span');
      tag.className = 'amenity-tag';
      tag.innerHTML = `${cb.dataset.name} <span class="tag-remove" onclick="removeAmenityTag(event, '${cb.dataset.id}')"><i class="fas fa-times"></i></span>`;
      tagsArea.appendChild(tag);
    });
  }

  function removeAmenityTag(e, id) {
    e.stopPropagation();
    const cb = document.getElementById('amenity_cb_' + id);
    if (cb) {
      cb.checked = false;
      cb.dispatchEvent(new Event('change'));
    }
  }

  function filterAmenities(query) {
    const q = query.toLowerCase().trim();
    const items = document.querySelectorAll('.amenity-item');
    let visible = 0;
    items.forEach(item => {
      const name = item.querySelector('.amenity-item-name').textContent.toLowerCase();
      if (name.includes(q)) { item.style.display = 'flex'; visible++; }
      else item.style.display = 'none';
    });
    const noResults = document.getElementById('amenityNoResults');
    if (visible === 0) noResults.classList.remove('d-none');
    else noResults.classList.add('d-none');
  }

  // ===== Featured image preview =====
  function previewFeaturedImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('featured-preview-img').src = e.target.result;
      document.getElementById('featured-preview-new').style.display = 'block';
    };
    reader.readAsDataURL(file);
  }

  // ===== Icon preview for new amenity modal =====
  function updateAmenityIconPreview(val) {
    document.getElementById('amenity-icon-preview').className = 'fas ' + val.trim();
  }

  // ===== Save new amenity via AJAX =====
  function saveNewAmenity() {
    const name = document.getElementById('new_amenity_name').value.trim();
    const icon = document.getElementById('new_amenity_icon').value.trim();
    const alertBox = document.getElementById('amenity-modal-alert');

    if (!name) {
      alertBox.className = 'alert alert-danger';
      alertBox.textContent = 'Please enter an amenity name.';
      return;
    }

    alertBox.className = 'd-none';
    document.getElementById('saveAmenityBtnText').classList.add('d-none');
    document.getElementById('saveAmenityBtnSpinner').classList.remove('d-none');

    $.ajax({
      url: '{{ route("amenities.store") }}',
      type: 'POST',
      data: { _token: '{{ csrf_token() }}', name: name, icon: icon || 'fa-star', status: 1 },
      success: function(response) {
        if (response.success) {
          const a = response.amenity;
          // Inject new item into dropdown list
          const list = document.getElementById('amenityItemsList');
          const noResults = document.getElementById('amenityNoResults');
          const newLabel = document.createElement('label');
          newLabel.className = 'amenity-item amenity-item--checked';
          newLabel.id = 'amenity-label-' + a.id;
          newLabel.innerHTML = `
            <input type="checkbox" name="amenities[]" value="${a.id}" id="amenity_cb_${a.id}"
                   class="amenity-cb" data-name="${a.name}" data-id="${a.id}" checked
                   onchange="onAmenityChange(this)" />
            <span class="amenity-item-icon"><i class="fas ${a.icon || 'fa-star'}"></i></span>
            <span class="amenity-item-name">${a.name}</span>
            <span class="amenity-item-check"><i class="fas fa-check"></i></span>`;
          list.insertBefore(newLabel, noResults);
          renderAmenityTags();
          // Reset modal
          document.getElementById('new_amenity_name').value = '';
          document.getElementById('new_amenity_icon').value = 'fa-star';
          updateAmenityIconPreview('fa-star');
          bootstrap.Modal.getInstance(document.getElementById('addAmenityModal')).hide();
        } else {
          alertBox.className = 'alert alert-danger';
          alertBox.textContent = response.message || 'Failed to add amenity.';
        }
      },
      error: function(xhr) {
        alertBox.className = 'alert alert-danger';
        const errors = xhr.responseJSON?.errors;
        alertBox.textContent = errors ? Object.values(errors).flat().join(' ') : 'An error occurred.';
      },
      complete: function() {
        document.getElementById('saveAmenityBtnText').classList.remove('d-none');
        document.getElementById('saveAmenityBtnSpinner').classList.add('d-none');
      }
    });
  }

  // ===== Gallery delete overlay =====
  function toggleDeleteOverlay(cb, id) {
    const overlay = document.getElementById('overlay-' + id);
    if (cb.checked) { overlay.classList.remove('d-none'); overlay.classList.add('d-flex'); }
    else { overlay.classList.remove('d-flex'); overlay.classList.add('d-none'); }
  }

  function toggleVideoDeleteOverlay(cb) {
    const overlay = document.getElementById('overlay-video');
    if (cb.checked) { overlay.classList.remove('d-none'); overlay.classList.add('d-flex'); }
    else { overlay.classList.remove('d-flex'); overlay.classList.add('d-none'); }
  }

  // ===== Gallery image preview =====
  function previewGalleryImages(event) {
    const files = Array.from(event.target.files);
    const previewGrid = document.getElementById('gallery-new-preview');
    const altFields = document.getElementById('gallery-alt-fields');
    previewGrid.innerHTML = '';
    altFields.innerHTML = '';

    files.forEach((file, index) => {
      const reader = new FileReader();
      reader.onload = function(e) {
        const col = document.createElement('div');
        col.className = 'col-md-3 col-sm-4 col-6';
        col.innerHTML = `<div class="card border"><img src="${e.target.result}" class="card-img-top" style="height:140px;object-fit:cover;" /><div class="card-body p-2"><small class="text-muted">New Image ${index+1}</small></div></div>`;
        previewGrid.appendChild(col);
      };
      reader.readAsDataURL(file);

      const altWrap = document.createElement('div');
      altWrap.className = 'mb-2';
      altWrap.innerHTML = `<label class="form-label small">Alt Text for Image ${index+1} (SEO)</label><input type="text" class="form-control form-control-sm" name="gallery_alts[${index}]" />`;
      altFields.appendChild(altWrap);
    });

    if (files.length > 0) {
      const h = document.createElement('div'); h.className = 'col-12 mb-2';
      h.innerHTML = `<label class="form-label fw-semibold">Preview (${files.length} images selected)</label>`;
      previewGrid.insertBefore(h, previewGrid.firstChild);
      const ah = document.createElement('h6'); ah.className = 'mt-3 mb-2 fw-semibold';
      ah.textContent = 'Add Alt Text for Gallery Images (SEO)';
      altFields.insertBefore(ah, altFields.firstChild);
    }
  }

  // Init: render pre-checked tags on page load
  document.addEventListener('DOMContentLoaded', function() { renderAmenityTags(); });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  $(document).ready(function() {
    tinymce.init({
      selector: 'textarea.tinymce',
      plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
      toolbar_mode: 'floating',
      height: 300,
      setup: function (editor) {
        editor.on('change', function () {
          editor.save();
        });
      }
    });
  });

  let faqIndex = Date.now();
  function addFaq() {
    const container = document.getElementById('faqs-container');
    const id = 'faq_' + faqIndex;
    const html = `
      <div class="card mb-3 faq-item border-info" id="${id}">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="card-title mb-0 text-info fw-bold"><i class="fas fa-question-circle me-1"></i> New FAQ</h6>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFaq('${id}')"><i class="fas fa-trash me-1"></i> Remove</button>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Question</label>
            <input type="text" class="form-control" name="faqs[${faqIndex}][question]" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Answer</label>
            <textarea class="form-control tinymce" id="faq_answer_${faqIndex}" name="faqs[${faqIndex}][answer]"></textarea>
          </div>
        </div>
      </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    
    // Initialize TinyMCE for the new textarea
    tinymce.init({
      selector: '#faq_answer_' + faqIndex,
      plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
      toolbar_mode: 'floating',
      height: 200,
      setup: function (editor) {
        editor.on('change', function () {
          editor.save();
        });
      }
    });
    
    faqIndex++;
  }

  function removeFaq(id) {
    document.getElementById(id).remove();
  }
</script>

{{-- ===== Add Amenity Modal ===== --}}
<div class="modal fade" id="addAmenityModal" tabindex="-1" aria-labelledby="addAmenityModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addAmenityModalLabel"><i class="fas fa-plus-circle me-2 text-primary"></i>Add New Amenity</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="amenity-modal-alert" class="d-none"></div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_amenity_name">Amenity Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="new_amenity_name" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_amenity_icon">Icon</label>
          <div class="dropdown">
            <button class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center" type="button" id="amenityIconDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" style="background: #fff; border: 1px solid #d9dee3; padding: 8px 12px; height: 38px;">
              <span><i id="amenity_selected_icon_display" class="fas fa-star me-2"></i> <span id="amenity_selected_icon_text">fas fa-star</span></span>
              <i class="fas fa-chevron-down text-muted"></i>
            </button>
            <div class="dropdown-menu w-100 p-3" aria-labelledby="amenityIconDropdownBtn">
              <input type="text" class="form-control mb-3" id="amenityIconSearch" placeholder="Search icons...">
              <div class="d-flex flex-wrap gap-2 icon-picker-grid" id="amenityIconGrid" style="max-height: 200px; overflow-y: auto;">
                @foreach($popularIcons as $ic)
                  <div class="icon-option amenity-icon-option p-2 border rounded cursor-pointer text-center" data-icon="{{ $ic }}" style="width: 45px; height: 45px; display:flex; align-items:center; justify-content:center; cursor: pointer;" title="{{ $ic }}">
                    <i class="{{ $ic }} fs-5"></i>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          <input type="hidden" id="new_amenity_icon" value="fas fa-star">
          <div class="form-text">Search and select an icon for the amenity.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveNewAmenity()">
          <span id="saveAmenityBtnText"><i class="fas fa-plus me-1"></i>Add Amenity</span>
          <span id="saveAmenityBtnSpinner" class="d-none"><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
        </button>
      </div>
    </div>
  </div>
</div>

  {{-- ===== Add Category Modal ===== --}}

  <script>
    function saveNewCategory() {
      const name = document.getElementById('new_category_name').value.trim();
      const slug = document.getElementById('new_category_slug').value.trim();
      const alertBox = document.getElementById('category-modal-alert');
      if (!name || !slug) { alertBox.className = 'alert alert-danger'; alertBox.textContent = 'Please enter name and slug.'; return; }
      alertBox.className = 'd-none';
      document.getElementById('saveCategoryBtnText').classList.add('d-none');
      document.getElementById('saveCategoryBtnSpinner').classList.remove('d-none');
      $.ajax({
        url: '{{ route("hotel-categories.quick-store") }}', type: 'POST',
        data: { _token: '{{ csrf_token() }}', name, slug, status: 1 },
        success: function(response) {
          if (response.success) {
            const cat = response.category;
            const list = document.getElementById('categoryItemsList');
            const lbl = document.createElement('label');
            lbl.className = 'cuisine-item';
            lbl.id = 'cat-label-' + cat.id;
            lbl.innerHTML = `
              <input type="radio" name="_cat_radio" value="${cat.id}" id="cat_rb_${cat.id}" class="cat-rb d-none" data-name="${cat.name}" data-id="${cat.id}" onchange="onCategoryChange(this)" />
              <span class="cuisine-item-name">${cat.name}</span>
              <span class="cuisine-item-check"><i class="fas fa-check"></i></span>
            `;
            list.insertBefore(lbl, list.firstChild);
            
            const newRb = lbl.querySelector('input[type="radio"]');
            newRb.checked = true;
            onCategoryChange(newRb);

            document.getElementById('new_category_name').value = '';
            document.getElementById('new_category_slug').value = '';
            alertBox.className = 'alert alert-success'; alertBox.textContent = 'Category added!';
            bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
          } else { alertBox.className = 'alert alert-danger'; alertBox.textContent = response.message || 'Failed.'; }
        },
        error: function(xhr) {
          alertBox.className = 'alert alert-danger';
          const errors = xhr.responseJSON?.errors;
          alertBox.textContent = errors ? Object.values(errors).flat().join(' ') : 'An error occurred.';
        },
        complete: function() {
          document.getElementById('saveCategoryBtnText').classList.remove('d-none');
          document.getElementById('saveCategoryBtnSpinner').classList.add('d-none');
        }
      });
    }

    function toggleCategoryDropdown() {
      const panel  = document.getElementById('categoryDropdownPanel');
      const arrow  = document.getElementById('categoryArrow');
      const isOpen = panel.style.display !== 'none';
      panel.style.display = isOpen ? 'none' : 'block';
      arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
      if (!isOpen) document.getElementById('categorySearchInput').focus();
    }
    function closeCategoryDropdown() {
      const panel = document.getElementById('categoryDropdownPanel');
      if(panel) { panel.style.display = 'none'; document.getElementById('categoryArrow').style.transform = 'rotate(0deg)'; }
    }
    function filterCategories(val) {
      const term  = val.toLowerCase();
      const items = document.querySelectorAll('#categoryItemsList .cuisine-item');
      let   found = 0;
      items.forEach(item => {
        const name = item.querySelector('.cuisine-item-name').textContent.toLowerCase();
        const show = name.includes(term);
        item.style.display = show ? '' : 'none';
        if (show) found++;
      });
      document.getElementById('categoryNoResults').classList.toggle('d-none', found > 0);
    }
    function onCategoryChange(rb) {
      const id    = rb.dataset.id;
      const name  = rb.dataset.name;
      const hidden= document.getElementById('hotel_category_id');
      const tags  = document.getElementById('categoryTagsArea');
      const ph    = document.getElementById('categoryPlaceholder');

      hidden.value = id;
      document.querySelectorAll('#categoryItemsList .cuisine-item').forEach(l => l.classList.remove('selected'));
      const label = document.getElementById('cat-label-' + id);
      if(label) label.classList.add('selected');

      ph.style.display = 'none';
      let existing = tags.querySelector('.category-selected-text');
      if (!existing) {
        existing = document.createElement('span');
        existing.className = 'category-selected-text';
        existing.style.cssText = 'font-size:0.9rem; font-weight:500; color:#333;';
        tags.insertBefore(existing, ph);
      }
      existing.textContent = name;
      closeCategoryDropdown();

      // Clear validation styling
      const categoryTrigger = document.getElementById('categoryTrigger');
      if (categoryTrigger) {
        categoryTrigger.style.borderColor = '';
      }
      const categoryError = document.getElementById('category-error-msg');
      if (categoryError) {
        categoryError.classList.add('d-none');
      }
    }

    document.addEventListener('click', function(e) {
      const wrapper = document.getElementById('categoryDropdownWrapper');
      if (wrapper && !wrapper.contains(e.target)) closeCategoryDropdown();
    });

    document.addEventListener('DOMContentLoaded', function() {
      const preselectedCat = document.getElementById('hotel_category_id').value;
      if (preselectedCat) {
        const rb = document.getElementById('cat_rb_' + preselectedCat);
        if (rb) { rb.checked = true; onCategoryChange(rb); }
      }
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {

      // Real-time input validation clear
      const nameInput = document.getElementById('name');
      const slugInput = document.getElementById('slug');
      if (nameInput) {
        nameInput.addEventListener('input', function() {
          if (this.value.trim()) {
            this.classList.remove('is-invalid');
          }
        });
      }
      if (slugInput) {
        slugInput.addEventListener('input', function() {
          if (this.value.trim()) {
            this.classList.remove('is-invalid');
          }
        });
      }

      // Helper function to validate Basic Info fields
      function validateBasicInfo() {
        const categoryInput = document.getElementById('hotel_category_id');
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        
        let isValid = true;
        
        // Check Category
        if (!categoryInput || !categoryInput.value.trim()) {
          isValid = false;
          const categoryTrigger = document.getElementById('categoryTrigger');
          if (categoryTrigger) {
            categoryTrigger.style.borderColor = '#ff3e1d';
          }
          const categoryError = document.getElementById('category-error-msg');
          if (categoryError) {
            categoryError.classList.remove('d-none');
          }
        } else {
          const categoryTrigger = document.getElementById('categoryTrigger');
          if (categoryTrigger) {
            categoryTrigger.style.borderColor = '';
          }
          const categoryError = document.getElementById('category-error-msg');
          if (categoryError) {
            categoryError.classList.add('d-none');
          }
        }
        
        // Check Name
        if (!nameInput || !nameInput.value.trim()) {
          isValid = false;
          if (nameInput) nameInput.classList.add('is-invalid');
        } else {
          if (nameInput) nameInput.classList.remove('is-invalid');
        }
        
        // Check Slug
        if (!slugInput || !slugInput.value.trim()) {
          isValid = false;
          if (slugInput) slugInput.classList.add('is-invalid');
        } else {
          if (slugInput) slugInput.classList.remove('is-invalid');
        }
        
        return isValid;
      }

      // Tab navigation validation for Basic Info required fields
      const triggerTabList = document.querySelectorAll('#hotelFormTabs button');
      triggerTabList.forEach(triggerEl => {
        triggerEl.addEventListener('show.bs.tab', function(event) {
          if (event.target.id !== 'basic-tab') {
            const isValid = validateBasicInfo();
            if (!isValid) {
              event.preventDefault(); // Prevent navigating to the clicked tab
              
              // Focus on the first empty required input
              const nameInput = document.getElementById('name');
              const slugInput = document.getElementById('slug');
              if (nameInput && !nameInput.value.trim()) {
                nameInput.focus();
              } else if (slugInput && !slugInput.value.trim()) {
                slugInput.focus();
              }
            }
          }
        });
      });

      // Form submission validation
      const form = document.getElementById('hotelEditForm');
      if (form) {
        form.addEventListener('submit', function(event) {
          if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
          }
          const isValid = validateBasicInfo();
          if (!isValid) {
            event.preventDefault(); // Prevent form submission
            
            // Switch back to Basic Info tab if not active
            const basicTab = document.getElementById('basic-tab');
            if (basicTab && !basicTab.classList.contains('active')) {
              const tab = new bootstrap.Tab(basicTab);
              tab.show();
            }
            
            // Focus on first invalid field
            setTimeout(() => {
              const nameInput = document.getElementById('name');
              const slugInput = document.getElementById('slug');
              if (nameInput && !nameInput.value.trim()) {
                nameInput.focus();
              } else if (slugInput && !slugInput.value.trim()) {
                slugInput.focus();
              }
            }, 250);
          }
        });
      }
    });
  </script>
@endsection


