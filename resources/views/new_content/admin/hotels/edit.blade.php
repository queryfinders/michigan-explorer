@extends('layouts/layoutMaster')

@section('title', 'Edit Hotel')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Hotel</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('hotels.update', $hotel->id) }}" method="POST" enctype="multipart/form-data">
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
              <label class="form-label" for="hotel_category_id">Category <span class="text-danger">*</span></label>
              <select class="form-select @error('hotel_category_id') is-invalid @enderror" id="hotel_category_id" name="hotel_category_id" required>
                  <option value="">Select Category</option>
                  @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ (old('hotel_category_id') ?? $hotel->hotel_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                  @endforeach
              </select>
              @error('hotel_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') ?? $hotel->name }}" required />
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') ?? $hotel->slug }}" required />
              @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="short_description">Short Description</label>
            <textarea class="form-control" id="short_description" name="short_description" rows="2">{{ $hotel->short_description }}</textarea>
          </div>
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
              <input type="text" class="form-control" id="city" name="city" value="{{ $hotel->city }}" />
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="zip">Zip Code</label>
              <input type="text" class="form-control" id="zip" name="zip" value="{{ $hotel->zip }}" />
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="address">Street Address</label>
              <input type="text" class="form-control" id="address" name="address" value="{{ $hotel->address }}" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label" for="phone">Phone Number</label>
              <input type="text" class="form-control" id="phone" name="phone" value="{{ $hotel->phone }}" />
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ $hotel->email }}" />
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="website">Website URL</label>
              <input type="url" class="form-control" id="website" name="website" value="{{ $hotel->website }}" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="starting_price">Starting Price ($)</label>
              <input type="number" class="form-control" id="starting_price" name="starting_price" value="{{ $hotel->starting_price }}" placeholder="e.g. 199" />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label" for="affiliate_url">Booking Affiliate URL</label>
              <input type="url" class="form-control" id="affiliate_url" name="affiliate_url" value="{{ $hotel->affiliate_url }}" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="latitude">Latitude</label>
              <input type="text" class="form-control" id="latitude" name="latitude" value="{{ $hotel->latitude }}" placeholder="e.g. 45.8500" />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label" for="longitude">Longitude</label>
              <input type="text" class="form-control" id="longitude" name="longitude" value="{{ $hotel->longitude }}" placeholder="e.g. -84.6178" />
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
              <h6 class="fw-semibold">Booking Features</h6>
              <div class="card bg-light border-0 shadow-none">
                <div class="card-body p-3">
                  <div class="row g-3">
                    @php
                      $hotelBookingFeatureIds = $hotel->bookingFeatures->pluck('id')->toArray();
                    @endphp
                    @foreach($bookingFeatures as $feature)
                    @php
                      $bfChecked = in_array($feature->id, $hotelBookingFeatureIds);
                    @endphp
                    <div class="col-md-4 col-sm-6">
                      <div class="form-check custom-checkbox">
                        <input class="form-check-input" type="checkbox" name="booking_features[]" value="{{ $feature->id }}" id="bf_{{ $feature->id }}" {{ $bfChecked ? 'checked' : '' }}>
                        <label class="form-check-label d-flex align-items-center" for="bf_{{ $feature->id }}">
                          @if($feature->icon)
                            <i class="{{ $feature->icon }} text-primary me-2"></i>
                          @endif
                          {{ $feature->name }}
                        </label>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Hotel Policies -->
          <div class="row">
            <div class="col-12 mt-4">
              <h6 class="fw-semibold">Hotel Policies</h6>
              <div class="card bg-light border-0 shadow-none">
                <div class="card-body p-3">
                  <div class="row g-3">
                    @foreach($hotelPolicies as $policy)
                    @php 
                      $policyValue = $hotel->policyValues->where('hotel_policy_id', $policy->id)->first();
                      $currentValue = $policyValue ? $policyValue->value : '';
                    @endphp
                    <div class="col-md-6">
                      <label class="form-label" for="policy_{{ $policy->id }}">{{ $policy->name }}</label>
                      @if($policy->input_type === 'textarea')
                        <textarea class="form-control" name="hotel_policies[{{ $policy->id }}]" id="policy_{{ $policy->id }}" rows="2">{{ $currentValue }}</textarea>
                      @else
                        <input type="text" class="form-control" name="hotel_policies[{{ $policy->id }}]" id="policy_{{ $policy->id }}" value="{{ $currentValue }}" />
                      @endif
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Tab 3: Featured Image -->
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
            <input type="text" class="form-control" id="featured_image_alt" name="featured_image_alt" value="{{ $hotel->featured_image_alt }}" placeholder="e.g. Exterior view of Grand Hotel Resort in Mackinac Island" />
            <div class="form-text">Describe the image clearly for search engines and accessibility.</div>
          </div>
        </div>

        <!-- Tab 4: Gallery -->
        <div class="tab-pane fade" id="gallery-pane" role="tabpanel" aria-labelledby="gallery-tab">
          
          <!-- Existing Gallery Images -->
          @if($hotel->images->count() > 0)
          <div class="mb-4">
            <label class="form-label fw-semibold">Current Gallery ({{ $hotel->images->count() }} images)</label>
            <div class="row g-3" id="existing-gallery-grid">
              @foreach($hotel->images as $img)
              <div class="col-md-3 col-sm-4 col-6" id="gallery-item-{{ $img->id }}">
                <div class="card border position-relative">
                  <img src="{{ asset($img->image) }}" alt="{{ $img->alt_text ?? 'Gallery' }}" class="card-img-top" style="height:140px;object-fit:cover;" />
                  <div class="card-body p-2">
                    <small class="text-muted">{{ $img->alt_text ?: 'No alt text' }}</small>
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
            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ $hotel->seo->meta_title ?? '' }}" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="meta_description">Meta Description</label>
            <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ $hotel->seo->meta_description ?? '' }}</textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="canonical_url">Canonical URL</label>
              <input type="url" class="form-control" id="canonical_url" name="canonical_url" value="{{ $hotel->seo->canonical_url ?? '' }}" />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label" for="og_title">OG Title</label>
              <input type="text" class="form-control" id="og_title" name="og_title" value="{{ $hotel->seo->og_title ?? '' }}" />
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="og_description">OG Description</label>
            <textarea class="form-control" id="og_description" name="og_description" rows="2">{{ $hotel->seo->og_description ?? '' }}</textarea>
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
                  <input type="text" class="form-control" name="faqs[{{ $index }}][question]" value="{{ $faq->question }}" required placeholder="e.g. What time is check-in?">
                </div>
                <div class="mb-2">
                  <label class="form-label fw-semibold">Answer</label>
                  <textarea class="form-control tinymce-faq" id="faq_answer_{{ $index }}" name="faqs[{{ $index }}][answer]">{{ $faq->answer }}</textarea>
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
@endsection

@section('page-script')
<style>
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

    tagsArea.innerHTML = '';

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
      altWrap.innerHTML = `<label class="form-label small">Alt Text for Image ${index+1} (SEO)</label><input type="text" class="form-control form-control-sm" name="gallery_alts[${index}]" placeholder="e.g. Hotel lobby interior view" />`;
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
    tinymce.init({
      selector: 'textarea.tinymce-faq',
      plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
      toolbar_mode: 'floating',
      height: 200,
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
            <input type="text" class="form-control" name="faqs[${faqIndex}][question]" required placeholder="e.g. What time is check-in?">
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Answer</label>
            <textarea class="form-control tinymce-faq" id="faq_answer_${faqIndex}" name="faqs[${faqIndex}][answer]"></textarea>
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
          <input type="text" class="form-control" id="new_amenity_name" placeholder="e.g. Rooftop Pool" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_amenity_icon">FontAwesome Icon Class</label>
          <div class="input-group">
            <span class="input-group-text"><i id="amenity-icon-preview" class="fas fa-star"></i></span>
            <input type="text" class="form-control" id="new_amenity_icon" placeholder="e.g. fa-swimming-pool" value="fa-star" oninput="updateAmenityIconPreview(this.value)" />
          </div>
          <div class="form-text">Enter any <a href="https://fontawesome.com/icons" target="_blank">FontAwesome 5</a> icon class. e.g. <code>fa-wifi</code>, <code>fa-dumbbell</code></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveAmenityBtn" onclick="saveNewAmenity()">
          <span id="saveAmenityBtnText"><i class="fas fa-plus me-1"></i>Add Amenity</span>
          <span id="saveAmenityBtnSpinner" class="d-none"><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
        </button>
      </div>
    </div>
  </div>
</div>
@endsection
