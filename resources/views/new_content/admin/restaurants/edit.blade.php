@extends('layouts/layoutMaster')

@section('title', 'Edit Restaurant: ' . $restaurant->name)

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Restaurant: {{ $restaurant->name }}</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('restaurants.update', $restaurant->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- Tabs Navigation -->
      <ul class="nav nav-tabs mb-4" id="restaurantFormTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic-pane" type="button" role="tab" aria-controls="basic-pane" aria-selected="true">Basic Info</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-pane" type="button" role="tab" aria-controls="details-pane" aria-selected="false">Details</button>
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
      <div class="tab-content p-0" id="restaurantFormTabsContent">
        
        <!-- Tab 1: Basic Info -->
        <div class="tab-pane fade show active" id="basic-pane" role="tabpanel" aria-labelledby="basic-tab">
          <div class="row">
            <div class="col-md-4 mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label mb-0" for="restaurant_category_id">Category <span class="text-danger">*</span></label>
                <a href="{{ route('restaurant-categories.create') }}" target="_blank" class="btn btn-sm btn-link p-0 text-primary fw-semibold"><i class="fas fa-plus-circle me-1"></i>Add Category</a>
              </div>
              <select class="form-select select2 @error('restaurant_category_id') is-invalid @enderror" id="restaurant_category_id" name="restaurant_category_id" required>
                  <option value="">Select Category</option>
                  @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ old('restaurant_category_id', $restaurant->restaurant_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                  @endforeach
              </select>
              @error('restaurant_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $restaurant->name) }}" required />
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $restaurant->slug) }}" required />
              @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea class="form-control tinymce" id="description" name="description" rows="6">{{ old('description', $restaurant->description) }}</textarea>
          </div>
          <div class="mb-3">
            <div class="form-check form-switch mt-2">
              <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $restaurant->is_featured) ? 'checked' : '' }}>
              <label class="form-check-label fw-semibold" for="is_featured">Featured Restaurant (Shows on Home Page)</label>
            </div>
          </div>
        </div>

        <!-- Tab 2: Details -->
        <div class="tab-pane fade" id="details-pane" role="tabpanel" aria-labelledby="details-tab">
          <div class="row">
            {{-- Cuisines Multi-Select --}}
            <div class="col-md-6 mb-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label fw-semibold mb-0">Cuisines</label>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCuisineModal">
                  <i class="fas fa-plus me-1"></i> Add Cuisine
                </button>
              </div>
              <div class="cuisine-dropdown-wrapper" id="cuisineDropdownWrapper">
                <div class="cuisine-dropdown-trigger" id="cuisineTrigger" onclick="toggleCuisineDropdown()">
                  <div class="cuisine-tags-area" id="cuisineTagsArea">
                    <span class="cuisine-placeholder" id="cuisinePlaceholder">
                      <i class="fas fa-utensils me-2 text-muted"></i>Click to select cuisines...
                    </span>
                  </div>
                  <i class="fas fa-chevron-down cuisine-dropdown-arrow" id="cuisineArrow"></i>
                </div>
                <div class="cuisine-dropdown-panel" id="cuisineDropdownPanel" style="display:none;">
                  <div class="cuisine-search-wrap">
                    <i class="fas fa-search cuisine-search-icon"></i>
                    <input type="text" class="cuisine-search-input" id="cuisineSearchInput"
                           placeholder="Search cuisines..." oninput="filterCuisines(this.value)" autocomplete="off" />
                  </div>
                  <div class="cuisine-divider"></div>
                  <div class="cuisine-items-list" id="cuisineItemsList">
                    @foreach($cuisines as $cuisine)
                    @php $checked = $restaurant->cuisines->contains($cuisine->id); @endphp
                    <label class="cuisine-item {{ $checked ? 'cuisine-item--checked' : '' }}" id="cuisine-label-{{ $cuisine->id }}">
                      <input type="checkbox" name="cuisines[]" value="{{ $cuisine->id }}"
                             id="cuisine_cb_{{ $cuisine->id }}"
                             class="cuisine-cb"
                             data-name="{{ $cuisine->name }}"
                             data-id="{{ $cuisine->id }}"
                             {{ $checked ? 'checked' : '' }}
                             onchange="onCuisineChange(this)" />
                      <span class="cuisine-item-name">{{ $cuisine->name }}</span>
                      <span class="cuisine-item-check"><i class="fas fa-check"></i></span>
                    </label>
                    @endforeach
                    <div class="cuisine-no-results d-none" id="cuisineNoResults">
                      <i class="fas fa-search-minus me-2"></i>No cuisines found
                    </div>
                  </div>
                  <div class="cuisine-divider"></div>
                  <div class="cuisine-panel-footer">
                    <button type="button" class="btn btn-sm btn-link p-0 text-primary fw-semibold"
                            data-bs-toggle="modal" data-bs-target="#addCuisineModal" onclick="closeCuisineDropdown()">
                      <i class="fas fa-plus-circle me-1"></i>Add New Cuisine
                    </button>
                    <span class="cuisine-selected-count" id="cuisineSelectedCount">0 selected</span>
                  </div>
                </div>
              </div>
            </div>

            {{-- Features Multi-Select --}}
            <div class="col-md-6 mb-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label fw-semibold mb-0">Features</label>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addFeatureModal">
                  <i class="fas fa-plus me-1"></i> Add Feature
                </button>
              </div>
              <div class="feature-dropdown-wrapper" id="featureDropdownWrapper">
                <div class="feature-dropdown-trigger" id="featureTrigger" onclick="toggleFeatureDropdown()">
                  <div class="feature-tags-area" id="featureTagsArea">
                    <span class="feature-placeholder" id="featurePlaceholder">
                      <i class="fas fa-sparkles me-2 text-muted"></i>Click to select features...
                    </span>
                  </div>
                  <i class="fas fa-chevron-down feature-dropdown-arrow" id="featureArrow"></i>
                </div>
                <div class="feature-dropdown-panel" id="featureDropdownPanel" style="display:none;">
                  <div class="feature-search-wrap">
                    <i class="fas fa-search feature-search-icon"></i>
                    <input type="text" class="feature-search-input" id="featureSearchInput"
                           placeholder="Search features..." oninput="filterFeatures(this.value)" autocomplete="off" />
                  </div>
                  <div class="feature-divider"></div>
                  <div class="feature-items-list" id="featureItemsList">
                    @foreach($features as $feature)
                    @php $checked = $restaurant->features->contains($feature->id); @endphp
                    <label class="feature-item {{ $checked ? 'feature-item--checked' : '' }}" id="feature-label-{{ $feature->id }}">
                      <input type="checkbox" name="features[]" value="{{ $feature->id }}"
                             id="feature_cb_{{ $feature->id }}"
                             class="feature-cb"
                             data-name="{{ $feature->name }}"
                             data-id="{{ $feature->id }}"
                             {{ $checked ? 'checked' : '' }}
                             onchange="onFeatureChange(this)" />
                      <span class="feature-item-icon"><i class="{{ $feature->icon_class ?? 'fas fa-star' }}"></i></span>
                      <span class="feature-item-name">{{ $feature->name }}</span>
                      <span class="feature-item-check"><i class="fas fa-check"></i></span>
                    </label>
                    @endforeach
                    <div class="feature-no-results d-none" id="featureNoResults">
                      <i class="fas fa-search-minus me-2"></i>No features found
                    </div>
                  </div>
                  <div class="feature-divider"></div>
                  <div class="feature-panel-footer">
                    <button type="button" class="btn btn-sm btn-link p-0 text-primary fw-semibold"
                            data-bs-toggle="modal" data-bs-target="#addFeatureModal" onclick="closeFeatureDropdown()">
                      <i class="fas fa-plus-circle me-1"></i>Add New Feature
                    </button>
                    <span class="feature-selected-count" id="featureSelectedCount">0 selected</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label" for="starting_price">Average Cost ($)</label>
              <input type="number" class="form-control" id="starting_price" name="starting_price" placeholder="e.g. 45" value="{{ old('starting_price', $restaurant->starting_price) }}" />
            </div>
          </div>

          <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label fw-bold mb-0">Opening Hours</label>
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyMondayHours()">
                <i class="fas fa-copy me-1"></i>Copy Monday to All Days
              </button>
            </div>
            <div class="border rounded p-3 bg-white">
              @php
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                $oldHours = old('opening_hours', is_array($restaurant->opening_hours) ? $restaurant->opening_hours : []);
              @endphp
              
              @foreach($days as $day)
                @php
                  $dayData = $oldHours[$day] ?? ['open' => '', 'close' => '', 'closed' => false, '24_hours' => false];
                  $isClosed = !empty($dayData['closed']);
                  $is24 = !empty($dayData['24_hours']);
                  if ($isClosed && $is24) {
                      $is24 = false; // Closed wins logically if bad DB data
                  }
                  $disabled = ($isClosed || $is24) ? 'disabled' : '';
                @endphp
                <div class="row align-items-center mb-2 {{ $loop->last ? '' : 'border-bottom pb-2' }}">
                  <div class="col-md-2 fw-semibold text-capitalize">
                    {{ $day }}
                  </div>
                  <div class="col-md-3">
                    <input type="time" class="form-control time-input-{{ $day }}" name="opening_hours[{{ $day }}][open]" value="{{ $dayData['open'] ?? '' }}" {{ $disabled }}>
                  </div>
                  <div class="col-md-1 text-center text-muted small fw-bold">TO</div>
                  <div class="col-md-3">
                    <input type="time" class="form-control time-input-{{ $day }}" name="opening_hours[{{ $day }}][close]" value="{{ $dayData['close'] ?? '' }}" {{ $disabled }}>
                  </div>
                  <div class="col-md-3 d-flex gap-3">
                    <div class="form-check mt-2">
                      <input class="form-check-input closed-checkbox" type="checkbox" name="opening_hours[{{ $day }}][closed]" value="1" id="closed_{{ $day }}" data-day="{{ $day }}" {{ $isClosed ? 'checked' : '' }}>
                      <label class="form-check-label" for="closed_{{ $day }}">Closed</label>
                    </div>
                    <div class="form-check mt-2">
                      <input class="form-check-input 24h-checkbox" type="checkbox" name="opening_hours[{{ $day }}][24_hours]" value="1" id="24h_{{ $day }}" data-day="{{ $day }}" {{ $is24 ? 'checked' : '' }}>
                      <label class="form-check-label" for="24h_{{ $day }}">24 Hours</label>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label" for="city">City</label>
              <select class="form-select select2" id="city" name="city">
                <option value="">Select a city</option>
                @foreach(config('michigan_cities') as $m_city)
                  <option value="{{ $m_city }}" {{ old('city', $restaurant->city) == $m_city ? 'selected' : '' }}>{{ $m_city }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="zip">Zip Code</label>
              <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip', $restaurant->zip) }}" />
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="address">Street Address</label>
              <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $restaurant->address) }}" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label" for="phone">Phone Number</label>
              <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $restaurant->phone) }}" />
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $restaurant->email) }}" />
            </div>
            {{--
            <div class="col-md-4 mb-3">
              <label class="form-label" for="website">Website URL</label>
              <input type="url" class="form-control" id="website" name="website" value="{{ old('website', $restaurant->website) }}" />
            </div>
            --}}
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="affiliate_url">Booking Affiliate URL</label>
              <input type="url" class="form-control" id="affiliate_url" name="affiliate_url" placeholder="e.g. OpenTable or Resy link" value="{{ old('affiliate_url', $restaurant->affiliate_url) }}" />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label" for="map_iframe">Google Maps Embed Code (Iframe Link)</label>
              <textarea class="form-control" id="map_iframe" name="map_iframe" rows="1" placeholder="Paste the <iframe src='...'></iframe> embed code here">{{ old('map_iframe', $restaurant->map_iframe) }}</textarea>
            </div>
          </div>
        </div>

        <!-- Tab 3: Featured Image -->
        <div class="tab-pane fade" id="featured-pane" role="tabpanel" aria-labelledby="featured-tab">
          <div class="mb-3">
            <label class="form-label fw-semibold" for="featured_image_file">Featured Image</label>
            <input type="file" class="form-control" id="featured_image_file" name="featured_image_file" accept="image/*" onchange="previewFeaturedImage(event)" />
            <div class="form-text">Recommended: 1200×800px, JPG/PNG/WebP, max 2MB. Main thumbnail image shown in cards.</div>
          </div>
          @if($restaurant->featured_image)
          <div class="mb-3">
            <label class="form-label text-muted small d-block">Current Image</label>
            <img src="{{ asset($restaurant->featured_image) }}" alt="Featured Image" class="img-thumbnail" style="max-height:220px;" />
          </div>
          @endif
          <div class="mb-3" id="featured-preview-new" style="display:none;">
            <label class="form-label text-muted small">New Image Preview</label>
            <img id="featured-preview-img" src="" alt="Preview" class="img-thumbnail" style="max-height:220px;" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="featured_image_alt">Image Alt Text (SEO)</label>
            <input type="text" class="form-control" id="featured_image_alt" name="featured_image_alt" value="{{ old('featured_image_alt', $restaurant->featured_image_alt) }}" placeholder="e.g. Fine dining room interior view" />
          </div>
          
          <hr class="my-4">
          <h5 class="fw-bold mb-3">Video Details</h5>
          <div class="mb-3">
            <label class="form-label fw-semibold" for="video">Video URL (YouTube/Vimeo)</label>
            <input type="url" class="form-control" id="video" name="video" placeholder="e.g. https://youtube.com/watch?v=..." value="{{ old('video', $restaurant->video) }}" />
            <div class="form-text">Paste a Youtube or Vimeo link here. This will display as the first item in the media gallery.</div>
          </div>
        </div>

        <!-- Tab 4: Gallery -->
        <div class="tab-pane fade" id="gallery-pane" role="tabpanel" aria-labelledby="gallery-tab">
          <!-- Existing Gallery Images -->
          @if($restaurant->images->count() > 0)
          <div class="mb-4">
            <h6 class="fw-semibold">Current Gallery Images <span class="text-muted small">(Check to delete)</span></h6>
            <div class="row g-3">
              @foreach($restaurant->images as $image)
              <div class="col-md-3 col-sm-4 col-6">
                <div class="card h-100 shadow-sm border position-relative">
                  <img src="{{ asset($image->image) }}" class="card-img-top object-fit-cover" style="height:120px;" alt="{{ $image->alt_text }}" />
                  <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <span class="small text-muted text-truncate" style="max-width:70%;">{{ $image->alt_text ?: 'No Alt Text' }}</span>
                    <div class="form-check m-0">
                      <input class="form-check-input border-danger" type="checkbox" name="delete_gallery_ids[]" value="{{ $image->id }}" id="del_img_{{ $image->id }}">
                      <label class="form-check-label text-danger small fw-semibold cursor-pointer" for="del_img_{{ $image->id }}">Delete</label>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endif

          <div class="mb-3">
            <label class="form-label fw-semibold" for="gallery_images">Add More Gallery Images</label>
            <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" accept="image/*" multiple onchange="previewGalleryImages(event)" />
            <div class="form-text">Upload multiple files. JPG/PNG/WebP, max 4MB per image.</div>
          </div>
          <div id="gallery-new-preview" class="row g-3 mt-2"></div>
          <div id="gallery-alt-fields"></div>
        </div>

        <!-- Tab 5: FAQs -->
        <div class="tab-pane fade" id="faqs-pane" role="tabpanel" aria-labelledby="faqs-tab">
          <div id="faqs-container">
            @foreach($restaurant->faqs as $index => $faq)
            <div class="card mb-3 faq-item border-info" id="faq_old_{{ $faq->id }}">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h6 class="card-title mb-0 text-info fw-bold"><i class="fas fa-question-circle me-1"></i> FAQ #{{ $index + 1 }}</h6>
                  <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFaq('faq_old_{{ $faq->id }}')"><i class="fas fa-trash me-1"></i> Remove</button>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Question</label>
                  <input type="text" class="form-control" name="faqs[{{ $index }}][question]" value="{{ $faq->question }}" required>
                </div>
                <div class="mb-2">
                  <label class="form-label fw-semibold">Answer</label>
                  <textarea class="form-control tinymce" name="faqs[{{ $index }}][answer]" rows="3" required>{{ $faq->answer }}</textarea>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <button type="button" class="btn btn-outline-primary mt-3" onclick="addFaq()">+ Add FAQ</button>
        </div>

        <!-- Tab 6: SEO & Schema -->
        <div class="tab-pane fade" id="seo-pane" role="tabpanel" aria-labelledby="seo-tab">
          <div class="mb-3">
            <label class="form-label" for="meta_title">Meta Title</label>
            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', $restaurant->seo->meta_title ?? '') }}" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="meta_description">Meta Description</label>
            <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $restaurant->seo->meta_description ?? '') }}</textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="canonical_url">Canonical URL</label>
              <input type="url" class="form-control" id="canonical_url" name="canonical_url" value="{{ old('canonical_url', $restaurant->seo->canonical_url ?? '') }}" />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label" for="og_title">OG Title</label>
              <input type="text" class="form-control" id="og_title" name="og_title" value="{{ old('og_title', $restaurant->seo->og_title ?? '') }}" />
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="og_description">OG Description</label>
            <textarea class="form-control" id="og_description" name="og_description" rows="2">{{ old('og_description', $restaurant->seo->og_description ?? '') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label" for="schema_markup">Schema Markup (JSON-LD) - <span class="text-info">Auto-generated</span></label>
            <textarea class="form-control" id="schema_markup" name="schema_markup" rows="8" placeholder="Auto-generated on save" readonly disabled>{{ $restaurant->seo->schema_markup ?? '' }}</textarea>
          </div>
        </div>
      </div>

      <div class="mt-4 pt-3 border-top">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection

@section('page-script')
<style>
  /* Cuisines dropdown styles */
  .cuisine-dropdown-wrapper { position: relative; }
  .cuisine-dropdown-trigger {
    display: flex; align-items: center; justify-content: space-between;
    min-height: 48px; padding: 8px 14px; border: 1.5px solid #d5d9e0;
    border-radius: 8px; background: #fff; cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s; gap: 10px; user-select: none;
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
  .cuisine-item--checked { background: #ede9ff; }
  .cuisine-item--checked:hover { background: #e4dfff; }
  .cuisine-item input[type="checkbox"] { display: none; }
  .cuisine-item-name { flex: 1; font-size: .9rem; color: #3a3a3a; }
  .cuisine-item--checked .cuisine-item-name { color: #5a50d6; font-weight: 600; }
  .cuisine-item-check { font-size: .8rem; color: #7367f0; display: none; }
  .cuisine-item--checked .cuisine-item-check { display: block; }
  .cuisine-no-results { padding: 16px; text-align: center; color: #9ea5b1; font-size: .88rem; }
  .cuisine-panel-footer { display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; background: #f8f7ff; }
  .cuisine-selected-count { font-size: .8rem; color: #7367f0; font-weight: 700; background: #ede9ff; border-radius: 20px; padding: 2px 10px; }

  /* Features dropdown styles */
  .feature-dropdown-wrapper { position: relative; }
  .feature-dropdown-trigger {
    display: flex; align-items: center; justify-content: space-between;
    min-height: 48px; padding: 8px 14px; border: 1.5px solid #d5d9e0;
    border-radius: 8px; background: #fff; cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s; gap: 10px; user-select: none;
  }
  .feature-dropdown-trigger:hover { border-color: #7367f0; }
  .feature-dropdown-trigger.open { border-color: #7367f0; box-shadow: 0 0 0 3px rgba(115,103,240,.15); }
  .feature-tags-area { display: flex; flex-wrap: wrap; gap: 6px; flex: 1; align-items: center; min-height: 28px; }
  .feature-placeholder { color: #9ea5b1; font-size: .92rem; display: flex; align-items: center; }
  .feature-tag {
    display: inline-flex; align-items: center; gap: 5px;
    background: #ede9ff; color: #5a50d6; border-radius: 20px;
    padding: 3px 10px 3px 8px; font-size: .8rem; font-weight: 600; white-space: nowrap;
  }
  .feature-tag .tag-remove { cursor: pointer; color: #8b82e0; font-size: .75rem; margin-left: 2px; transition: color .15s; }
  .feature-tag .tag-remove:hover { color: #dc3545; }
  .feature-dropdown-arrow { font-size: .8rem; color: #9ea5b1; transition: transform .25s; flex-shrink: 0; }
  .feature-dropdown-arrow.rotated { transform: rotate(180deg); }
  .feature-dropdown-panel {
    position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: #fff; border: 1.5px solid #d5d9e0; border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,.12); z-index: 1055; overflow: hidden;
    animation: dropdownFade .18s ease;
  }
  .feature-search-wrap { display: flex; align-items: center; padding: 10px 14px; gap: 10px; background: #f8f7ff; }
  .feature-search-icon { color: #9ea5b1; font-size: .9rem; flex-shrink: 0; }
  .feature-search-input { border: none; outline: none; background: transparent; font-size: .9rem; width: 100%; color: #3a3a3a; }
  .feature-search-input::placeholder { color: #b0b8c9; }
  .feature-divider { height: 1px; background: #eeedf5; }
  .feature-items-list { max-height: 240px; overflow-y: auto; padding: 6px 0; }
  .feature-items-list::-webkit-scrollbar { width: 4px; }
  .feature-items-list::-webkit-scrollbar-thumb { background: #d5d9e0; border-radius: 4px; }
  .feature-item {
    display: flex; align-items: center; gap: 10px; padding: 9px 16px;
    cursor: pointer; margin: 0; font-weight: 400; transition: background .13s;
  }
  .feature-item:hover { background: #f4f2ff; }
  .feature-item--checked { background: #ede9ff; }
  .feature-item--checked:hover { background: #e4dfff; }
  .feature-item input[type="checkbox"] { display: none; }
  .feature-item-icon {
    width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;
    border-radius: 6px; background: #f0eeff; color: #7367f0; font-size: .85rem; flex-shrink: 0;
  }
  .feature-item--checked .feature-item-icon { background: #7367f0; color: #fff; }
  .feature-item-name { flex: 1; font-size: .9rem; color: #3a3a3a; }
  .feature-item--checked .feature-item-name { color: #5a50d6; font-weight: 600; }
  .feature-item-check { font-size: .8rem; color: #7367f0; display: none; }
  .feature-item--checked .feature-item-check { display: block; }
  .feature-no-results { padding: 16px; text-align: center; color: #9ea5b1; font-size: .88rem; }
  .feature-panel-footer { display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; background: #f8f7ff; }
  .feature-selected-count { font-size: .8rem; color: #7367f0; font-weight: 700; background: #ede9ff; border-radius: 20px; padding: 2px 10px; }
</style>

<script>
  // Cuisines selection logic
  let cuisineDropdownOpen = false;
  function toggleCuisineDropdown() { cuisineDropdownOpen ? closeCuisineDropdown() : openCuisineDropdown(); }
  function openCuisineDropdown() {
    document.getElementById('cuisineDropdownPanel').style.display = 'block';
    document.getElementById('cuisineTrigger').classList.add('open');
    document.getElementById('cuisineArrow').classList.add('rotated');
    document.getElementById('cuisineSearchInput').focus();
    cuisineDropdownOpen = true;
  }
  function closeCuisineDropdown() {
    document.getElementById('cuisineDropdownPanel').style.display = 'none';
    document.getElementById('cuisineTrigger').classList.remove('open');
    document.getElementById('cuisineArrow').classList.remove('rotated');
    document.getElementById('cuisineSearchInput').value = '';
    filterCuisines('');
    cuisineDropdownOpen = false;
  }
  function onCuisineChange(cb) {
    const label = document.getElementById('cuisine-label-' + cb.dataset.id);
    cb.checked ? label.classList.add('cuisine-item--checked') : label.classList.remove('cuisine-item--checked');
    renderCuisineTags();
  }
  function renderCuisineTags() {
    const cbs = document.querySelectorAll('.cuisine-cb:checked');
    const tagsArea = document.getElementById('cuisineTagsArea');
    const placeholder = document.getElementById('cuisinePlaceholder');
    const countEl = document.getElementById('cuisineSelectedCount');
    
    tagsArea.querySelectorAll('.cuisine-tag').forEach(tag => tag.remove());
    
    if (cbs.length === 0) {
      placeholder.style.display = 'flex';
      countEl.textContent = '0 selected'; return;
    }
    placeholder.style.display = 'none';
    countEl.textContent = cbs.length + ' selected';
    cbs.forEach(cb => {
      const tag = document.createElement('span');
      tag.className = 'cuisine-tag';
      tag.innerHTML = `${cb.dataset.name} <span class="tag-remove" onclick="removeCuisineTag(event,'${cb.dataset.id}')"><i class="fas fa-times"></i></span>`;
      tagsArea.appendChild(tag);
    });
  }
  function removeCuisineTag(e, id) {
    e.stopPropagation();
    const cb = document.getElementById('cuisine_cb_' + id);
    if (cb) { cb.checked = false; cb.dispatchEvent(new Event('change')); }
  }
  function filterCuisines(query) {
    const q = query.toLowerCase().trim();
    let visible = 0;
    document.querySelectorAll('.cuisine-item').forEach(item => {
      const match = item.querySelector('.cuisine-item-name').textContent.toLowerCase().includes(q);
      item.style.display = match ? 'flex' : 'none';
      if (match) visible++;
    });
    const noR = document.getElementById('cuisineNoResults');
    visible === 0 ? noR.classList.remove('d-none') : noR.classList.add('d-none');
  }

  // Features selection logic
  let featureDropdownOpen = false;
  function toggleFeatureDropdown() { featureDropdownOpen ? closeFeatureDropdown() : openFeatureDropdown(); }
  function openFeatureDropdown() {
    document.getElementById('featureDropdownPanel').style.display = 'block';
    document.getElementById('featureTrigger').classList.add('open');
    document.getElementById('featureArrow').classList.add('rotated');
    document.getElementById('featureSearchInput').focus();
    featureDropdownOpen = true;
  }
  function closeFeatureDropdown() {
    document.getElementById('featureDropdownPanel').style.display = 'none';
    document.getElementById('featureTrigger').classList.remove('open');
    document.getElementById('featureArrow').classList.remove('rotated');
    document.getElementById('featureSearchInput').value = '';
    filterFeatures('');
    featureDropdownOpen = false;
  }
  function onFeatureChange(cb) {
    const label = document.getElementById('feature-label-' + cb.dataset.id);
    cb.checked ? label.classList.add('feature-item--checked') : label.classList.remove('feature-item--checked');
    renderFeatureTags();
  }
  function renderFeatureTags() {
    const cbs = document.querySelectorAll('.feature-cb:checked');
    const tagsArea = document.getElementById('featureTagsArea');
    const placeholder = document.getElementById('featurePlaceholder');
    const countEl = document.getElementById('featureSelectedCount');
    
    tagsArea.querySelectorAll('.feature-tag').forEach(tag => tag.remove());
    
    if (cbs.length === 0) {
      placeholder.style.display = 'flex';
      countEl.textContent = '0 selected'; return;
    }
    placeholder.style.display = 'none';
    countEl.textContent = cbs.length + ' selected';
    cbs.forEach(cb => {
      const tag = document.createElement('span');
      tag.className = 'feature-tag';
      tag.innerHTML = `${cb.dataset.name} <span class="tag-remove" onclick="removeFeatureTag(event,'${cb.dataset.id}')"><i class="fas fa-times"></i></span>`;
      tagsArea.appendChild(tag);
    });
  }
  function removeFeatureTag(e, id) {
    e.stopPropagation();
    const cb = document.getElementById('feature_cb_' + id);
    if (cb) { cb.checked = false; cb.dispatchEvent(new Event('change')); }
  }
  function filterFeatures(query) {
    const q = query.toLowerCase().trim();
    let visible = 0;
    document.querySelectorAll('.feature-item').forEach(item => {
      const match = item.querySelector('.feature-item-name').textContent.toLowerCase().includes(q);
      item.style.display = match ? 'flex' : 'none';
      if (match) visible++;
    });
    const noR = document.getElementById('featureNoResults');
    visible === 0 ? noR.classList.remove('d-none') : noR.classList.add('d-none');
  }

  // Global click outside listener
  document.addEventListener('click', function(e) {
    const wCuisine = document.getElementById('cuisineDropdownWrapper');
    if (wCuisine && !wCuisine.contains(e.target)) closeCuisineDropdown();
    const wFeature = document.getElementById('featureDropdownWrapper');
    if (wFeature && !wFeature.contains(e.target)) closeFeatureDropdown();
  });

  // Autogenerate slugs
  function generateSlug(text) {
    return text.toString().toLowerCase()
      .replace(/\s+/g, '-')
      .replace(/[^\w\-]+/g, '')
      .replace(/\-\-+/g, '-')
      .replace(/^-+/, '')
      .replace(/-+$/, '');
  }

  // Autocomplete Slug in Basic tab
  document.getElementById('name').addEventListener('input', function() {
    document.getElementById('slug').value = generateSlug(this.value);
  });

  // Preview Uploads
  function previewFeaturedImage(event) {
    const file = event.target.files[0]; if (!file) return;
    const reader = new FileReader();
    reader.onload = e => { document.getElementById('featured-preview-img').src = e.target.result; document.getElementById('featured-preview-new').style.display = 'block'; };
    reader.readAsDataURL(file);
  }

  function previewGalleryImages(event) {
    const files = Array.from(event.target.files);
    const previewContainer = document.getElementById('gallery-new-preview');
    const altFieldsContainer = document.getElementById('gallery-alt-fields');
    previewContainer.innerHTML = ''; altFieldsContainer.innerHTML = '';
    if (files.length > 0) {
      const titleDiv = document.createElement('div');
      titleDiv.className = 'col-12 mb-2';
      titleDiv.innerHTML = `<label class="form-label fw-semibold">Preview (${files.length} new images selected)</label>`;
      previewContainer.appendChild(titleDiv);
      const altTitle = document.createElement('h6');
      altTitle.className = 'mt-3 mb-2 fw-semibold';
      altTitle.textContent = 'Add Alt Text for New Gallery Images (SEO)';
      altFieldsContainer.appendChild(altTitle);
      files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
          const col = document.createElement('div');
          col.className = 'col-md-3 col-sm-4 col-6';
          col.innerHTML = `<div class="card border"><img src="${e.target.result}" class="card-img-top" style="height:120px;object-fit:cover;" /><div class="card-body p-2"><small class="text-muted">New Image ${index + 1}</small></div></div>`;
          previewContainer.appendChild(col);
          const altDiv = document.createElement('div');
          altDiv.className = 'mb-3 mt-2';
          altDiv.innerHTML = `<label class="form-label text-muted small">Alt Text for New Image #${index + 1}</label><input type="text" class="form-control form-control-sm" name="gallery_alts[${index}]" placeholder="e.g. Dining area table setting" />`;
          altFieldsContainer.appendChild(altDiv);
        }
        reader.readAsDataURL(file);
      });
    }
  }

  // Dynamic FAQs
  let faqIndex = {{ $restaurant->faqs->count() }};
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
            <input type="text" class="form-control" name="faqs[${faqIndex}][question]" required placeholder="e.g. Do you offer vegetarian options?">
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Answer</label>
            <textarea class="form-control tinymce" id="faq_answer_${faqIndex}" name="faqs[${faqIndex}][answer]" rows="3" required placeholder="e.g. Yes, we have a variety of vegetarian and vegan dishes on our menu."></textarea>
          </div>
        </div>
      </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    
    if (typeof tinymce !== 'undefined') {
      tinymce.init({
        selector: '#faq_answer_' + faqIndex,
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
        toolbar_mode: 'floating',
        height: 300,
        setup: function (editor) {
          editor.on('change', function () {
            editor.save();
          });
        }
      });
    }
    
    faqIndex++;
  }

  function removeFaq(id) {
    document.getElementById(id).remove();
  }

  // Save new cuisine via AJAX
  function saveNewCuisine() {
    const name = document.getElementById('new_cuisine_name').value.trim();
    const slug = document.getElementById('new_cuisine_slug').value.trim();
    const alertBox = document.getElementById('cuisine-modal-alert');
    if (!name || !slug) { alertBox.className = 'alert alert-danger'; alertBox.textContent = 'Please enter name and slug.'; return; }
    alertBox.className = 'd-none';
    document.getElementById('saveCuisineBtnText').classList.add('d-none');
    document.getElementById('saveCuisineBtnSpinner').classList.remove('d-none');
    $.ajax({
      url: '{{ route("cuisines.quick-store") }}', type: 'POST',
      data: { _token: '{{ csrf_token() }}', name, slug, status: 1 },
      success: function(response) {
        if (response.success) {
          const item = response.data;
          const list = document.getElementById('cuisineItemsList');
          const noResults = document.getElementById('cuisineNoResults');
          const newLabel = document.createElement('label');
          newLabel.className = 'cuisine-item cuisine-item--checked';
          newLabel.id = 'cuisine-label-' + item.id;
          newLabel.innerHTML = `<input type="checkbox" name="cuisines[]" value="${item.id}" id="cuisine_cb_${item.id}" class="cuisine-cb" data-name="${item.name}" data-id="${item.id}" checked onchange="onCuisineChange(this)" /><span class="cuisine-item-name">${item.name}</span><span class="cuisine-item-check"><i class="fas fa-check"></i></span>`;
          list.insertBefore(newLabel, noResults);
          renderCuisineTags();
          document.getElementById('new_cuisine_name').value = '';
          document.getElementById('new_cuisine_slug').value = '';
          bootstrap.Modal.getInstance(document.getElementById('addCuisineModal')).hide();
        } else { alertBox.className = 'alert alert-danger'; alertBox.textContent = response.message || 'Failed.'; }
      },
      error: function(xhr) {
        alertBox.className = 'alert alert-danger';
        const errors = xhr.responseJSON?.errors;
        alertBox.textContent = errors ? Object.values(errors).flat().join(' ') : 'An error occurred.';
      },
      complete: function() {
        document.getElementById('saveCuisineBtnText').classList.remove('d-none');
        document.getElementById('saveCuisineBtnSpinner').classList.add('d-none');
      }
    });
  }

  // Save new feature via AJAX
  function saveNewFeature() {
    const name = document.getElementById('new_feature_name').value.trim();
    const slug = document.getElementById('new_feature_slug').value.trim();
    const iconClass = document.getElementById('new_feature_icon').value.trim();
    const description = document.getElementById('new_feature_desc').value.trim();
    const alertBox = document.getElementById('feature-modal-alert');
    if (!name || !slug) { alertBox.className = 'alert alert-danger'; alertBox.textContent = 'Please enter name and slug.'; return; }
    alertBox.className = 'd-none';
    document.getElementById('saveFeatureBtnText').classList.add('d-none');
    document.getElementById('saveFeatureBtnSpinner').classList.remove('d-none');
    $.ajax({
      url: '{{ route("features.quick-store") }}', type: 'POST',
      data: { _token: '{{ csrf_token() }}', name, slug, icon_class: iconClass || 'fas fa-star', description, status: 1 },
      success: function(response) {
        if (response.success) {
          const item = response.data;
          const list = document.getElementById('featureItemsList');
          const noResults = document.getElementById('featureNoResults');
          const newLabel = document.createElement('label');
          newLabel.className = 'feature-item feature-item--checked';
          newLabel.id = 'feature-label-' + item.id;
          newLabel.innerHTML = `<input type="checkbox" name="features[]" value="${item.id}" id="feature_cb_${item.id}" class="feature-cb" data-name="${item.name}" data-id="${item.id}" checked onchange="onFeatureChange(this)" /><span class="feature-item-icon"><i class="${iconClass || 'fas fa-star'}"></i></span><span class="feature-item-name">${item.name}</span><span class="feature-item-check"><i class="fas fa-check"></i></span>`;
          list.insertBefore(newLabel, noResults);
          renderFeatureTags();
          document.getElementById('new_feature_name').value = '';
          document.getElementById('new_feature_slug').value = '';
          document.getElementById('new_feature_icon').value = 'fas fa-star';
          document.getElementById('new_feature_desc').value = '';
          bootstrap.Modal.getInstance(document.getElementById('addFeatureModal')).hide();
        } else { alertBox.className = 'alert alert-danger'; alertBox.textContent = response.message || 'Failed.'; }
      },
      error: function(xhr) {
        alertBox.className = 'alert alert-danger';
        const errors = xhr.responseJSON?.errors;
        alertBox.textContent = errors ? Object.values(errors).flat().join(' ') : 'An error occurred.';
      },
      complete: function() {
        document.getElementById('saveFeatureBtnText').classList.remove('d-none');
        document.getElementById('saveFeatureBtnSpinner').classList.add('d-none');
      }
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    // Auto-slug in quick add modals
    const newCuisineName = document.getElementById('new_cuisine_name');
    if (newCuisineName) {
      newCuisineName.addEventListener('input', function() {
        document.getElementById('new_cuisine_slug').value = generateSlug(this.value);
      });
    }

    const newFeatureName = document.getElementById('new_feature_name');
    if (newFeatureName) {
      newFeatureName.addEventListener('input', function() {
        document.getElementById('new_feature_slug').value = generateSlug(this.value);
      });
    }

    renderCuisineTags();
    renderFeatureTags();
  });
</script>

{{-- ===== Add Cuisine Modal ===== --}}
<div class="modal fade" id="addCuisineModal" tabindex="-1" aria-labelledby="addCuisineModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCuisineModalLabel"><i class="fas fa-plus-circle me-2 text-primary"></i>Add New Cuisine</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="cuisine-modal-alert" class="d-none"></div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_cuisine_name">Cuisine Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="new_cuisine_name" placeholder="e.g. Italian" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_cuisine_slug">Slug <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="new_cuisine_slug" placeholder="e.g. italian" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveNewCuisine()">
          <span id="saveCuisineBtnText"><i class="fas fa-plus me-1"></i>Add Cuisine</span>
          <span id="saveCuisineBtnSpinner" class="d-none"><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ===== Add Feature Modal ===== --}}
<div class="modal fade" id="addFeatureModal" tabindex="-1" aria-labelledby="addFeatureModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addFeatureModalLabel"><i class="fas fa-plus-circle me-2 text-primary"></i>Add New Feature</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="feature-modal-alert" class="d-none"></div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_feature_name">Feature Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="new_feature_name" placeholder="e.g. Outdoor Seating" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_feature_slug">Slug <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="new_feature_slug" placeholder="e.g. outdoor-seating" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_feature_icon">Icon Class</label>
          <input type="text" class="form-control" id="new_feature_icon" placeholder="e.g. fas fa-chair" value="fas fa-star" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" for="new_feature_desc">Description</label>
          <textarea class="form-control" id="new_feature_desc" rows="2" placeholder="e.g. Outdoor patio seating with view"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveNewFeature()">
          <span id="saveFeatureBtnText"><i class="fas fa-plus me-1"></i>Add Feature</span>
          <span id="saveFeatureBtnSpinner" class="d-none"><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
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

    // Pre-select category from existing record
    const preselectedCat = document.getElementById('restaurant_category_id').value;
    if (preselectedCat) {
      const rb = document.getElementById('cat_rb_' + preselectedCat);
      if (rb) { rb.checked = true; onCategoryChange(rb); }
    }
  });

  // ─── CATEGORY SINGLE-SELECT DROPDOWN ────────────────────────────────────────
  function toggleCategoryDropdown() {
    const panel  = document.getElementById('categoryDropdownPanel');
    const arrow  = document.getElementById('categoryArrow');
    const isOpen = panel.style.display !== 'none';
    panel.style.display = isOpen ? 'none' : 'block';
    arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    if (!isOpen) document.getElementById('categorySearchInput').focus();
  }
  function closeCategoryDropdown() {
    document.getElementById('categoryDropdownPanel').style.display = 'none';
    document.getElementById('categoryArrow').style.transform = 'rotate(0deg)';
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
    const hidden= document.getElementById('restaurant_category_id');
    const tags  = document.getElementById('categoryTagsArea');
    const ph    = document.getElementById('categoryPlaceholder');
    hidden.value = id;
    document.querySelectorAll('#categoryItemsList .cuisine-item').forEach(l => l.classList.remove('selected'));
    document.getElementById('cat-label-' + id).classList.add('selected');
    // Show plain text in trigger (no tag/× button)
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
  }

  // Opening Hours JavaScript
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.closed-checkbox, .24h-checkbox').forEach(cb => {
      cb.addEventListener('change', function() {
        const day = this.dataset.day;
        const cbClosed = document.getElementById('closed_' + day);
        const cb24h = document.getElementById('24h_' + day);
        const inputs = document.querySelectorAll('.time-input-' + day);
        
        if (this.classList.contains('closed-checkbox') && this.checked) {
          cb24h.checked = false;
          cb24h.disabled = true;
        } else if (this.classList.contains('closed-checkbox') && !this.checked) {
          cb24h.disabled = false;
        }

        if (this.classList.contains('24h-checkbox') && this.checked) {
          cbClosed.checked = false;
          cbClosed.disabled = true;
        } else if (this.classList.contains('24h-checkbox') && !this.checked) {
          cbClosed.disabled = false;
        }

        const shouldDisable = cbClosed.checked || cb24h.checked;
        
        inputs.forEach(input => {
            input.disabled = shouldDisable;
            if (shouldDisable) {
                input.value = '';
                input.classList.add('bg-light');
            } else {
                input.classList.remove('bg-light');
            }
        });
      });
      
      // Trigger change on load to set initial state
      if(cb.checked) {
          cb.dispatchEvent(new Event('change'));
      }
    });
  });

  function copyMondayHours() {
    const days = ['tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    const monOpen = document.querySelector('input[name="opening_hours[monday][open]"]').value;
    const monClose = document.querySelector('input[name="opening_hours[monday][close]"]').value;
    const monClosed = document.getElementById('closed_monday').checked;
    const mon24h = document.getElementById('24h_monday').checked;

    if (!monClosed && !mon24h && (!monOpen || !monClose)) {
        if (typeof toastr !== 'undefined') {
            toastr.error('Please set valid times for Monday before copying.');
        } else {
            alert('Please set valid times for Monday before copying.');
        }
        return;
    }

    days.forEach(day => {
      document.querySelector('input[name="opening_hours['+day+'][open]"]').value = monOpen;
      document.querySelector('input[name="opening_hours['+day+'][close]"]').value = monClose;
      document.getElementById('closed_' + day).checked = monClosed;
      document.getElementById('24h_' + day).checked = mon24h;
      
      const shouldDisable = monClosed || mon24h;
      document.querySelectorAll('.time-input-' + day).forEach(input => {
          input.disabled = shouldDisable;
          if (shouldDisable) {
              input.classList.add('bg-light');
          } else {
              input.classList.remove('bg-light');
          }
      });
    });

    if (typeof toastr !== 'undefined') {
        toastr.success('Monday schedule applied to all days.');
    }
  }

  function clearCategory() {
    document.getElementById('restaurant_category_id').value = '';
    const tags = document.getElementById('categoryTagsArea');
    const txt  = tags.querySelector('.category-selected-text');
    if (txt) txt.remove();
    document.getElementById('categoryPlaceholder').style.display = '';
    document.querySelectorAll('#categoryItemsList .cuisine-item').forEach(l => l.classList.remove('selected'));
    document.querySelectorAll('#categoryItemsList .cat-rb').forEach(r => r.checked = false);
  }
  document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('categoryDropdownWrapper');
    if (wrapper && !wrapper.contains(e.target)) closeCategoryDropdown();
  });
</script>
@endsection
