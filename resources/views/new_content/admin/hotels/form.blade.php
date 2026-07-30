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
               value="{{ old('hotel_category_id', isset($hotel) ? $hotel->hotel_category_id : '') }}" required />
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
                       {{ old('hotel_category_id', isset($hotel) ? $hotel->hotel_category_id : '') == $cat->id ? 'checked' : '' }}
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
            <div class="cuisine-items-list p-0">
              <div class="cuisine-panel-footer">
                <button type="button" class="btn btn-sm btn-link p-0 text-primary fw-semibold"
                        data-bs-toggle="modal" data-bs-target="#addCategoryModal" onclick="closeCategoryDropdown()">
                  <i class="fas fa-plus-circle me-1"></i>Add New Category
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="text-danger small mt-1 d-none" id="category-error-msg">The category field is required.</div>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', isset($hotel) ? $hotel->name : '') }}"  placeholder="e.g. The Grand Hotel" />
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @else <div class="invalid-feedback">The name field is required.</div> @enderror
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', isset($hotel) ? $hotel->slug : '') }}"  placeholder="e.g. the-grand-hotel" />
        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @else <div class="invalid-feedback">The slug field is required.</div> @enderror
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label" for="description">Description</label>
      <textarea class="form-control tinymce" id="description" name="description" rows="6">{{ old('description', isset($hotel) ? $hotel->description : '') }}</textarea>
    </div>
    <div class="mb-3">
      <div class="form-check form-switch mt-2">
        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', isset($hotel) ? $hotel->is_featured : 0) == 1 ? 'checked' : '' }}>
        <label class="form-check-label fw-semibold" for="is_featured">Featured Hotel (Shows on Home Page)</label>
      </div>
    </div>
  </div>

  <!-- Tab 2: Details & Pricing -->
  <div class="tab-pane fade" id="details-pane" role="tabpanel" aria-labelledby="details-tab">
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label" for="city">City</label>
        {{-- Hidden input that holds the selected city --}}
        <input type="hidden" name="city" id="city" value="{{ old('city', isset($hotel) ? $hotel->city : '') }}" />

        <div class="cuisine-dropdown-wrapper" id="cityDropdownWrapper">
          <div class="cuisine-dropdown-trigger" id="cityTrigger" onclick="toggleCityDropdown()">
            <div class="cuisine-tags-area" id="cityTagsArea">
              <span class="cuisine-placeholder" id="cityPlaceholder">
                <i class="fas fa-map-marker-alt me-2 text-muted"></i>Select a city...
              </span>
            </div>
            <i class="fas fa-chevron-down cuisine-dropdown-arrow" id="cityArrow"></i>
          </div>
          <div class="cuisine-dropdown-panel" id="cityDropdownPanel" style="display:none;">
            <div class="cuisine-search-wrap">
              <i class="fas fa-search cuisine-search-icon"></i>
              <input type="text" class="cuisine-search-input" id="citySearchInput"
                     placeholder="Search cities..." oninput="filterCitiesList(this.value)" autocomplete="off" />
            </div>
            <div class="cuisine-divider"></div>
            <div class="cuisine-items-list" id="cityItemsList">
              @foreach(config('michigan_cities') as $m_city)
              <label class="cuisine-item" id="city-label-{{ $loop->index }}">
                <input type="radio" name="_city_radio" value="{{ $m_city }}"
                       id="city_rb_{{ $loop->index }}"
                       class="city-rb d-none"
                       data-name="{{ $m_city }}"
                       {{ old('city', isset($hotel) ? $hotel->city : '') == $m_city ? 'checked' : '' }}
                       onchange="onCityChange(this)" />
                <span class="cuisine-item-name">{{ $m_city }}</span>
                <span class="cuisine-item-check"><i class="fas fa-check"></i></span>
              </label>
              @endforeach
              <div class="cuisine-no-results d-none" id="cityNoResults">
                <i class="fas fa-search-minus me-2"></i>No cities found
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label" for="zip">Zip Code</label>
        <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip', isset($hotel) ? $hotel->zip : '') }}" placeholder="e.g. 49757" />
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label" for="address">Street Address</label>
        <textarea class="form-control" id="address" name="address" rows="1" placeholder="e.g. 286 Grand Avenue">{{ old('address', isset($hotel) ? $hotel->address : '') }}</textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label" for="phone">Phone Number</label>
        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', isset($hotel) ? $hotel->phone : '') }}" placeholder="e.g. +1 555-123-4567" />
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label" for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', isset($hotel) ? $hotel->email : '') }}" placeholder="e.g. info@example.com" />
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label" for="website">Website URL</label>
        <input type="url" class="form-control" id="website" name="website" value="{{ old('website', isset($hotel) ? $hotel->website : '') }}" placeholder="e.g. https://www.example.com" />
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label" for="starting_price">Starting Price ($)</label>
        <input type="number" class="form-control" id="starting_price" name="starting_price" value="{{ old('starting_price', isset($hotel) ? $hotel->starting_price : '') }}" placeholder="e.g. 199" />
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label" for="map_iframe">Google Maps Embed Code (Iframe Link)</label>
        <textarea class="form-control" id="map_iframe" name="map_iframe" rows="1" placeholder="Paste the <iframe src='...'></iframe> embed code here">{{ old('map_iframe', isset($hotel) ? $hotel->map_iframe : '') }}</textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <label class="form-label fw-semibold mb-0">Booking Affiliate Link</label>
          <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAffiliateLinkModal">
            <i class="fas fa-plus me-1"></i> Add Affiliate Link
          </button>
        </div>

        {{-- Custom Affiliate Link Dropdown --}}
        <div class="amenity-dropdown-wrapper" id="affiliateLinkDropdownWrapper">

          {{-- Trigger Button --}}
          <div class="amenity-dropdown-trigger" id="affiliateLinkTrigger" onclick="toggleAffiliateLinkDropdown()">
            <div class="amenity-tags-area" id="affiliateLinkTagsArea">
              <span class="amenity-placeholder" id="affiliateLinkPlaceholder">
                <i class="fas fa-link me-2 text-muted"></i>Click to select an affiliate link...
              </span>
            </div>
            <i class="fas fa-chevron-down amenity-dropdown-arrow" id="affiliateLinkArrow"></i>
          </div>

          {{-- Hidden input for selected value --}}
          <input type="hidden" name="affiliate_link_id" id="affiliate_link_id_input"
                 value="{{ old('affiliate_link_id', isset($hotel) ? $hotel->affiliate_link_id : '') }}">

          {{-- Dropdown Panel --}}
          <div class="amenity-dropdown-panel" id="affiliateLinkDropdownPanel" style="display:none;">
            <div class="amenity-search-wrap">
              <i class="fas fa-search amenity-search-icon"></i>
              <input type="text" class="amenity-search-input" id="affiliateLinkSearchInput"
                     placeholder="Search affiliate links..." oninput="filterAffiliateLinks(this.value)" autocomplete="off" />
            </div>
            <div class="amenity-divider"></div>
            <div class="amenity-items-list" id="affiliateLinkItemsList">
              {{-- None option --}}
              <label class="amenity-item {{ old('affiliate_link_id', isset($hotel) ? $hotel->affiliate_link_id : '') == '' ? 'amenity-item--checked' : '' }}" id="afflink-label-0">
                <input type="radio" name="_affiliate_link_radio" value="" id="afflink_rb_0" class="afflink-rb d-none"
                       data-name="— None —"
                       {{ old('affiliate_link_id', isset($hotel) ? $hotel->affiliate_link_id : '') == '' ? 'checked' : '' }}
                       onchange="onAffiliateLinkChange(this)" />
                <span class="amenity-item-icon"><i class="fas fa-ban"></i></span>
                <span class="amenity-item-name">— None —</span>
                <span class="amenity-item-check"><i class="fas fa-check"></i></span>
              </label>
              @php $selectedAffLinkId = old('affiliate_link_id', isset($hotel) ? $hotel->affiliate_link_id : ''); @endphp
              @foreach(\App\Models\AffiliateLink::where('is_active', 1)->orderBy('name')->get() as $affLink)
              @php $isSelected = $selectedAffLinkId == $affLink->id; @endphp
              <label class="amenity-item {{ $isSelected ? 'amenity-item--checked' : '' }}" id="afflink-label-{{ $affLink->id }}">
                <input type="radio" name="_affiliate_link_radio" value="{{ $affLink->id }}" id="afflink_rb_{{ $affLink->id }}" class="afflink-rb d-none"
                       data-name="{{ $affLink->name }}{{ $affLink->provider ? ' (' . $affLink->provider . ')' : '' }}"
                       {{ $isSelected ? 'checked' : '' }}
                       onchange="onAffiliateLinkChange(this)" />
                <span class="amenity-item-icon"><i class="fas fa-link"></i></span>
                <span class="amenity-item-name">
                  {{ $affLink->name }}
                  @if($affLink->provider)<small class="text-muted ms-1">({{ $affLink->provider }})</small>@endif
                </span>
                <span class="amenity-item-check"><i class="fas fa-check"></i></span>
              </label>
              @endforeach
              <div class="amenity-no-results d-none" id="affiliateLinkNoResults">
                <i class="fas fa-search-minus me-2"></i>No affiliate links found
              </div>
            </div>
            <div class="amenity-divider"></div>
            <div class="amenity-panel-footer">
              <button type="button" class="btn btn-sm btn-link p-0 text-primary fw-semibold"
                      data-bs-toggle="modal" data-bs-target="#addAffiliateLinkModal" onclick="closeAffiliateLinkDropdown()">
                <i class="fas fa-plus-circle me-1"></i>Add New Affiliate Link
              </button>
              @isset($hotel)
                @if($hotel->affiliate_link_id && $hotel->affiliateLink)
                <a href="{{ route('affiliate-links.show', $hotel->affiliate_link_id) }}" target="_blank"
                   class="btn btn-sm btn-link p-0 text-success fw-semibold">
                  <i class="fas fa-chart-line me-1"></i>{{ number_format($hotel->affiliateLink->total_clicks) }} clicks
                </a>
                @endif
              @endisset
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="row mt-3">
      <div class="col-md-6 mb-3">
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
            <div class="amenity-search-wrap">
              <i class="fas fa-search amenity-search-icon"></i>
              <input type="text" class="amenity-search-input" id="amenitySearchInput"
                     placeholder="Search amenities..." oninput="filterAmenities(this.value)" autocomplete="off" />
            </div>
            <div class="amenity-divider"></div>
            <div class="amenity-items-list" id="amenityItemsList">
              @foreach($amenities as $amenity)
              @php
                $checked = collect(old('amenities', isset($hotel) ? $hotel->amenities->pluck('id')->toArray() : []))->contains($amenity->id);
              @endphp
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
      <div class="col-md-6 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <label class="form-label fw-semibold mb-0">Booking Features</label>
          <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addBookingFeatureModal">
            <i class="fas fa-plus me-1"></i> Add Booking Feature
          </button>
        </div>

        {{-- Custom Booking Feature Dropdown --}}
        <div class="amenity-dropdown-wrapper" id="bookingFeatureDropdownWrapper">
          
          {{-- Trigger Button --}}
          <div class="amenity-dropdown-trigger" id="bookingFeatureTrigger" onclick="toggleBookingFeatureDropdown()">
            <div class="amenity-tags-area" id="bookingFeatureTagsArea">
              <span class="amenity-placeholder" id="bookingFeaturePlaceholder">
                <i class="fas fa-check-circle me-2 text-muted"></i>Click to select booking features...
              </span>
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
                $checked = collect(old('booking_features', isset($hotel) ? $hotel->bookingFeatures->pluck('id')->toArray() : []))->contains($feature->id);
              @endphp
              <label class="amenity-item {{ $checked ? 'amenity-item--checked' : '' }}" id="booking-feature-label-{{ $feature->id }}">
                <input type="checkbox" name="booking_features[]" value="{{ $feature->id }}"
                       id="booking_feature_cb_{{ $feature->id }}"
                       class="booking-feature-cb"
                       data-name="{{ $feature->name }}"
                       data-id="{{ $feature->id }}"
                       {{ $checked ? 'checked' : '' }}
                       onchange="onBookingFeatureChange(this)" />
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
              <span class="amenity-selected-count" id="bookingFeatureSelectedCount">0 selected</span>
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
            $policyValues = isset($hotel) ? $hotel->policyValues->keyBy('hotel_policy_id') : collect();
          @endphp
          @foreach($hotelPolicies as $policy)
          @php
            $val = isset($policyValues[$policy->id]) ? $policyValues[$policy->id]->value : '';
          @endphp
          <div class="col-md-6">
            <label class="form-label" for="policy_{{ $policy->id }}">{{ $policy->name }}</label>
            @if($policy->input_type === 'textarea')
              <textarea class="form-control" name="hotel_policies[{{ $policy->id }}]" id="policy_{{ $policy->id }}" rows="2">{{ old('hotel_policies.'.$policy->id, $val) }}</textarea>
            @else
              <input type="text" class="form-control" name="hotel_policies[{{ $policy->id }}]" id="policy_{{ $policy->id }}" value="{{ old('hotel_policies.'.$policy->id, $val) }}" />
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
    @if(isset($hotel) && $hotel->featured_image)
      <div class="mb-3" id="featured-preview-existing">
        <label class="form-label text-muted small">Current Featured Image</label>
        <div>
          <img src="{{ asset($hotel->featured_image) }}" alt="{{ $hotel->featured_image_alt ?? 'Featured Image' }}" class="img-thumbnail" style="max-height:220px;" />
        </div>
      </div>
    @endif
    <div class="mb-3" id="featured-preview-new" style="display:none;">
      <label class="form-label text-muted small">New/Preview Image</label>
      <img id="featured-preview-img" src="" alt="Preview" class="img-thumbnail" style="max-height:220px;" />
    </div>
    <div class="mb-3">
      <label class="form-label" for="featured_image_alt">Image Alt Text (SEO)</label>
      <input type="text" class="form-control" id="featured_image_alt" name="featured_image_alt" value="{{ old('featured_image_alt', isset($hotel) ? $hotel->featured_image_alt : '') }}" />
      <div class="form-text">Describe the image clearly for search engines and accessibility.</div>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold" for="video_url">Video URL</label>
      <input type="url" class="form-control" id="video_url" name="video_url" value="{{ old('video', (isset($hotel) && str_starts_with($hotel->video ?? '', 'http')) ? $hotel->video : '') }}" />
      <div class="form-text">Paste a YouTube link directly instead of uploading a video file.</div>
    </div>
  </div>

  <!-- Tab 4: Gallery -->
  <div class="tab-pane fade" id="gallery-pane" role="tabpanel" aria-labelledby="gallery-tab">
    
    <!-- Existing Gallery Images -->
    @if(isset($hotel) && ($hotel->images->count() > 0 || !empty($hotel->video)))
    <div class="mb-4">
      <label class="form-label fw-semibold">Current Gallery & Video</label>
      <div class="row g-3" id="existing-gallery-grid">
        @if(!empty($hotel->video) && !str_starts_with($hotel->video, 'http'))
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
                     value="{{ old('existing_gallery_alts.'.$img->id, $img->alt_text) }}"
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
      <div class="form-text mt-2 text-danger">Check the trash icon on images to mark them for deletion. They will be removed when you click Update/Save.</div>
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
      <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="e.g. Best Hotel in Michigan | Michigan Explorer" value="{{ old('meta_title', isset($hotel) && $hotel->seo ? $hotel->seo->meta_title : '') }}" />
    </div>
    <div class="mb-3">
      <label class="form-label" for="meta_description">Meta Description <span class="text-muted small ms-2 fw-normal" id="meta_desc_count">(0 / 160)</span></label>
      <textarea class="form-control" id="meta_description" name="meta_description" placeholder="e.g. Discover the best hotel in Michigan..." maxlength="160" rows="2">{{ old('meta_description', isset($hotel) && $hotel->seo ? $hotel->seo->meta_description : '') }}</textarea>
    </div>
    <div class="mb-3">
      <label class="form-label" for="og_title">OG Title</label>
      <input type="text" class="form-control" id="og_title" name="og_title" placeholder="e.g. Best Hotel in Michigan" value="{{ old('og_title', isset($hotel) && $hotel->seo ? $hotel->seo->og_title : '') }}" />
    </div>
    <div class="mb-3">
      <label class="form-label" for="og_description">OG Description <span class="text-muted small ms-2 fw-normal" id="og_desc_count">(0 / 160)</span></label>
      <textarea class="form-control" id="og_description" name="og_description" placeholder="e.g. Discover the best hotel in Michigan..." maxlength="160" rows="2">{{ old('og_description', isset($hotel) && $hotel->seo ? $hotel->seo->og_description : '') }}</textarea>
    </div>
    <div class="mb-3">
      <label class="form-label" for="schema_markup">Schema Markup (JSON-LD) - <span class="text-info">Auto-generated</span></label>
      <textarea class="form-control" id="schema_markup" name="schema_markup" rows="8" placeholder="Auto-generated on save" readonly disabled>{{ old('schema_markup', isset($hotel) && $hotel->seo ? $hotel->seo->schema_markup : '') }}</textarea>
    </div>
  </div>

  <!-- Tab 6: FAQs -->
  <div class="tab-pane fade" id="faqs-pane" role="tabpanel" aria-labelledby="faqs-tab">
    <div id="faqs-container">
      @if(isset($hotel))
        @foreach($hotel->faqs as $index => $faq)
        <div class="card mb-3 faq-item border-info" id="faq_{{ $index }}">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="card-title mb-0 text-info fw-bold"><i class="fas fa-question-circle me-1"></i> Existing FAQ</h6>
              <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFaq('faq_{{ $index }}')"><i class="fas fa-trash me-1"></i> Remove</button>
            </div>
            <!-- Hidden ID field for existing FAQs -->
            <input type="hidden" name="faqs[{{ $index }}][id]" value="{{ $faq->id }}">
            <div class="mb-3">
              <label class="form-label fw-semibold">Question</label>
              <input type="text" class="form-control" name="faqs[{{ $index }}][question]" value="{{ old('faqs.'.$index.'.question', $faq->question) }}" required>
            </div>
            <div class="mb-2">
              <label class="form-label fw-semibold">Answer</label>
              <textarea class="form-control tinymce" id="faq_answer_{{ $index }}" name="faqs[{{ $index }}][answer]">{{ old('faqs.'.$index.'.answer', $faq->answer) }}</textarea>
            </div>
          </div>
        </div>
        @endforeach
      @endif
    </div>
    <button type="button" class="btn btn-outline-primary mt-3" onclick="addFaq()">+ Add FAQ</button>
  </div>
</div>

<div class="mt-4 pt-3 border-top">
  <button type="submit" class="btn btn-primary">Save</button>
  <a href="{{ route('hotels.index') }}" class="btn btn-secondary">Cancel</a>
</div>

{{-- ===== Add Category Modal ===== --}}
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

{{-- ===== Add Amenity Modal ===== --}}
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
@section('page-style')
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

  .amenity-dropdown-wrapper { position: relative; }
  .amenity-dropdown-trigger {
    display: flex; align-items: center; justify-content: space-between;
    min-height: 48px; padding: 8px 14px; border: 1.5px solid #d5d9e0;
    border-radius: 8px; background: #fff; cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s; gap: 10px; user-select: none;
  }
  .amenity-dropdown-trigger:hover { border-color: #7367f0; }
  .amenity-dropdown-trigger.open { border-color: #7367f0; box-shadow: 0 0 0 3px rgba(115,103,240,.15); }
  .amenity-tags-area { display: flex; flex-wrap: wrap; gap: 6px; flex: 1; align-items: center; min-height: 28px; }
  .amenity-placeholder { color: #9ea5b1; font-size: .92rem; display: flex; align-items: center; }
  .amenity-tag {
    display: inline-flex; align-items: center; gap: 5px;
    background: #ede9ff; color: #5a50d6; border-radius: 20px;
    padding: 3px 10px 3px 8px; font-size: .8rem; font-weight: 600; white-space: nowrap;
  }
  .amenity-tag .tag-remove { cursor: pointer; color: #8b82e0; font-size: .75rem; margin-left: 2px; transition: color .15s; }
  .amenity-tag .tag-remove:hover { color: #dc3545; }
  .amenity-dropdown-arrow { font-size: .8rem; color: #9ea5b1; transition: transform .25s; flex-shrink: 0; }
  .amenity-dropdown-arrow.rotated { transform: rotate(180deg); }
  .amenity-dropdown-panel {
    position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: #fff; border: 1.5px solid #d5d9e0; border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,.12); z-index: 1055; overflow: hidden;
    animation: dropdownFade .18s ease;
  }
  @keyframes dropdownFade { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }
  .amenity-search-wrap { display: flex; align-items: center; padding: 10px 14px; gap: 10px; background: #f8f7ff; }
  .amenity-search-icon { color: #9ea5b1; font-size: .9rem; flex-shrink: 0; }
  .amenity-search-input { border: none; outline: none; background: transparent; font-size: .9rem; width: 100%; color: #3a3a3a; }
  .amenity-search-input::placeholder { color: #b0b8c9; }
  .amenity-divider { height: 1px; background: #eeedf5; }
  .amenity-items-list { max-height: 240px; overflow-y: auto; padding: 6px 0; }
  .amenity-items-list::-webkit-scrollbar { width: 4px; }
  .amenity-items-list::-webkit-scrollbar-thumb { background: #d5d9e0; border-radius: 4px; }
  .amenity-item {
    display: flex; align-items: center; gap: 10px; padding: 9px 16px;
    cursor: pointer; margin: 0; font-weight: 400; transition: background .13s;
  }
  .amenity-item:hover { background: #f4f2ff; }
  .amenity-item--checked { background: #ede9ff; }
  .amenity-item--checked:hover { background: #e4dfff; }
  .amenity-item input[type="checkbox"] { display: none; }
  .amenity-item-icon {
    width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;
    border-radius: 6px; background: #f0eeff; color: #7367f0; font-size: .85rem; flex-shrink: 0;
  }
  .amenity-item--checked .amenity-item-icon { background: #7367f0; color: #fff; }
  .amenity-item-name { flex: 1; font-size: .9rem; color: #3a3a3a; }
  .amenity-item--checked .amenity-item-name { color: #5a50d6; font-weight: 600; }
  .amenity-item-check { font-size: .8rem; color: #7367f0; display: none; }
  .amenity-item--checked .amenity-item-check { display: block; }
  .amenity-no-results { padding: 16px; text-align: center; color: #9ea5b1; font-size: .88rem; }
  .amenity-panel-footer { display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; background: #f8f7ff; }
  .amenity-selected-count { font-size: .8rem; color: #7367f0; font-weight: 700; background: #ede9ff; border-radius: 20px; padding: 2px 10px; }

  .icon-option:hover {
      background-color: #f8f9fa;
      border-color: #4f46e5 !important;
  }
  .icon-picker-grid {
      scrollbar-width: thin;
  }
</style>
@endsection
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
          <span id="saveCategoryBtnSpinner" class="d-none"><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ===== Add Booking Feature Modal ===== --}}
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

{{-- ===== Add Hotel Policy Modal ===== --}}
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

@section('page-script')
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

  let faqIndex = {{ isset($hotel) ? 'Date.now()' : '0' }};
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

  function toggleVideoDeleteOverlay(cb) {
    const overlay = document.getElementById('overlay-video');
    if (overlay) {
      if (cb.checked) { overlay.classList.remove('d-none'); overlay.classList.add('d-flex'); }
      else { overlay.classList.remove('d-flex'); overlay.classList.add('d-none'); }
    }
  }

  function toggleDeleteOverlay(cb, id) {
    const overlay = document.getElementById('overlay-' + id);
    if (overlay) {
      if (cb.checked) { overlay.classList.remove('d-none'); overlay.classList.add('d-flex'); }
      else { overlay.classList.remove('d-flex'); overlay.classList.add('d-none'); }
    }
  }
</script>

<script>
  let amenityDropdownOpen = false;

  function toggleAmenityDropdown() { amenityDropdownOpen ? closeAmenityDropdown() : openAmenityDropdown(); }

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

  document.addEventListener('click', function(e) {
    const w = document.getElementById('amenityDropdownWrapper');
    if (w && !w.contains(e.target)) closeAmenityDropdown();
  });

  function onAmenityChange(cb) {
    const label = document.getElementById('amenity-label-' + cb.dataset.id);
    if (label) {
      cb.checked ? label.classList.add('amenity-item--checked') : label.classList.remove('amenity-item--checked');
    }
    renderAmenityTags();
  }

  function renderAmenityTags() {
    const cbs = document.querySelectorAll('.amenity-cb:checked');
    const tagsArea = document.getElementById('amenityTagsArea');
    const placeholder = document.getElementById('amenityPlaceholder');
    const countEl = document.getElementById('amenitySelectedCount');
    if (!tagsArea) return;
    tagsArea.querySelectorAll('.amenity-tag').forEach(t => t.remove());
    if (cbs.length === 0) {
      if (placeholder) {
        tagsArea.appendChild(placeholder); placeholder.style.display = 'flex';
      }
      if (countEl) countEl.textContent = '0 selected'; return;
    }
    if (placeholder) placeholder.style.display = 'none';
    if (countEl) countEl.textContent = cbs.length + ' selected';
    cbs.forEach(cb => {
      const tag = document.createElement('span');
      tag.className = 'amenity-tag';
      tag.innerHTML = `${cb.dataset.name} <span class="tag-remove" onclick="removeAmenityTag(event,'${cb.dataset.id}')"><i class="fas fa-times"></i></span>`;
      tagsArea.appendChild(tag);
    });
  }

  function removeAmenityTag(e, id) {
    e.stopPropagation();
    const cb = document.getElementById('amenity_cb_' + id);
    if (cb) { cb.checked = false; cb.dispatchEvent(new Event('change')); }
  }

  function filterAmenities(query) {
    const q = query.toLowerCase().trim();
    let visible = 0;
    document.querySelectorAll('.amenity-item').forEach(item => {
      const match = item.querySelector('.amenity-item-name').textContent.toLowerCase().includes(q);
      item.style.display = match ? 'flex' : 'none';
      if (match) visible++;
    });
    const noR = document.getElementById('amenityNoResults');
    if (noR) {
      visible === 0 ? noR.classList.remove('d-none') : noR.classList.add('d-none');
    }
  }

  function previewFeaturedImage(event) {
    const file = event.target.files[0]; if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('featured-preview-img').src = e.target.result;
      document.getElementById('featured-preview-new').style.display = 'block';
      const existing = document.getElementById('featured-preview-existing');
      if (existing) existing.style.display = 'none';
    };
    reader.readAsDataURL(file);
  }

  function updateAmenityIconPreview(val) { document.getElementById('amenity-icon-preview').className = 'fas ' + val.trim(); }

  function saveNewAmenity() {
    const name = document.getElementById('new_amenity_name').value.trim();
    const icon = document.getElementById('new_amenity_icon').value.trim();
    const alertBox = document.getElementById('amenity-modal-alert');
    if (!name) { alertBox.className = 'alert alert-danger'; alertBox.textContent = 'Please enter an amenity name.'; return; }
    alertBox.className = 'd-none';
    document.getElementById('saveAmenityBtnText').classList.add('d-none');
    const spinner = document.getElementById('saveCategoryBtnSpinner');
    if (spinner) spinner.classList.remove('d-none');
    $.ajax({
      url: '{{ route("amenities.store") }}', type: 'POST',
      data: { _token: '{{ csrf_token() }}', name, icon: icon || 'fa-star', status: 1 },
      success: function(response) {
        if (response.success) {
          const a = response.amenity;
          const list = document.getElementById('amenityItemsList');
          const noResults = document.getElementById('amenityNoResults');
          const newLabel = document.createElement('label');
          newLabel.className = 'amenity-item amenity-item--checked';
          newLabel.id = 'amenity-label-' + a.id;
          newLabel.innerHTML = `<input type="checkbox" name="amenities[]" value="${a.id}" id="amenity_cb_${a.id}" class="amenity-cb" data-name="${a.name}" data-id="${a.id}" checked onchange="onAmenityChange(this)" /><span class="amenity-item-icon"><i class="fas ${a.icon || 'fa-star'}"></i></span><span class="amenity-item-name">${a.name}</span><span class="amenity-item-check"><i class="fas fa-check"></i></span>`;
          list.insertBefore(newLabel, noResults);
          renderAmenityTags();
          document.getElementById('new_amenity_name').value = '';
          document.getElementById('new_amenity_icon').value = 'fa-star';
          const pickerText = document.getElementById('amenity_selected_icon_text');
          if (pickerText) pickerText.textContent = 'fas fa-star';
          const pickerDisplay = document.getElementById('amenity_selected_icon_display');
          if (pickerDisplay) pickerDisplay.className = 'fas fa-star me-2';
          bootstrap.Modal.getInstance(document.getElementById('addAmenityModal')).hide();
        } else { alertBox.className = 'alert alert-danger'; alertBox.textContent = response.message || 'Failed.'; }
      },
      error: function(xhr) {
        alertBox.className = 'alert alert-danger';
        const errors = xhr.responseJSON?.errors;
        alertBox.textContent = errors ? Object.values(errors).flat().join(' ') : 'An error occurred.';
      },
      complete: function() {
        document.getElementById('saveAmenityBtnText').classList.remove('d-none');
        if (spinner) spinner.classList.add('d-none');
      }
    });
  }

  function previewGalleryImages(event) {
    const files = Array.from(event.target.files);
    const previewGrid = document.getElementById('gallery-new-preview');
    const altFields = document.getElementById('gallery-alt-fields');
    previewGrid.innerHTML = ''; altFields.innerHTML = '';
    files.forEach((file, index) => {
      const reader = new FileReader();
      reader.onload = e => {
        const col = document.createElement('div'); col.className = 'col-md-3 col-sm-4 col-6';
        col.innerHTML = `<div class="card border"><img src="${e.target.result}" class="card-img-top" style="height:140px;object-fit:cover;" /><div class="card-body p-2"><small class="text-muted">Image ${index+1}</small></div></div>`;
        previewGrid.appendChild(col);
      };
      reader.readAsDataURL(file);
      const altWrap = document.createElement('div'); altWrap.className = 'mb-2';
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

  document.addEventListener('DOMContentLoaded', () => renderAmenityTags());
</script>

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

    const categoryTrigger = document.getElementById('categoryTrigger');
    if (categoryTrigger) {
      categoryTrigger.style.borderColor = '';
    }
    const categoryError = document.getElementById('category-error-msg');
    if (categoryError) {
      categoryError.classList.add('d-none');
    }
  }

  function toggleCityDropdown() {
    const panel  = document.getElementById('cityDropdownPanel');
    const arrow  = document.getElementById('cityArrow');
    const isOpen = panel.style.display !== 'none';
    panel.style.display = isOpen ? 'none' : 'block';
    arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    if (!isOpen) document.getElementById('citySearchInput').focus();
  }
  function closeCityDropdown() {
    const panel = document.getElementById('cityDropdownPanel');
    if(panel) { panel.style.display = 'none'; document.getElementById('cityArrow').style.transform = 'rotate(0deg)'; }
  }
  function filterCitiesList(val) {
    const term  = val.toLowerCase();
    const items = document.querySelectorAll('#cityItemsList .cuisine-item');
    let   found = 0;
    items.forEach(item => {
      const name = item.querySelector('.cuisine-item-name').textContent.toLowerCase();
      const show = name.includes(term);
      item.style.display = show ? '' : 'none';
      if (show) found++;
    });
    document.getElementById('cityNoResults').classList.toggle('d-none', found > 0);
  }
  function onCityChange(rb) {
    const name  = rb.dataset.name;
    const hidden= document.getElementById('city');
    const tags  = document.getElementById('cityTagsArea');
    const ph    = document.getElementById('cityPlaceholder');

    hidden.value = name;
    document.querySelectorAll('#cityItemsList .cuisine-item').forEach(l => l.classList.remove('selected'));
    const label = rb.closest('.cuisine-item');
    if(label) label.classList.add('selected');

    ph.style.display = 'none';
    let existing = tags.querySelector('.city-selected-text');
    if (!existing) {
      existing = document.createElement('span');
      existing.className = 'city-selected-text';
      existing.style.cssText = 'font-size:0.9rem; font-weight:500; color:#333;';
      tags.insertBefore(existing, ph);
    }
    existing.textContent = name;
    closeCityDropdown();
  }

  window.toggleCityDropdown = toggleCityDropdown;
  window.closeCityDropdown = closeCityDropdown;
  window.filterCitiesList = filterCitiesList;
  window.onCityChange = onCityChange;

  document.addEventListener('DOMContentLoaded', function() {
    const preselectedCat = document.getElementById('hotel_category_id').value;
    if (preselectedCat) {
      const rb = document.getElementById('cat_rb_' + preselectedCat);
      if (rb) { rb.checked = true; onCategoryChange(rb); }
    }
    const preselectedCity = document.getElementById('city').value;
    if (preselectedCity) {
      const rb = Array.from(document.querySelectorAll('#cityItemsList .city-rb')).find(r => r.dataset.name === preselectedCity);
      if (rb) { rb.checked = true; onCityChange(rb); }
    }
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
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
    document.querySelectorAll('.amenity-icon-option').forEach(opt => {
        opt.addEventListener('click', function() {
            const iconName = this.getAttribute('data-icon');
            document.getElementById('new_amenity_icon').value = iconName;
            document.getElementById('amenity_selected_icon_display').className = iconName + ' me-2';
            document.getElementById('amenity_selected_icon_text').textContent = iconName;
        });
    });

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
    document.querySelectorAll('.bf-icon-option').forEach(opt => {
        opt.addEventListener('click', function() {
            const iconName = this.getAttribute('data-icon');
            document.getElementById('new_bf_icon').value = iconName;
            document.getElementById('bf_selected_icon_display').className = iconName + ' me-2';
            document.getElementById('bf_selected_icon_text').textContent = iconName;
        });
    });
  });

  let bookingFeatureDropdownOpen = false;
  function toggleBookingFeatureDropdown() { bookingFeatureDropdownOpen ? closeBookingFeatureDropdown() : openBookingFeatureDropdown(); }
  function openBookingFeatureDropdown() {
    document.getElementById('bookingFeatureDropdownPanel').style.display = 'block';
    document.getElementById('bookingFeatureTrigger').classList.add('open');
    document.getElementById('bookingFeatureArrow').classList.add('rotated');
    document.getElementById('bookingFeatureSearchInput').focus();
    bookingFeatureDropdownOpen = true;
  }
  function closeBookingFeatureDropdown() {
    document.getElementById('bookingFeatureDropdownPanel').style.display = 'none';
    document.getElementById('bookingFeatureTrigger').classList.remove('open');
    document.getElementById('bookingFeatureArrow').classList.remove('rotated');
    document.getElementById('bookingFeatureSearchInput').value = '';
    filterBookingFeatures('');
    bookingFeatureDropdownOpen = false;
  }
  document.addEventListener('click', function(e) {
    const w = document.getElementById('bookingFeatureDropdownWrapper');
    if (w && !w.contains(e.target)) closeBookingFeatureDropdown();
  });
  function onBookingFeatureChange(cb) {
    const label = document.getElementById('booking-feature-label-' + cb.dataset.id);
    if(label) { cb.checked ? label.classList.add('amenity-item--checked') : label.classList.remove('amenity-item--checked'); }
    renderBookingFeatureTags();
  }
  function renderBookingFeatureTags() {
    const cbs = document.querySelectorAll('.booking-feature-cb:checked');
    const tagsArea = document.getElementById('bookingFeatureTagsArea');
    const placeholder = document.getElementById('bookingFeaturePlaceholder');
    const countEl = document.getElementById('bookingFeatureSelectedCount');
    if (!tagsArea) return;
    const oldTags = tagsArea.querySelectorAll('.amenity-tag');
    oldTags.forEach(t => t.remove());

    if (cbs.length === 0) {
      if (placeholder) placeholder.style.display = 'flex';
      if (countEl) countEl.textContent = '0 selected';
      return;
    }
    if (placeholder) placeholder.style.display = 'none';
    cbs.forEach(cb => {
      const tag = document.createElement('span');
      tag.className = 'amenity-tag';
      tag.innerHTML = cb.dataset.name + ' <span class="tag-remove" onclick="removeBookingFeatureTag(event,\'' + cb.dataset.id + '\')"><i class="fas fa-times"></i></span>';
      tagsArea.appendChild(tag);
    });
    if (countEl) countEl.textContent = cbs.length + ' selected';
  }
  function filterBookingFeatures(val) {
    const term = val.toLowerCase();
    const items = document.querySelectorAll('#bookingFeatureItemsList .amenity-item');
    let found = 0;
    items.forEach(item => {
      const txt = item.querySelector('.amenity-item-name').textContent.toLowerCase();
      if (txt.includes(term)) { item.style.display = 'flex'; found++; }
      else { item.style.display = 'none'; }
    });
    const noResults = document.getElementById('bookingFeatureNoResults');
    if (noResults) noResults.classList.toggle('d-none', found > 0);
  }

  function removeBookingFeatureTag(e, id) {
    e.stopPropagation();
    const cb = document.getElementById('booking_feature_cb_' + id);
    if (cb) { cb.checked = false; cb.dispatchEvent(new Event('change')); }
  }

  function updateBookingFeatureIconPreview(val) { document.getElementById('booking-feature-icon-preview').className = 'fas ' + val.trim(); }

  function saveNewBookingFeature() {
    const name = document.getElementById('new_bf_name').value.trim();
    const icon = document.getElementById('new_bf_icon').value.trim();
    const alertBox = document.getElementById('bf-modal-alert');
    if (!name) { alertBox.className = 'alert alert-danger'; alertBox.textContent = 'Please enter a name.'; return; }
    alertBox.className = 'd-none';
    document.getElementById('saveBfBtnText').classList.add('d-none');
    const spinner = document.getElementById('saveBfBtnSpinner');
    if (spinner) spinner.classList.remove('d-none');
    $.ajax({
      url: '{{ route("booking-features.store") }}', type: 'POST',
      data: { _token: '{{ csrf_token() }}', name, icon: icon || 'fa-check', status: 1 },
      success: function(response) {
        if (response.success) {
          const a = response.booking_feature;
          const list = document.getElementById('bookingFeatureItemsList');
          const noResults = document.getElementById('bookingFeatureNoResults');
          const newLabel = document.createElement('label');
          newLabel.className = 'amenity-item amenity-item--checked';
          newLabel.id = 'booking-feature-label-' + a.id;
          newLabel.innerHTML = '<input type="checkbox" name="booking_features[]" value="'+a.id+'" id="booking_feature_cb_'+a.id+'" class="booking-feature-cb" data-name="'+a.name+'" data-id="'+a.id+'" checked onchange="onBookingFeatureChange(this)" /><span class="amenity-item-icon"><i class="fas '+(a.icon || 'fa-check')+'"></i></span><span class="amenity-item-name">'+a.name+'</span><span class="amenity-item-check"><i class="fas fa-check"></i></span>';
          list.insertBefore(newLabel, noResults);
          renderBookingFeatureTags();
          document.getElementById('new_bf_name').value = '';
          document.getElementById('new_bf_icon').value = 'fa-check';
          const pickerText = document.getElementById('bf_selected_icon_text');
          if (pickerText) pickerText.textContent = 'fas fa-check';
          const pickerDisplay = document.getElementById('bf_selected_icon_display');
          if (pickerDisplay) pickerDisplay.className = 'fas fa-check me-2';
          bootstrap.Modal.getInstance(document.getElementById('addBookingFeatureModal')).hide();
        } else { alertBox.className = 'alert alert-danger'; alertBox.textContent = response.message || 'Failed.'; }
      },
      error: function(xhr) {
        alertBox.className = 'alert alert-danger';
        const errors = xhr.responseJSON?.errors;
        alertBox.textContent = errors ? Object.values(errors).flat().join(' ') : 'An error occurred.';
      },
      complete: function() {
        document.getElementById('saveBfBtnText').classList.remove('d-none');
        if (spinner) spinner.classList.add('d-none');
      }
    });
  }

  function saveNewHotelPolicy() {
    const name = document.getElementById('new_policy_name').value.trim();
    const type = document.getElementById('new_policy_type').value;
    const alertBox = document.getElementById('policy-modal-alert');
    if (!name) { alertBox.className = 'alert alert-danger'; alertBox.textContent = 'Please enter a policy name.'; return; }
    alertBox.className = 'd-none';
    document.getElementById('savePolicyBtnText').classList.add('d-none');
    const spinner = document.getElementById('savePolicyBtnSpinner');
    if (spinner) spinner.classList.remove('d-none');
    $.ajax({
      url: '{{ route("hotel-policies.store") }}', type: 'POST',
      data: { _token: '{{ csrf_token() }}', name, input_type: type, status: 1 },
      success: function(response) {
        if (response.success) {
          const p = response.hotel_policy;
          const container = document.querySelector('.row.g-3');
          const div = document.createElement('div');
          div.className = 'col-md-6';
          let inputHtml = type === 'textarea' ? '<textarea class="form-control" name="hotel_policies['+p.id+']" id="policy_'+p.id+'" rows="2"></textarea>' : '<input type="text" class="form-control" name="hotel_policies['+p.id+']" id="policy_'+p.id+'" />';
          div.innerHTML = '<label class="form-label" for="policy_'+p.id+'">'+p.name+'</label>' + inputHtml;
          if (container) container.appendChild(div);
          
          document.getElementById('new_policy_name').value = '';
          bootstrap.Modal.getInstance(document.getElementById('addHotelPolicyModal')).hide();
        } else { alertBox.className = 'alert alert-danger'; alertBox.textContent = response.message || 'Failed.'; }
      },
      error: function(xhr) {
        alertBox.className = 'alert alert-danger';
        const errors = xhr.responseJSON?.errors;
        alertBox.textContent = errors ? Object.values(errors).flat().join(' ') : 'An error occurred.';
      },
      complete: function() {
        document.getElementById('savePolicyBtnText').classList.remove('d-none');
        if (spinner) spinner.classList.add('d-none');
      }
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelectorAll('.booking-feature-cb').length > 0) {
      renderBookingFeatureTags();
    }
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
          validateBasicInfo(); // Validate to show errors on Basic Info tab, but allow navigation
        }
      });
    });

    // Form submission validation
    const form = document.getElementById('hotelCreateForm') || document.getElementById('hotelEditForm');
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

{{-- ===== Add Affiliate Link Modal ===== --}}
<div class="modal fade" id="addAffiliateLinkModal" tabindex="-1" aria-labelledby="addAffiliateLinkModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addAffiliateLinkModalLabel"><i class="fas fa-plus-circle me-2 text-primary"></i>Add New Affiliate Link</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="afflink-modal-alert" class="d-none"></div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_afflink_name">Link Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="new_afflink_name" placeholder="e.g. Booking.com - Grand Hotel" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_afflink_provider">Provider</label>
          <input type="text" class="form-control" id="new_afflink_provider" placeholder="e.g. Booking.com, Expedia" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_afflink_url">Affiliate URL <span class="text-danger">*</span></label>
          <input type="url" class="form-control" id="new_afflink_url" placeholder="https://www.booking.com/hotel/..." />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveNewAffiliateLink()">
          <span id="saveAfflinkBtnText"><i class="fas fa-plus me-1"></i>Add Link</span>
          <span id="saveAfflinkBtnSpinner" class="d-none"><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// ===== Affiliate Link Custom Dropdown =====
function toggleAffiliateLinkDropdown() {
  const panel = document.getElementById('affiliateLinkDropdownPanel');
  const arrow = document.getElementById('affiliateLinkArrow');
  const isOpen = panel.style.display !== 'none';
  // Close all other dropdowns
  document.querySelectorAll('.amenity-dropdown-panel').forEach(p => {
    if (p !== panel) { p.style.display = 'none'; }
  });
  document.querySelectorAll('.amenity-dropdown-arrow').forEach(a => {
    if (a !== arrow) { a.classList.remove('open'); }
  });
  panel.style.display = isOpen ? 'none' : 'block';
  arrow.classList.toggle('open', !isOpen);
  if (!isOpen) { document.getElementById('affiliateLinkSearchInput').focus(); }
}

function closeAffiliateLinkDropdown() {
  document.getElementById('affiliateLinkDropdownPanel').style.display = 'none';
  document.getElementById('affiliateLinkArrow').classList.remove('open');
}

function onAffiliateLinkChange(radio) {
  // Uncheck all visual labels
  document.querySelectorAll('#affiliateLinkItemsList .amenity-item').forEach(l => l.classList.remove('amenity-item--checked'));
  // Check clicked label
  const lbl = document.getElementById('afflink-label-' + (radio.value || '0'));
  if (lbl) lbl.classList.add('amenity-item--checked');
  // Update hidden input
  document.getElementById('affiliate_link_id_input').value = radio.value;
  // Update tag display
  renderAffiliateLinkTag(radio.dataset.name, radio.value);
  // Close dropdown
  closeAffiliateLinkDropdown();
}

function renderAffiliateLinkTag(name, value) {
  const area = document.getElementById('affiliateLinkTagsArea');
  const placeholder = document.getElementById('affiliateLinkPlaceholder');
  if (!value) {
    area.innerHTML = '';
    area.appendChild(placeholder);
    placeholder.style.display = '';
    return;
  }
  placeholder.style.display = 'none';
  area.innerHTML = '';
  const tag = document.createElement('span');
  tag.className = 'amenity-tag';
  tag.innerHTML = `<i class="fas fa-link me-1"></i>${name} <span class="amenity-tag-remove" onclick="clearAffiliateLink(event)">×</span>`;
  area.appendChild(tag);
  area.appendChild(placeholder);
}

function clearAffiliateLink(e) {
  e.stopPropagation();
  // Select None
  const noneRb = document.getElementById('afflink_rb_0');
  if (noneRb) { noneRb.checked = true; onAffiliateLinkChange(noneRb); }
}

function filterAffiliateLinks(query) {
  const q = query.toLowerCase();
  const items = document.querySelectorAll('#affiliateLinkItemsList .amenity-item');
  let found = 0;
  items.forEach(item => {
    const name = item.querySelector('.amenity-item-name')?.textContent.toLowerCase() || '';
    const match = name.includes(q);
    item.style.display = match ? '' : 'none';
    if (match) found++;
  });
  const noResults = document.getElementById('affiliateLinkNoResults');
  noResults.classList.toggle('d-none', found > 0);
}

function saveNewAffiliateLink() {
  const name = document.getElementById('new_afflink_name').value.trim();
  const provider = document.getElementById('new_afflink_provider').value.trim();
  const link = document.getElementById('new_afflink_url').value.trim();
  const alertBox = document.getElementById('afflink-modal-alert');
  if (!name) { alertBox.className = 'alert alert-danger'; alertBox.textContent = 'Please enter a link name.'; return; }
  if (!link) { alertBox.className = 'alert alert-danger'; alertBox.textContent = 'Please enter the affiliate URL.'; return; }
  alertBox.className = 'd-none';
  document.getElementById('saveAfflinkBtnText').classList.add('d-none');
  document.getElementById('saveAfflinkBtnSpinner').classList.remove('d-none');
  $.ajax({
    url: '{{ route("affiliate-links.store") }}',
    type: 'POST',
    data: { _token: '{{ csrf_token() }}', name, provider, link, is_active: 1 },
    success: function(response) {
      if (response.success) {
        const a = response.affiliateLink;
        const list = document.getElementById('affiliateLinkItemsList');
        const noResults = document.getElementById('affiliateLinkNoResults');
        const newLabel = document.createElement('label');
        newLabel.className = 'amenity-item amenity-item--checked';
        newLabel.id = 'afflink-label-' + a.id;
        newLabel.innerHTML = `<input type="radio" name="_affiliate_link_radio" value="${a.id}" id="afflink_rb_${a.id}" class="afflink-rb d-none" data-name="${a.name}${a.provider ? ' (' + a.provider + ')' : ''}" checked onchange="onAffiliateLinkChange(this)" /><span class="amenity-item-icon"><i class="fas fa-link"></i></span><span class="amenity-item-name">${a.name}${a.provider ? '<small class=\'text-muted ms-1\'>(' + a.provider + ')</small>' : ''}</span><span class="amenity-item-check"><i class="fas fa-check"></i></span>`;
        list.insertBefore(newLabel, noResults);
        // Select the new link
        document.getElementById('affiliate_link_id_input').value = a.id;
        renderAffiliateLinkTag(a.name + (a.provider ? ' (' + a.provider + ')' : ''), a.id);
        // Uncheck all others
        document.querySelectorAll('#affiliateLinkItemsList .amenity-item').forEach(l => l.classList.remove('amenity-item--checked'));
        newLabel.classList.add('amenity-item--checked');
        // Reset form
        document.getElementById('new_afflink_name').value = '';
        document.getElementById('new_afflink_provider').value = '';
        document.getElementById('new_afflink_url').value = '';
        bootstrap.Modal.getInstance(document.getElementById('addAffiliateLinkModal')).hide();
      } else {
        alertBox.className = 'alert alert-danger';
        alertBox.textContent = response.message || 'Failed to save.';
      }
    },
    error: function(xhr) {
      alertBox.className = 'alert alert-danger';
      const errors = xhr.responseJSON?.errors;
      alertBox.textContent = errors ? Object.values(errors).flat().join(' ') : 'An error occurred.';
    },
    complete: function() {
      document.getElementById('saveAfflinkBtnText').classList.remove('d-none');
      document.getElementById('saveAfflinkBtnSpinner').classList.add('d-none');
    }
  });
}

// Init affiliate link tag on page load
document.addEventListener('DOMContentLoaded', function() {
  const hiddenInput = document.getElementById('affiliate_link_id_input');
  const val = hiddenInput ? hiddenInput.value : '';
  if (val) {
    const rb = document.getElementById('afflink_rb_' + val);
    if (rb) renderAffiliateLinkTag(rb.dataset.name, val);
  }
});
</script>
@endsection
