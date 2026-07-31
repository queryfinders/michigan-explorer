@if($errors->any())
      <div class="alert alert-danger alert-dismissible mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
          <i class="ti ti-alert-circle fs-5"></i>
          <strong>Please fix the following errors:</strong>
        </div>
        <ul class="mb-0 mt-2 ps-3">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      @endif

      <!-- Tabs Navigation -->
      <ul class="nav nav-tabs mb-4" id="blogFormTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic-pane" type="button" role="tab">
            Basic Info
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="image-tab" data-bs-toggle="tab" data-bs-target="#image-pane" type="button" role="tab">
            Featured Image
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button" role="tab">
            SEO & Schema
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="faqs-tab" data-bs-toggle="tab" data-bs-target="#faqs-pane" type="button" role="tab">
            FAQs
          </button>
        </li>
      </ul>

      <!-- Tabs Content -->
      <div class="tab-content p-0" id="blogFormTabsContent">

        {{-- ============================================ --}}
        {{-- TAB 1: BASIC INFO --}}
        {{-- ============================================ --}}
        <div class="tab-pane fade show active" id="basic-pane" role="tabpanel">

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="title">Title <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('title') is-invalid @enderror"
                     id="title" name="title"
                     value="{{ old('title', $blog->title ?? '') }}"
                     placeholder="e.g. The Ultimate 3-Day Traverse City" />
              @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <div class="text-danger small mt-1 d-none" id="title-error-msg">The title field is required.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="slug">Slug <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                       id="slug" name="slug"
                       value="{{ old('slug', $blog->slug ?? '') }}"
                       placeholder="e.g. the-ultimate-traverse-city" />
              </div>
              @error('slug') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              <div class="text-danger small mt-1 d-none" id="slug-error-msg">The slug field is required.</div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            {{-- CATEGORY --}}
            <div class="col-md-4">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label fw-semibold mb-0" for="blog_category_id">Category</label>
                <button type="button" class="btn btn-sm btn-link p-0 text-primary fw-semibold"
                        data-bs-toggle="modal" data-bs-target="#addCategoryModal" style="text-decoration: none; font-size: 0.85rem;">
                  <i class="fas fa-plus-circle me-1"></i>Add Category
                </button>
              </div>
              <input type="hidden" name="blog_category_id" id="blog_category_id"
                     value="{{ old('blog_category_id', $blog->blog_category_id ?? '') }}" />
              <div class="cuisine-dropdown-wrapper" id="catDropWrapper">
                <div class="cuisine-dropdown-trigger" id="catDropTrigger" onclick="toggleCatDrop()">
                  <div class="cuisine-tags-area" id="catTagsArea">
                    <span class="cuisine-placeholder" id="catPlaceholder">
                      <i class="ti ti-category me-1 text-muted"></i>Select category...
                    </span>
                  </div>
                  <i class="ti ti-chevron-down cuisine-dropdown-arrow" id="catArrow"></i>
                </div>
                <div class="cuisine-dropdown-panel" id="catDropPanel" style="display:none;">
                  <div class="cuisine-search-wrap">
                    <i class="ti ti-search cuisine-search-icon"></i>
                    <input type="text" class="cuisine-search-input" id="catSearchInput"
                           placeholder="Search categories..." oninput="filterCat(this.value)" autocomplete="off" />
                  </div>
                  <div class="cuisine-divider"></div>
                  <div class="cuisine-items-list" id="catItemsList">
                    @foreach($categories as $category)
                    <label class="cuisine-item" id="cat-lbl-{{ $category->id }}">
                      <input type="radio" name="_cat_radio" value="{{ $category->id }}"
                             id="cat_rb_{{ $category->id }}" class="cat-rb d-none"
                             data-name="{{ $category->name }}"
                             {{ old('blog_category_id', $blog->blog_category_id ?? '') == $category->id ? 'checked' : '' }}
                             onchange="onCatChange(this)" />
                      <span class="cuisine-item-name">{{ $category->name }}</span>
                      <span class="cuisine-item-check"><i class="ti ti-check"></i></span>
                    </label>
                    @endforeach
                    <div class="cuisine-no-results d-none" id="catNoResults">
                      <i class="ti ti-search-off me-1"></i>No categories found
                    </div>
                  </div>
                  <div class="cuisine-divider"></div>
                  <div class="cuisine-items-list p-0">
                    <div class="cuisine-panel-footer">
                      <button type="button" class="btn btn-sm btn-link p-0 text-primary fw-semibold"
                              data-bs-toggle="modal" data-bs-target="#addCategoryModal" onclick="closeCatDrop()">
                        <i class="fas fa-plus-circle me-1"></i>Add New Category
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <input type="hidden" name="author_id" id="author_id" value="{{ old('author_id', $blog->author_id ?? '') }}" />

            {{-- TAGS --}}
            <div class="col-md-4">
              <label class="form-label fw-semibold">Tags</label>
              <div class="blog-tag-wrapper" id="blogTagWrapper">
                <div class="form-control d-flex flex-wrap align-items-center gap-1" id="blogTagInputContainer" style="min-height: 40px; cursor: text;" onclick="document.getElementById('blogTagInput').focus()">
                  <!-- Chips will go here -->
                  <input type="text" class="border-0 outline-none flex-grow-1" id="blogTagInput" placeholder="Type tag and press Enter" style="outline: none; min-width: 120px; font-size: 0.9rem;" autocomplete="off" />
                </div>
                <div id="blogTagInputsHidden" class="d-none">
                  <!-- Hidden inputs for form submission -->
                  @foreach($tags as $tag)
                    @if(is_array(old('tags')) && in_array($tag->id, old('tags')))
                      <input type="hidden" name="tags[]" value="{{ $tag->id }}" id="hidden_tag_{{ $tag->id }}" data-name="{{ $tag->name }}">
                    @endif
                  @endforeach
                </div>
              </div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            {{-- STATUS --}}
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="status">Status</label>
              <input type="hidden" name="status" id="status" value="{{ old('status', $blog->status ?? 'published') }}" />
              <div class="status-dropdown-wrapper" id="statusDropWrapper">
                <div class="status-dropdown-trigger" id="statusDropTrigger" onclick="toggleStatusDrop()">
                  <div class="status-selected-display" id="statusSelectedDisplay">
                    <span class="status-label">Published</span>
                  </div>
                  <i class="ti ti-chevron-down status-arrow" id="statusArrow"></i>
                </div>
                <div class="status-dropdown-panel" id="statusDropPanel" style="display:none;">
                  <div class="status-option active" data-value="published" data-name="Published" data-dot="published" onclick="onStatusChange(this)">
                    <div class="status-opt-info">
                      <span class="status-opt-name">Published</span>
                      <span class="status-opt-desc">Visible to everyone immediately</span>
                    </div>
                    <span class="status-opt-check"><i class="ti ti-check"></i></span>
                  </div>
                  <div class="status-option" data-value="draft" data-name="Draft" data-dot="draft" onclick="onStatusChange(this)">
                    <div class="status-opt-info">
                      <span class="status-opt-name">Draft</span>
                      <span class="status-opt-desc">Saved as draft, not visible publicly</span>
                    </div>
                    <span class="status-opt-check"><i class="ti ti-check"></i></span>
                  </div>
                  <div class="status-option" data-value="scheduled" data-name="Scheduled" data-dot="scheduled" onclick="onStatusChange(this)">
                    <div class="status-opt-info">
                      <span class="status-opt-name">Scheduled</span>
                      <span class="status-opt-desc">Publish automatically at a future date</span>
                    </div>
                    <span class="status-opt-check"><i class="ti ti-check"></i></span>
                  </div>
                </div>
              </div>
            </div>

            {{-- PUBLISH DATE --}}
            <div class="col-md-6" id="publish-date-wrapper" style="display: {{ old('status', $blog->status ?? 'published') == 'scheduled' ? 'block' : 'none' }};">
              <label class="form-label fw-semibold" for="published_at">Publish Date & Time</label>
              <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="{{ old('published_at', isset($blog->published_at) ? \Carbon\Carbon::parse($blog->published_at)->format('Y-m-d\TH:i') : '') }}" />
              <div class="text-danger small mt-1 d-none" id="pubdate-error-msg">Please select a publish date and time for scheduled post.</div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="content">Content <span class="text-danger">*</span></label>
            <textarea class="form-control" id="content" name="content" rows="14">{{ old('content', $blog->content ?? '') }}</textarea>
            <div class="text-danger small mt-1 d-none" id="content-error-msg">The content field is required.</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="excerpt">Excerpt</label>
            <textarea class="form-control" id="excerpt" name="excerpt" rows="3" placeholder="Brief summary of the blog post">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
          </div>

          {{-- AUTHOR INFORMATION SECTION --}}
          <hr class="my-4">
          <h5 class="fw-bold mb-3 text-warning"><i class="ti ti-user me-1"></i> Author Information</h5>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="author_name">Author Name</label>
              <input type="text" class="form-control" id="author_name" name="author_name" value="{{ old('author_name', $blog->author_name ?? '') }}" placeholder="Enter author name" />
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="author_designation">Author Designation</label>
              <input type="text" class="form-control" id="author_designation" name="author_designation" value="{{ old('author_designation', $blog->author_designation ?? '') }}" placeholder="e.g. Travel Blogger, Local Expert" />
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
                        @if(isset($blog) && $blog->author_avatar)
          <div class="mb-3 p-2 border rounded-3 bg-light" style="max-width:max-content;">
            <label class="form-label text-muted small fw-semibold d-block mb-2">Current Icon</label>
            <img src="{{ asset($blog->author_avatar) }}" class="rounded-circle object-fit-cover shadow-sm border border-2 border-warning" style="width: 80px; height: 80px;" />
          </div>
          @endif
              <label class="form-label fw-semibold" for="author_avatar_file">Author Profile Icon</label>
              <input type="file" class="form-control" id="author_avatar_file" name="author_avatar_file" accept="image/*" onchange="previewAuthorAvatar(event)" />
              <div class="form-text">Recommended: Square format (e.g., 150x150px).</div>
              <div class="mt-2" id="author-avatar-preview-container" style="display: none;">
                <img id="author-avatar-preview" src="" class="rounded-circle object-fit-cover shadow-sm border border-2 border-warning" style="width: 80px; height: 80px;" alt="Author Avatar Preview">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="author_avatar_alt">Author Icon Alt Text</label>
              <input type="text" class="form-control" id="author_avatar_alt" name="author_avatar_alt" value="{{ old('author_avatar_alt', $blog->author_avatar_alt ?? '') }}" placeholder="Enter author icon alt text" />
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="facebook_url">Facebook URL</label>
              <input type="text" class="form-control" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $blog->facebook_url ?? '') }}" placeholder="Facebook URL" />
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="twitter_url">Twitter URL</label>
              <input type="text" class="form-control" id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $blog->twitter_url ?? '') }}" placeholder="Twitter URL" />
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="linkedin_url">LinkedIn URL</label>
              <input type="text" class="form-control" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $blog->linkedin_url ?? '') }}" placeholder="LinkedIn URL" />
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="instagram_url">Instagram URL</label>
              <input type="text" class="form-control" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $blog->instagram_url ?? '') }}" placeholder="Instagram URL" />
            </div>
          </div>

        </div>{{-- /basic-pane --}}

        {{-- ============================================ --}}
        {{-- TAB 2: FEATURED IMAGE --}}
        {{-- ============================================ --}}
        <div class="tab-pane fade" id="image-pane" role="tabpanel">

          <div class="mb-3">
            
          @if(isset($blog) && $blog->featured_image)
          <div class="mb-4 p-3 border rounded-3 bg-light">
            <label class="form-label text-muted small fw-semibold d-block mb-2">Current Featured Image</label>
            <img src="{{ asset($blog->featured_image) }}"
                 alt="{{ $blog->featured_image_alt ?? $blog->title }}"
                 class="img-thumbnail d-block" style="max-height:280px; object-fit:cover;" />
          </div>
          @endif
          <label class="form-label fw-semibold" for="featured_image_file">{{ isset($blog) && $blog->featured_image ? 'Replace Featured Image' : 'Upload Featured Image' }}</label>
            <input class="form-control" type="file" id="featured_image_file"
                   name="featured_image_file" accept="image/*" onchange="previewImage(event)">
            <div class="form-text">Recommended: 1200├ù800px, JPG/PNG/WebP, max 2MB.</div>
          </div>

          <div class="mb-4 p-3 border rounded-3 d-none" id="featured-preview-wrap">
            <label class="form-label text-muted small fw-semibold d-block mb-2">New Image Preview</label>
            <img id="preview-img" src="" alt="Preview" class="img-thumbnail d-block" style="max-height:280px; object-fit:cover;" />
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="featured_image_alt">Image Alt Text <span class="text-muted fw-normal">(SEO)</span></label>
            <input type="text" class="form-control" id="featured_image_alt" name="featured_image_alt"
                   value="{{ old('featured_image_alt', $blog->featured_image_alt ?? '') }}"
                   placeholder="e.g. Scenic view of Traverse City waterfront" />
          </div>

        </div>{{-- /image-pane --}}

        {{-- ============================================ --}}
        {{-- TAB 3: SEO & SCHEMA --}}
        {{-- ============================================ --}}
        <div class="tab-pane fade" id="seo-pane" role="tabpanel">

          <div class="mb-3">
            <label class="form-label fw-semibold" for="meta_title">Meta Title</label>
            <input type="text" class="form-control" id="meta_title" name="meta_title"
                   value="{{ old('meta_title', $blog->meta_title ?? '') }}"
                   placeholder="e.g. Traverse City Travel Guide | Michigan Explorer" />
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <label class="form-label fw-semibold mb-1" for="meta_description">Meta Description</label>
              <span class="text-muted small" id="meta_desc_count">(0 / 160)</span>
            </div>
            <textarea class="form-control" id="meta_description" name="meta_description" rows="3"
                      maxlength="160"
                      placeholder="e.g. Discover the best attractions, restaurants, and tours in Traverse City with our travel guide.">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="canonical_url">Canonical URL</label>
            <input type="url" class="form-control" id="canonical_url" name="canonical_url"
                   value="{{ old('canonical_url', $blog->canonical_url ?? '') }}"
                   placeholder="e.g. https://michiganexplorer.com/blog/traverse-city-guide" />
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="og_title">OG Title</label>
            <input type="text" class="form-control" id="og_title" name="og_title"
                   value="{{ old('og_title', $blog->og_title ?? '') }}"
                   placeholder="e.g. Traverse City Travel Guide | Michigan Explorer" />
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <label class="form-label fw-semibold mb-1" for="og_description">OG Description</label>
              <span class="text-muted small" id="og_desc_count">(0 / 160)</span>
            </div>
            <textarea class="form-control" id="og_description" name="og_description" rows="3"
                      maxlength="160"
                      placeholder="e.g. Discover the best restaurants, beaches and hidden spots in Traverse City.">{{ old('og_description', $blog->og_description ?? '') }}</textarea>
          </div>

          <hr class="my-4">

          <h6 class="fw-bold text-muted mb-3"><i class="ti ti-code me-1"></i>Schema Markup</h6>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="schema_markup">JSON-LD Schema</label>
            <textarea class="form-control font-monospace" id="schema_markup" name="schema_markup" rows="10"
                      placeholder='{"@@context": "https://schema.org", "@@type": "BlogPosting", ...}'>{{ old('schema_markup', $blog->schema_markup ?? '') }}</textarea>
            <div class="form-text">Paste valid JSON-LD markup. Leave empty if not needed.</div>
          </div>

        </div>{{-- /seo-pane --}}

        <!-- Tab 5: FAQs -->
        <div class="tab-pane fade" id="faqs-pane" role="tabpanel">
          <div id="faqs-container">
            @if(isset($blog) && $blog->faqs)
              @foreach($blog->faqs as $index => $faq)
              <div class="card mb-3 faq-item border-info" id="faq_old_{{ $faq->id }}">
                <div class="card-header d-flex justify-content-between align-items-center py-2 bg-info-subtle">
                  <h6 class="card-title mb-0 text-info fw-bold"><i class="ti ti-help me-1"></i> FAQ #{{ $index + 1 }}</h6>
                  <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFaq('faq_old_{{ $faq->id }}')"><i class="ti ti-trash me-1"></i> Remove</button>
                </div>
                <div class="card-body p-3">
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Question</label>
                    <input type="text" class="form-control" name="faqs[{{ $index }}][question]" value="{{ $faq->question }}">
                  </div>
                  <div class="mb-0">
                    <label class="form-label fw-semibold">Answer</label>
                    <textarea class="form-control tinymce" name="faqs[{{ $index }}][answer]" rows="3">{{ $faq->answer }}</textarea>
                  </div>
                </div>
              </div>
              @endforeach
            @endif
          </div>
          <button type="button" class="btn btn-outline-primary mt-3" onclick="addFaq()">+ Add FAQ</button>
        </div>

      </div>{{-- /tab-content --}}

      