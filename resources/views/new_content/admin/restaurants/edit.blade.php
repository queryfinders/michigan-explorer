@extends('layouts/layoutMaster')

@section('title', 'Edit Restaurant')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Restaurant</h5>
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
              <label class="form-label" for="restaurant_category_id">Category <span class="text-danger">*</span></label>
              <select class="form-select @error('restaurant_category_id') is-invalid @enderror" id="restaurant_category_id" name="restaurant_category_id" required>
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
            <label class="form-label" for="short_description">Short Description</label>
            <textarea class="form-control" id="short_description" name="short_description" rows="2">{{ old('short_description', $restaurant->short_description) }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea class="form-control tinymce" id="description" name="description" rows="6">{{ old('description', $restaurant->description) }}</textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="status">Status</label>
              <select class="form-select" id="status" name="status">
                <option value="1" {{ old('status', $restaurant->status) == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $restaurant->status) == '0' ? 'selected' : '' }}>Inactive</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <div class="form-check form-switch mt-4 pt-2">
                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $restaurant->is_featured) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="is_featured">Featured Restaurant (Shows on Homepage)</label>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab 2: Details -->
        <div class="tab-pane fade" id="details-pane" role="tabpanel" aria-labelledby="details-tab">
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label" for="cuisine">Cuisine Type</label>
              <input type="text" class="form-control" id="cuisine" name="cuisine" placeholder="e.g. Fine Dining, Seafood, Italian" value="{{ old('cuisine', $restaurant->cuisine) }}" />
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="opening_hours">Opening Hours</label>
              <input type="text" class="form-control" id="opening_hours" name="opening_hours" placeholder="e.g. Daily 11:00 AM - 10:00 PM" value="{{ old('opening_hours', $restaurant->opening_hours) }}" />
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="starting_price">Average Cost ($)</label>
              <input type="number" class="form-control" id="starting_price" name="starting_price" placeholder="e.g. 45" value="{{ old('starting_price', $restaurant->starting_price) }}" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label" for="city">City</label>
              <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $restaurant->city) }}" />
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="state">State</label>
              <input type="text" class="form-control" id="state" name="state" placeholder="e.g. MI" value="{{ old('state', $restaurant->state ?? 'MI') }}" />
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label" for="zip">Zip Code</label>
              <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip', $restaurant->zip) }}" />
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="address">Street Address</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $restaurant->address) }}" />
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
            <div class="col-md-4 mb-3">
              <label class="form-label" for="website">Website URL</label>
              <input type="url" class="form-control" id="website" name="website" value="{{ old('website', $restaurant->website) }}" />
            </div>
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
            <div class="mb-3" id="featured-preview-existing">
              <label class="form-label text-muted small">Current Featured Image</label>
              <div>
                <img src="{{ asset($restaurant->featured_image) }}" alt="Current Image" class="img-thumbnail" style="max-height:220px;" />
              </div>
            </div>
          @endif
          <div class="mb-3" id="featured-preview-new" style="display:none;">
            <label class="form-label text-muted small">New Image Preview</label>
            <div>
              <img id="featured-preview-img" src="" alt="Preview" class="img-thumbnail" style="max-height:220px;" />
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="featured_image_alt">Image Alt Text (SEO)</label>
            <input type="text" class="form-control" id="featured_image_alt" name="featured_image_alt" value="{{ $restaurant->featured_image_alt }}" placeholder="e.g. Lakeside dining room at Sunrise Café in Traverse City" />
            <div class="form-text">Describe the image clearly for search engines.</div>
          </div>
        </div>

        <!-- Tab 4: Gallery -->
        <div class="tab-pane fade" id="gallery-pane" role="tabpanel" aria-labelledby="gallery-tab">
          
          <!-- Existing Gallery Images -->
          @if($restaurant->images->count() > 0)
          <div class="mb-4">
            <label class="form-label fw-semibold">Current Gallery ({{ $restaurant->images->count() }} images)</label>
            <div class="row g-3" id="existing-gallery-grid">
              @foreach($restaurant->images as $img)
              <div class="col-md-3 col-sm-4 col-6" id="gallery-item-{{ $img->id }}">
                <div class="card border position-relative">
                  <img src="{{ asset($img->image) }}" alt="{{ $img->alt_text ?? 'Gallery' }}" class="card-img-top object-fit-cover" style="height:120px;" />
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

        <!-- Tab 5: FAQs -->
        <div class="tab-pane fade" id="faqs-pane" role="tabpanel" aria-labelledby="faqs-tab">
          <div id="faqs-container">
            @foreach($restaurant->faqs as $index => $faq)
            <div class="card mb-3 faq-item border-info" id="faq_{{ $index }}">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h6 class="card-title mb-0 text-info fw-bold"><i class="fas fa-question-circle me-1"></i> FAQ</h6>
                  <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFaq('faq_{{ $index }}')"><i class="fas fa-trash me-1"></i> Remove</button>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Question</label>
                  <input type="text" class="form-control" name="faqs[{{ $index }}][question]" value="{{ $faq->question }}" required placeholder="e.g. Do you offer vegetarian options?">
                </div>
                <div class="mb-2">
                  <label class="form-label fw-semibold">Answer</label>
                  <textarea class="form-control" id="faq_answer_{{ $index }}" name="faqs[{{ $index }}][answer]" rows="3" required placeholder="e.g. Yes, we have a variety of vegetarian and vegan dishes on our menu.">{{ $faq->answer }}</textarea>
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
            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ $restaurant->seo->meta_title ?? '' }}" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="meta_description">Meta Description</label>
            <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ $restaurant->seo->meta_description ?? '' }}</textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="canonical_url">Canonical URL</label>
              <input type="url" class="form-control" id="canonical_url" name="canonical_url" value="{{ $restaurant->seo->canonical_url ?? '' }}" />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label" for="og_title">OG Title</label>
              <input type="text" class="form-control" id="og_title" name="og_title" value="{{ $restaurant->seo->og_title ?? '' }}" />
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="og_description">OG Description</label>
            <textarea class="form-control" id="og_description" name="og_description" rows="2">{{ $restaurant->seo->og_description ?? '' }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label" for="schema_markup">Schema Markup (JSON-LD) - <span class="text-info">Auto-generated</span></label>
            <textarea class="form-control" id="schema_markup" name="schema_markup" rows="8" placeholder="Auto-generated on save" readonly disabled>{{ $restaurant->seo->schema_markup ?? '' }}</textarea>
          </div>
        </div>

      </div>

      <div class="mt-4 pt-3 border-top">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection

@section('page-script')
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

    // Auto-generate Slug from Name
    $('#name').on('input', function() {
      var name = $(this).val();
      var slug = name.toLowerCase()
        .replace(/[^a-z0-9\-]/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
      $('#slug').val(slug);
    });
  });

  // Featured Image Live Preview
  function previewFeaturedImage(event) {
    const input = event.target;
    const previewContainer = document.getElementById('featured-preview-new');
    const previewImg = document.getElementById('featured-preview-img');
    const existingContainer = document.getElementById('featured-preview-existing');
    
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        previewImg.src = e.target.result;
        previewContainer.style.display = 'block';
        if (existingContainer) existingContainer.style.opacity = '0.4';
      }
      reader.readAsDataURL(input.files[0]);
    } else {
      previewContainer.style.display = 'none';
      if (existingContainer) existingContainer.style.opacity = '1';
    }
  }

  // Toggle deletion overlay for existing gallery images
  function toggleDeleteOverlay(checkbox, id) {
    const overlay = document.getElementById('overlay-' + id);
    if (checkbox.checked) {
      overlay.classList.remove('d-none');
      overlay.classList.add('d-flex');
    } else {
      overlay.classList.remove('d-flex');
      overlay.classList.add('d-none');
    }
  }

  // Gallery Images Live Preview
  function previewGalleryImages(event) {
    const input = event.target;
    const previewContainer = document.getElementById('gallery-new-preview');
    const altFieldsContainer = document.getElementById('gallery-alt-fields');
    
    previewContainer.innerHTML = '';
    altFieldsContainer.innerHTML = '';
    
    if (input.files) {
      Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
          // Add preview thumbnail
          const col = document.createElement('div');
          col.className = 'col-md-3 col-sm-4 col-6 position-relative';
          col.innerHTML = `
            <div class="card h-100 shadow-sm border">
              <img src="${e.target.result}" class="card-img-top object-fit-cover" style="height:120px;" alt="Preview" />
              <div class="card-body p-2">
                <span class="badge bg-secondary">New Image #${index + 1}</span>
              </div>
            </div>
          `;
          previewContainer.appendChild(col);

          // Add alt field input
          const altDiv = document.createElement('div');
          altDiv.className = 'mb-3 mt-2';
          altDiv.innerHTML = `
            <label class="form-label text-muted small">Alt Text for New Image #${index + 1}</label>
            <input type="text" class="form-control form-control-sm" name="gallery_alts[${index}]" placeholder="e.g. Table arrangement detail" />
          `;
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
            <textarea class="form-control" id="faq_answer_${faqIndex}" name="faqs[${faqIndex}][answer]" rows="3" required placeholder="e.g. Yes, we have a variety of vegetarian and vegan dishes on our menu."></textarea>
          </div>
        </div>
      </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    faqIndex++;
  }

  function removeFaq(id) {
    document.getElementById(id).remove();
  }
</script>
@endsection
