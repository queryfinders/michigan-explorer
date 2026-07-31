
      <!-- Tabs Navigation -->
      <ul class="nav nav-tabs mb-4" id="pageFormTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="content-tab" data-bs-toggle="tab" data-bs-target="#content-pane" type="button" role="tab" aria-controls="content-pane" aria-selected="true">Page Content</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="banner-tab" data-bs-toggle="tab" data-bs-target="#banner-pane" type="button" role="tab" aria-controls="banner-pane" aria-selected="false">Hero Banner</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button" role="tab" aria-controls="seo-pane" aria-selected="false">SEO & Schema</button>
        </li>
      </ul>

      <!-- Tabs Content -->
      <div class="tab-content p-0" id="pageFormTabsContent">
        
        <!-- Tab 1: Page Content -->
        <div class="tab-pane fade show active" id="content-pane" role="tabpanel" aria-labelledby="content-tab">
          <div class="mb-3">
            <label class="form-label fw-semibold" for="title">Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $page->title) }}" placeholder="Enter page title" />
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @else <div class="invalid-feedback">The title field is required.</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" for="slug">Slug <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="Enter page slug (e.g. about-us)" />
            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @else <div class="invalid-feedback">The slug field is required.</div> @enderror
          </div>
          {{-- 
          <div class="mb-3">
            <label class="form-label" for="content">Content</label>
            <textarea class="form-control tinymce" id="content" name="content" rows="15">{{ $page->content }}</textarea>
          </div>
          --}}
        </div>

        <!-- Tab 2: Hero Banner -->
        <div class="tab-pane fade" id="banner-pane" role="tabpanel" aria-labelledby="banner-tab">
          <div class="mb-3">
            <label class="form-label" for="banner_title">Banner Title</label>
            <input type="text" class="form-control" id="banner_title" name="banner_title" value="{{ $page->banner_title }}" placeholder="Enter banner title" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="banner_subtitle">Banner Subtitle</label>
            <textarea class="form-control" id="banner_subtitle" name="banner_subtitle" rows="3" placeholder="Enter banner subtitle">{{ $page->banner_subtitle }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label" for="featured_image_file">Banner Image</label>
            <input type="file" class="form-control" id="featured_image_file" name="featured_image_file" accept="image/*" />
            @if($page->featured_image)
              <div class="mt-2 mb-3">
                <img src="{{ asset($page->featured_image) }}" alt="{{ $page->featured_image_alt ?? 'Banner Image' }}" class="img-thumbnail" style="max-height: 200px;">
              </div>
            @endif
          </div>
          <div class="mb-3">
            <label class="form-label" for="featured_image_alt">Image Alt Text (SEO)</label>
            <input type="text" class="form-control" id="featured_image_alt" name="featured_image_alt" value="{{ $page->featured_image_alt }}" placeholder="Describe the image for search engines e.g. Scenic view of Michigan coastline" />
          </div>
        </div>

        <!-- Tab 3: SEO & Schema -->
        <div class="tab-pane fade" id="seo-pane" role="tabpanel" aria-labelledby="seo-tab">
          <div class="mb-3">
            <label class="form-label" for="meta_title">Meta Title</label>
            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ $page->seo->meta_title ?? '' }}" placeholder="Enter meta title" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="meta_description">Meta Description <span class="text-muted small ms-2 fw-normal" id="meta_desc_count">(0 / 160)</span></label>
            <textarea class="form-control" id="meta_description" name="meta_description" rows="2" maxlength="160" placeholder="Enter meta description (max 160 characters)">{{ $page->seo->meta_description ?? '' }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label" for="og_title">OG Title</label>
            <input type="text" class="form-control" id="og_title" name="og_title" value="{{ $page->seo->og_title ?? '' }}" placeholder="Enter OG title" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="og_description">OG Description <span class="text-muted small ms-2 fw-normal" id="og_desc_count">(0 / 160)</span></label>
            <textarea class="form-control" id="og_description" name="og_description" rows="2" maxlength="160" placeholder="Enter OG description (max 160 characters)">{{ $page->seo->og_description ?? '' }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label" for="schema_markup">Schema Markup (JSON-LD) - <span class="text-info">Auto-generated</span></label>
            <textarea class="form-control" id="schema_markup" name="schema_markup" rows="8" placeholder="Auto-generated on save" readonly disabled>{{ $page->seo->schema_markup ?? '' }}</textarea>
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
      height: 400,
      setup: function (editor) {
        editor.on('change', function () {
          editor.save();
        });
      }
    });

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

    // Custom Form Validation & Tab Redirection
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(event) {
            let isValid = true;
            let firstInvalidTab = null;

            // Validate Title
            const titleInput = document.getElementById('title');
            if (titleInput && !titleInput.value.trim()) {
                isValid = false;
                titleInput.classList.add('is-invalid');
                if (!firstInvalidTab) firstInvalidTab = 'content-tab';
            } else if (titleInput) {
                titleInput.classList.remove('is-invalid');
            }

            // Validate Slug
            const slugInput = document.getElementById('slug');
            if (slugInput && !slugInput.value.trim()) {
                isValid = false;
                slugInput.classList.add('is-invalid');
                if (!firstInvalidTab) firstInvalidTab = 'content-tab';
            } else if (slugInput) {
                slugInput.classList.remove('is-invalid');
            }

            if (!isValid) {
                event.preventDefault(); // Prevent submission
                
                // Switch to the tab containing the first invalid field
                if (firstInvalidTab) {
                    const tabBtn = document.getElementById(firstInvalidTab);
                    if (tabBtn && !tabBtn.classList.contains('active')) {
                        const tab = new bootstrap.Tab(tabBtn);
                        tab.show();
                    }
                }
                
                // Focus on first invalid field
                setTimeout(() => {
                    if (titleInput && !titleInput.value.trim()) {
                        titleInput.focus();
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
