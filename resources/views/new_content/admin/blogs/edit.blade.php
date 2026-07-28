@extends('layouts/layoutMaster')

@section('title', 'Edit Blog: ' . $blog->title)

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blogs</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Blog</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h3 class="mb-1 fw-bold">Edit Blog</h3>
    <p class="text-muted mb-0 small">{{ $blog->title }}</p>
  </div>

</div>

<div class="card mb-4">
  <div class="card-body p-4">
    <form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data" id="blogEditForm">
      @csrf
      @method('PUT')

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
      <ul class="nav nav-tabs" id="blogFormTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic-pane" type="button" role="tab">
            <i class="ti ti-info-circle me-1"></i> Basic Info
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="image-tab" data-bs-toggle="tab" data-bs-target="#image-pane" type="button" role="tab">
            <i class="ti ti-photo me-1"></i> Featured Image
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button" role="tab">
            <i class="ti ti-world me-1"></i> SEO & Schema
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="faqs-tab" data-bs-toggle="tab" data-bs-target="#faqs-pane" type="button" role="tab">
            <i class="ti ti-help me-1"></i> FAQs
          </button>
        </li>
      </ul>

      <!-- Tabs Content -->
      <div class="tab-content pt-4" id="blogFormTabsContent">

        {{-- ============================================ --}}
        {{-- TAB 1: BASIC INFO --}}
        {{-- ============================================ --}}
        <div class="tab-pane fade show active" id="basic-pane" role="tabpanel">

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="title">Title <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('title') is-invalid @enderror"
                     id="title" name="title"
                     value="{{ old('title', $blog->title) }}"
                     placeholder="e.g. The Ultimate 3-Day Traverse City" />
              @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <div class="text-danger small mt-1 d-none" id="title-error-msg">The title field is required.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="slug">Slug <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text text-muted" style="font-size:0.8rem;">/blog/</span>
                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                       id="slug" name="slug"
                       value="{{ old('slug', $blog->slug) }}"
                       placeholder="e.g. the-ultimate-traverse-city" />
              </div>
              @error('slug') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              <div class="text-danger small mt-1 d-none" id="slug-error-msg">The slug field is required.</div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            {{-- CATEGORY --}}
            <div class="col-md-4">
              <label class="form-label fw-semibold" for="blog_category_id">Category</label>
              <input type="hidden" name="blog_category_id" id="blog_category_id"
                     value="{{ old('blog_category_id', $blog->blog_category_id) }}" />
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
                             {{ old('blog_category_id', $blog->blog_category_id) == $category->id ? 'checked' : '' }}
                             onchange="onCatChange(this)" />
                      <span class="cuisine-item-name">{{ $category->name }}</span>
                      <span class="cuisine-item-check"><i class="ti ti-check"></i></span>
                    </label>
                    @endforeach
                    <div class="cuisine-no-results d-none" id="catNoResults">
                      <i class="ti ti-search-off me-1"></i>No categories found
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <input type="hidden" name="author_id" id="author_id" value="{{ old('author_id', $blog->author_id) }}" />

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
                    @if(in_array($tag->id, old('tags', $selectedTags ?? [])))
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
            <textarea class="form-control" id="content" name="content" rows="14">{!! htmlspecialchars(old('content', $blog->content)) !!}</textarea>
            <div class="text-danger small mt-1 d-none" id="content-error-msg">The content field is required.</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="excerpt">Excerpt</label>
            <textarea class="form-control" id="excerpt" name="excerpt" rows="3"
                      placeholder="Short summary shown in blog listings and social share...">{{ old('excerpt', $blog->excerpt) }}</textarea>
            <div class="form-text">Leave blank to auto-generate from content.</div>
          </div>

          <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                   {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="is_featured">
              <i class="ti ti-star-filled text-warning me-1"></i>Featured Post
              <small class="text-muted fw-normal">(Shown in the homepage featured slider)</small>
            </label>
          </div>

          <hr class="my-4">
          <h5 class="mb-3 fw-bold text-muted"><i class="ti ti-user me-1"></i> Author Information</h5>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="author_name">Author Name</label>
              <input type="text" class="form-control" id="author_name" name="author_name" value="{{ old('author_name', $blog->author ? $blog->author->name : '') }}" placeholder="Enter Author Name" />
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="author_designation">Author Designation</label>
              <input type="text" class="form-control" id="author_designation" name="author_designation" value="{{ old('author_designation', $blog->author ? $blog->author->designation : '') }}" placeholder="Enter Author Designation" />
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="author_avatar_file">Author Icon</label>
              <div class="d-flex align-items-center gap-3">
                <input class="form-control" type="file" id="author_avatar_file" name="author_avatar_file" accept="image/*" onchange="previewAuthorAvatar(event)">
                <div id="author-avatar-preview-container" style="display: {{ $blog->author && $blog->author->avatar ? 'block' : 'none' }};">
                  <img id="author-avatar-preview" src="{{ $blog->author && $blog->author->avatar ? asset($blog->author->avatar) : '' }}" alt="Preview" class="rounded-circle border" style="width:50px;height:50px;object-fit:cover;" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="author_avatar_alt">Author Icon Alt Text</label>
              <input type="text" class="form-control" id="author_avatar_alt" name="author_avatar_alt" value="{{ old('author_avatar_alt', $blog->author ? $blog->author->avatar_alt : '') }}" placeholder="Enter author icon alt text" />
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="facebook_url">Facebook URL</label>
              <input type="text" class="form-control" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $blog->author ? $blog->author->facebook : '') }}" placeholder="Facebook URL" />
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="twitter_url">Twitter URL</label>
              <input type="text" class="form-control" id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $blog->author ? $blog->author->twitter : '') }}" placeholder="Twitter URL" />
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="linkedin_url">LinkedIn URL</label>
              <input type="text" class="form-control" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $blog->author ? $blog->author->linkedin : '') }}" placeholder="LinkedIn URL" />
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="instagram_url">Instagram URL</label>
              <input type="text" class="form-control" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $blog->author ? $blog->author->instagram : '') }}" placeholder="Instagram URL" />
            </div>
          </div>

        </div>{{-- /basic-pane --}}

        {{-- ============================================ --}}
        {{-- TAB 2: FEATURED IMAGE --}}
        {{-- ============================================ --}}
        <div class="tab-pane fade" id="image-pane" role="tabpanel">

          <h5 class="mb-3 fw-bold text-muted"><i class="ti ti-photo me-1"></i> Featured Image</h5>

          @if($blog->featured_image)
          <div class="mb-4 p-3 border rounded-3 bg-light">
            <label class="form-label text-muted small fw-semibold d-block mb-2">Current Featured Image</label>
            <img src="{{ asset($blog->featured_image) }}"
                 alt="{{ $blog->featured_image_alt ?? $blog->title }}"
                 class="img-thumbnail d-block" style="max-height:280px; object-fit:cover;" />
            @if($blog->featured_image_alt)
              <div class="form-text mt-2">
                <i class="ti ti-text-recognition me-1"></i>
                Current Alt: <strong>{{ $blog->featured_image_alt }}</strong>
              </div>
            @endif
          </div>
          @endif

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="featured_image_file">
                {{ $blog->featured_image ? 'Replace Featured Image' : 'Upload Featured Image' }}
              </label>
              <input class="form-control" type="file" id="featured_image_file"
                     name="featured_image_file" accept="image/*" onchange="previewImage(event)">
              <div class="form-text">Recommended: 1200×800px, JPG/PNG/WebP, max 2MB.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="featured_image_alt">Image Alt Text <span class="text-muted fw-normal">(SEO)</span></label>
              <input type="text" class="form-control" id="featured_image_alt" name="featured_image_alt"
                     value="{{ old('featured_image_alt', $blog->featured_image_alt) }}"
                     placeholder="e.g. Scenic view of Traverse City waterfront" />
              <div class="form-text">Describe the image for accessibility and search engines.</div>
            </div>
          </div>

          <div class="mb-4 p-3 border rounded-3 d-none" id="featured-preview-wrap">
            <label class="form-label text-muted small fw-semibold d-block mb-2">New Image Preview</label>
            <img id="preview-img" src="" alt="Preview" class="img-thumbnail d-block" style="max-height:280px; object-fit:cover;" />
          </div>

        </div>{{-- /image-pane --}}

        {{-- ============================================ --}}
        {{-- TAB 3: SEO & SCHEMA --}}
        {{-- ============================================ --}}
        <div class="tab-pane fade" id="seo-pane" role="tabpanel">

          <div class="mb-3">
            <label class="form-label fw-semibold" for="meta_title">Meta Title</label>
            <input type="text" class="form-control" id="meta_title" name="meta_title"
                   value="{{ old('meta_title', $blog->seo->meta_title ?? '') }}"
                   placeholder="e.g. Best 3-Day Traverse City Itinerary | Michigan Explorer" />
            <div class="form-text">Ideal length: 50–60 characters.</div>
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <label class="form-label fw-semibold" for="meta_description">Meta Description</label>
              <span class="text-muted small" id="meta_desc_count">(0 / 160)</span>
            </div>
            <textarea class="form-control" id="meta_description" name="meta_description" rows="3"
                      maxlength="160"
                      placeholder="e.g. Plan the perfect 3-day trip to Traverse City with our expert guide to dining, activities, and hidden gems.">{{ old('meta_description', $blog->seo->meta_description ?? '') }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="canonical_url">Canonical URL</label>
            <input type="url" class="form-control" id="canonical_url" name="canonical_url"
                   value="{{ old('canonical_url', $blog->seo->canonical_url ?? '') }}"
                   placeholder="e.g. https://www.michiganexplorer.com/blog/3-day-traverse-city" />
          </div>

          <hr class="my-4">

          <h6 class="fw-bold text-muted mb-3"><i class="ti ti-share me-1"></i>Open Graph (Social Share)</h6>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="og_title">OG Title</label>
            <input type="text" class="form-control" id="og_title" name="og_title"
                   value="{{ old('og_title', $blog->seo->og_title ?? '') }}"
                   placeholder="e.g. Best 3-Day Traverse City Itinerary" />
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <label class="form-label fw-semibold" for="og_description">OG Description</label>
              <span class="text-muted small" id="og_desc_count">(0 / 160)</span>
            </div>
            <textarea class="form-control" id="og_description" name="og_description" rows="3"
                      maxlength="160"
                      placeholder="e.g. Discover the best restaurants, beaches and hidden spots in Traverse City.">{{ old('og_description', $blog->seo->og_description ?? '') }}</textarea>
          </div>

          <hr class="my-4">

          <h6 class="fw-bold text-muted mb-3"><i class="ti ti-code me-1"></i>Schema Markup</h6>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="schema_markup">JSON-LD Schema</label>
            <textarea class="form-control font-monospace" id="schema_markup" name="schema_markup" rows="10"
                      placeholder='{"@@context": "https://schema.org", "@@type": "BlogPosting", ...}'>{{ old('schema_markup', $blog->seo->schema_markup ?? '') }}</textarea>
            <div class="form-text">Paste valid JSON-LD markup. Leave empty if not needed.</div>
          </div>

        </div>{{-- /seo-pane --}}

        <!-- Tab 5: FAQs -->
        <div class="tab-pane fade" id="faqs-pane" role="tabpanel">
          <div id="faqs-container">
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
          </div>
          <button type="button" class="btn btn-outline-primary mt-3" onclick="addFaq()">+ Add FAQ</button>
        </div>

      </div>{{-- /tab-content --}}

      <!-- Form Actions -->
      <div class="d-flex justify-content-between align-items-center pt-4 mt-3 border-top">
        <a href="{{ route('blogs.index') }}" class="btn btn-outline-secondary">
          <i class="ti ti-x me-1"></i> Cancel
        </a>
        <button type="submit" class="btn btn-warning text-white px-5">
          <i class="ti ti-device-floppy me-1"></i> Update Blog
        </button>
      </div>

    </form>
  </div>
</div>

<!-- Blog Dropdowns Custom CSS -->
<style>
/* ── Cuisine-style Dropdown (Category & Author) ── */
.cuisine-dropdown-wrapper { position: relative; }
.cuisine-dropdown-trigger {
  display: flex; align-items: center; justify-content: space-between;
  border: 1px solid #d9dee3; border-radius: 6px; padding: 8px 12px;
  cursor: pointer; background: #fff; min-height: 40px; gap: 8px;
  transition: border-color 0.2s;
}
.cuisine-dropdown-trigger:hover { border-color: #7367f0; }
.cuisine-tags-area { display: flex; flex-wrap: wrap; gap: 5px; flex: 1; min-height: 22px; align-items: center; }
.cuisine-placeholder { color: #aaa; font-size: 0.88rem; display: flex; align-items: center; }
.cuisine-dropdown-arrow { color: #aaa; transition: transform 0.2s; font-size: 1rem; flex-shrink: 0; }
.cuisine-dropdown-panel {
  position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 1050;
  background: #fff; border: 1px solid #e0e0e0; border-radius: 8px;
  box-shadow: 0 6px 24px rgba(0,0,0,0.12); overflow: hidden;
}
.cuisine-search-wrap {
  display: flex; align-items: center; gap: 8px; padding: 10px 12px;
  border-bottom: 1px solid #f0f0f0; background: #fafafa;
}
.cuisine-search-icon { color: #aaa; font-size: 0.9rem; }
.cuisine-search-input {
  border: none; outline: none; width: 100%; font-size: 0.9rem; background: transparent;
}
.cuisine-divider { height: 1px; background: #f0f0f0; }
.cuisine-items-list { max-height: 220px; overflow-y: auto; }
.cuisine-item {
  display: flex; align-items: center; justify-content: space-between;
  padding: 9px 14px; cursor: pointer; transition: background 0.15s; font-size: 0.88rem;
}
.cuisine-item:hover { background: #f5f3ff; }
.cuisine-item.selected { background: #f0edff; color: #7367f0; font-weight: 500; }
.cuisine-item-check { color: #7367f0; display: none; }
.cuisine-item.selected .cuisine-item-check { display: inline; }
.cuisine-no-results { padding: 12px; text-align: center; color: #aaa; font-size: 0.88rem; }

/* ── Blog Tag Dropdown ── */
.blog-tag-wrapper { position: relative; }
.blog-tag-trigger {
  display: flex; align-items: center; justify-content: space-between;
  border: 1px solid #d9dee3; border-radius: 6px; padding: 8px 12px;
  cursor: pointer; background: #fff; min-height: 40px; gap: 8px;
  transition: border-color 0.2s;
}
.blog-tag-trigger:hover { border-color: #7367f0; }
.blog-tags-area { display: flex; flex-wrap: wrap; gap: 5px; flex: 1; min-height: 22px; }
.blog-tag-placeholder { color: #aaa; font-size: 0.88rem; display: flex; align-items: center; }
.blog-tag-chip {
  display: inline-flex; align-items: center; gap: 4px;
  background: #f0edff; color: #7367f0; border: 1px solid #d5ccff;
  border-radius: 20px; padding: 2px 10px; font-size: 0.8rem; font-weight: 500;
  white-space: nowrap;
}
.blog-tag-chip-remove { cursor: pointer; font-size: 0.75rem; color: #7367f0; }
.blog-tag-chip-remove:hover { color: #ff3e1d; }
.blog-tag-arrow { color: #aaa; transition: transform 0.2s; font-size: 1rem; flex-shrink: 0; }
.blog-tag-panel {
  position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 1050;
  background: #fff; border: 1px solid #e0e0e0; border-radius: 8px;
  box-shadow: 0 6px 24px rgba(0,0,0,0.12); overflow: hidden;
}
.blog-tag-search-wrap {
  display: flex; align-items: center; gap: 8px; padding: 10px 12px;
  border-bottom: 1px solid #f0f0f0; background: #fafafa;
}
.blog-tag-search-icon { color: #aaa; font-size: 0.9rem; }
.blog-tag-search-input {
  border: none; outline: none; width: 100%; font-size: 0.9rem; background: transparent;
}
.blog-tag-divider { height: 1px; background: #f0f0f0; }
.blog-tag-items { max-height: 220px; overflow-y: auto; }
.blog-tag-item {
  display: flex; align-items: center; justify-content: space-between;
  padding: 9px 14px; cursor: pointer; transition: background 0.15s; font-size: 0.88rem;
}
.blog-tag-item:hover { background: #f5f3ff; }
.blog-tag-item.selected { background: #f0edff; color: #7367f0; font-weight: 500; }
.blog-tag-item-check { color: #7367f0; display: none; }
.blog-tag-item.selected .blog-tag-item-check { display: inline; }
.blog-tag-no-results { padding: 12px; text-align: center; color: #aaa; font-size: 0.88rem; }

/* ── Status Dropdown ── */
.status-dropdown-wrapper { position: relative; }
.status-dropdown-trigger {
  display: flex; align-items: center; justify-content: space-between;
  border: 1px solid #d9dee3; border-radius: 6px; padding: 10px 14px;
  cursor: pointer; background: #fff; min-height: 42px;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.status-dropdown-trigger:hover { border-color: #7367f0; }
.status-dropdown-trigger.open { border-color: #7367f0; box-shadow: 0 0 0 3px rgba(115,103,240,.12); }
.status-selected-display { display: flex; align-items: center; gap: 10px; }
.status-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; background: #ccc; }
.status-dot.draft     { background: #a8b1c0; }
.status-dot.published { background: #28c76f; }
.status-dot.scheduled { background: #ff9f43; }
.status-label { font-size: 0.9rem; font-weight: 500; color: #333; }
.status-arrow { color: #aaa; transition: transform 0.2s; font-size: 1rem; }
.status-dropdown-panel {
  position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 1060;
  background: #fff; border: 1px solid #e0e0e0; border-radius: 10px;
  box-shadow: 0 8px 28px rgba(0,0,0,0.13); overflow: hidden;
}
.status-option {
  display: flex; align-items: center; gap: 12px;
  padding: 12px 16px; cursor: pointer; transition: background 0.15s;
}
.status-option:hover  { background: #f8f7ff; }
.status-option.active { background: #f0edff; }
.status-opt-dot { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; }
.status-opt-dot.draft     { background: #a8b1c0; }
.status-opt-dot.published { background: #28c76f; }
.status-opt-dot.scheduled { background: #ff9f43; }
.status-opt-info  { display: flex; flex-direction: column; flex: 1; }
.status-opt-name  { font-size: 0.88rem; font-weight: 600; color: #333; }
.status-opt-desc  { font-size: 0.78rem; color: #999; }
.status-opt-check { color: #7367f0; display: none; font-size: 0.95rem; }
.status-option.active .status-opt-check { display: inline; }
</style>

@endsection

@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
/* ── Image Preview ── */
function previewImage(event) {
  const reader = new FileReader();
  reader.onload = function() {
    document.getElementById('preview-img').src = reader.result;
    document.getElementById('featured-preview-wrap').classList.remove('d-none');
  };
  reader.readAsDataURL(event.target.files[0]);
}

/* ── Tag Dropdown ── */
function toggleTagDropdown() {
  const panel = document.getElementById('blogTagPanel');
  const arrow = document.getElementById('blogTagArrow');
  const isOpen = panel.style.display !== 'none';
  panel.style.display = isOpen ? 'none' : 'block';
  arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
  if (!isOpen) document.getElementById('blogTagSearchInput').focus();
}
function closeTagDropdown() {
  const panel = document.getElementById('blogTagPanel');
  if (panel) {
    panel.style.display = 'none';
    document.getElementById('blogTagArrow').style.transform = 'rotate(0deg)';
  }
}
function filterTags(val) {
  const term = val.toLowerCase();
  const items = document.querySelectorAll('#blogTagItems .blog-tag-item');
  let found = 0;
  items.forEach(item => {
    const name = item.querySelector('.blog-tag-item-name').textContent.toLowerCase();
    const show = name.includes(term);
    item.style.display = show ? '' : 'none';
    if (show) found++;
  });
  document.getElementById('blogTagNoResults').classList.toggle('d-none', found > 0);
}


// Non-dropdown dynamic tag handlers
document.addEventListener('DOMContentLoaded', function() {
  const tagInput = document.getElementById('blogTagInput');
  const hiddenContainer = document.getElementById('blogTagInputsHidden');

  function renderInitialChips() {
    if (!hiddenContainer) return;
    hiddenContainer.querySelectorAll('input').forEach(input => {
      const val = input.value;
      const name = input.getAttribute('data-name') || val;
      addTagChip(val, name);
    });
  }

  function addTagChip(val, name) {
    const container = document.getElementById('blogTagInputContainer');
    if (!container || container.querySelector(`[data-val="${val}"]`)) return;
    const chip = document.createElement('span');
    chip.className = 'blog-tag-chip';
    chip.setAttribute('data-val', val);
    chip.innerHTML = `${name}<span class="blog-tag-chip-remove" onclick="removeBlogTag('${val}')">&times;</span>`;
    container.insertBefore(chip, tagInput);
  }

  window.removeBlogTag = function(val) {
    const container = document.getElementById('blogTagInputContainer');
    if (container) {
      const chip = container.querySelector(`[data-val="${val}"]`);
      if (chip) chip.remove();
    }
    const input = hiddenContainer.querySelector(`input[value="${val}"]`);
    if (input) input.remove();
  };

  if (tagInput) {
    tagInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        const val = this.value.trim();
        if (!val) return;
        const cleanVal = val.replace(/[^a-zA-Z0-9\s-]/g, '');
        const exists = Array.from(hiddenContainer.querySelectorAll('input')).some(input => {
          return input.value.toLowerCase() === cleanVal.toLowerCase() || (input.getAttribute('data-name') && input.getAttribute('data-name').toLowerCase() === cleanVal.toLowerCase());
        });

        if (!exists) {
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'tags[]';
          input.value = cleanVal;
          input.setAttribute('data-name', cleanVal);
          hiddenContainer.appendChild(input);
          addTagChip(cleanVal, cleanVal);
        }
        this.value = '';
      }
    });
  }

  renderInitialChips();
});

document.addEventListener('DOMContentLoaded', function() {

  /* ── Init TinyMCE ── */
  tinymce.init({
    selector: '#content, .tinymce',
    height: 500,
    menubar: false,
    plugins: [
      'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
      'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
      'insertdatetime', 'media', 'table', 'wordcount', 'help'
    ],
    toolbar:
      'undo redo | blocks | bold italic underline forecolor | ' +
      'alignleft aligncenter alignright alignjustify | ' +
      'bullist numlist outdent indent | link image media | code fullscreen | help',
    content_style: 'body { font-family: Inter, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 1.7; }',
    setup: function(editor) {
      editor.on('change input', function() {
        tinymce.triggerSave();
        const val = document.getElementById('content').value.trim();
        document.getElementById('content-error-msg').classList.toggle('d-none', !!val);
      });
    }
  });

  /* ── Character Counter ── */
  ['meta_description:meta_desc_count', 'og_description:og_desc_count'].forEach(pair => {
    const [inputId, countId] = pair.split(':');
    const input = document.getElementById(inputId);
    const count = document.getElementById(countId);
    if (input && count) {
      const update = () => { count.textContent = `(${input.value.length} / 160)`; };
      input.addEventListener('input', update);
      update();
    }
  });

  /* ── Category Dropdown ── */
  function toggleCatDrop() {
    const p = document.getElementById('catDropPanel'), a = document.getElementById('catArrow');
    const open = p.style.display !== 'none';
    p.style.display = open ? 'none' : 'block';
    a.style.transform = open ? '' : 'rotate(180deg)';
    if (!open) document.getElementById('catSearchInput').focus();
  }
  function closeCatDrop() {
    const p = document.getElementById('catDropPanel');
    if (p) { p.style.display = 'none'; document.getElementById('catArrow').style.transform = ''; }
  }
  function filterCat(val) {
    const t = val.toLowerCase();
    let found = 0;
    document.querySelectorAll('#catItemsList .cuisine-item').forEach(item => {
      const show = item.querySelector('.cuisine-item-name').textContent.toLowerCase().includes(t);
      item.style.display = show ? '' : 'none';
      if (show) found++;
    });
    document.getElementById('catNoResults').classList.toggle('d-none', found > 0);
  }
  function onCatChange(rb) {
    document.getElementById('blog_category_id').value = rb.value;
    const area = document.getElementById('catTagsArea'), ph = document.getElementById('catPlaceholder');
    ph.style.display = 'none';
    area.querySelectorAll('.cuisine-sel-tag').forEach(t => t.remove());
    const tag = document.createElement('span');
    tag.className = 'cuisine-sel-tag';
    tag.style.cssText = 'font-size:0.9rem;color:#333;';
    tag.textContent = rb.dataset.name;
    area.insertBefore(tag, ph);
    document.querySelectorAll('#catItemsList .cuisine-item').forEach(item => {
      item.classList.toggle('selected', item.querySelector('.cat-rb')?.value == rb.value);
    });
    closeCatDrop();
  }
  window.toggleCatDrop = toggleCatDrop; window.closeCatDrop = closeCatDrop;
  function togglePublishDate(val) {
    const wrapper = document.getElementById('publish-date-wrapper');
    if (wrapper) {
      wrapper.style.display = val === 'scheduled' ? 'block' : 'none';
    }
  }
  window.togglePublishDate = togglePublishDate;

  /* Status Dropdown JS */
  function toggleStatusDrop() {
    const p = document.getElementById('statusDropPanel'), a = document.getElementById('statusArrow'), t = document.getElementById('statusDropTrigger');
    const open = p.style.display !== 'none';
    p.style.display = open ? 'none' : 'block';
    t.classList.toggle('open', !open);
    if (open) {
      a.style.transform = 'rotate(0deg)';
    } else {
      a.style.transform = 'rotate(180deg)';
    }
  }
  function closeStatusDrop() {
    const p = document.getElementById('statusDropPanel'), a = document.getElementById('statusArrow'), t = document.getElementById('statusDropTrigger');
    if (p) {
      p.style.display = 'none';
      t.classList.remove('open');
      a.style.transform = 'rotate(0deg)';
    }
  }
  function onStatusChange(opt) {
    const val = opt.getAttribute('data-value');
    const name = opt.getAttribute('data-name');
    const dotClass = opt.getAttribute('data-dot');
    
    document.getElementById('status').value = val;
    
    const display = document.getElementById('statusSelectedDisplay');
    display.innerHTML = `<span class="status-label">${name}</span>`;
    
    document.querySelectorAll('#statusDropPanel .status-option').forEach(item => {
      item.classList.toggle('active', item.getAttribute('data-value') === val);
    });
    
    closeStatusDrop();
    togglePublishDate(val);
  }
  window.toggleStatusDrop = toggleStatusDrop;
  window.closeStatusDrop = closeStatusDrop;
  window.onStatusChange = onStatusChange;

  /* ── Author Dropdown ── */
  // Preview author avatar
  window.previewAuthorAvatar = function(event) {
    const reader = new FileReader();
    reader.onload = function(){
      const output = document.getElementById('author-avatar-preview');
      output.src = reader.result;
      document.getElementById('author-avatar-preview-container').style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
  };

  /* ── Init all dropdowns from saved values ── */
  (function() {
    // Category
    const catRb = document.querySelector('#catItemsList .cat-rb:checked');
    if (catRb) onCatChange(catRb);
    
    // Status
    const savedStatus = document.getElementById('status').value;
    const opt = document.querySelector(`#statusDropPanel .status-option[data-value="${savedStatus}"]`);
    if (opt) onStatusChange(opt);
  })();

  // Dynamic FAQs
  let faqIndex = {{ $blog->faqs->count() }};
  window.addFaq = function() {
    const container = document.getElementById('faqs-container');
    const id = 'faq_' + faqIndex;
    const html = `
      <div class="card mb-3 faq-item border-info" id="${id}">
        <div class="card-header d-flex justify-content-between align-items-center py-2 bg-info-subtle">
          <h6 class="card-title mb-0 text-info fw-bold"><i class="ti ti-help me-1"></i> New FAQ</h6>
          <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFaq('${id}')"><i class="ti ti-trash me-1"></i> Remove</button>
        </div>
        <div class="card-body p-3">
          <div class="mb-3">
            <label class="form-label fw-semibold">Question</label>
            <input type="text" class="form-control" name="faqs[${faqIndex}][question]">
          </div>
          <div class="mb-0">
            <label class="form-label fw-semibold">Answer</label>
            <textarea class="form-control tinymce" id="faq_answer_${faqIndex}" name="faqs[${faqIndex}][answer]" rows="3"></textarea>
          </div>
        </div>
      </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    
    if (typeof tinymce !== 'undefined') {
      tinymce.init({
        selector: '#faq_answer_' + faqIndex,
        height: 200,
        menubar: false,
        plugins: ['lists', 'link', 'code', 'help', 'wordcount'],
        toolbar: 'undo redo | bold italic | bullist numlist | link code | removeformat'
      });
    }
    faqIndex++;
  };
  window.removeFaq = function(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
  };

  /* ── Outside click closes all ── */
  document.addEventListener('click', function(e) {
    if (!document.getElementById('catDropWrapper')?.contains(e.target))   closeCatDrop();
    if (!document.getElementById('statusDropWrapper')?.contains(e.target)) closeStatusDrop();
  });

  /* ── Auto slug ── */
  const titleInput = document.getElementById('title');
  const slugInput  = document.getElementById('slug');
  if (titleInput && slugInput) {
    titleInput.addEventListener('input', function() {
      slugInput.value = this.value.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/(^-|-$)+/g, '');
      slugInput.classList.remove('is-invalid');
      document.getElementById('slug-error-msg').classList.add('d-none');
    });
    titleInput.addEventListener('input', function() {
      if (this.value.trim()) {
        this.classList.remove('is-invalid');
        document.getElementById('title-error-msg').classList.add('d-none');
      }
    });
    slugInput.addEventListener('input', function() {
      if (this.value.trim()) {
        this.classList.remove('is-invalid');
        document.getElementById('slug-error-msg').classList.add('d-none');
      }
    });
  }



  /* ── Validation ── */
  function validateBasicInfo() {
    if (typeof tinymce !== 'undefined') tinymce.triggerSave();

    const title    = document.getElementById('title');
    const slug     = document.getElementById('slug');
    const content  = document.getElementById('content');
    const status   = document.getElementById('status');
    const pubDate  = document.getElementById('published_at');
    let   isValid  = true;

    const check = (input, errorId) => {
      const empty = !input || !input.value.trim();
      if (empty) { isValid = false; if (input) input.classList.add('is-invalid'); }
      else { if (input) input.classList.remove('is-invalid'); }
      document.getElementById(errorId)?.classList.toggle('d-none', !empty);
    };
    check(title,   'title-error-msg');
    check(slug,    'slug-error-msg');
    check(content, 'content-error-msg');

    if (status && status.value === 'scheduled') {
      if (!pubDate || !pubDate.value.trim()) {
        isValid = false;
        if (pubDate) pubDate.classList.add('is-invalid');
      } else {
        if (pubDate) pubDate.classList.remove('is-invalid');
      }
    }

    return isValid;
  }

  /* Validate on tab switch */
  document.querySelectorAll('#blogFormTabs button').forEach(btn => {
    btn.addEventListener('show.bs.tab', () => validateBasicInfo());
  });

  /* Submit handler */
  document.getElementById('blogEditForm')?.addEventListener('submit', function(e) {
    const isValid = validateBasicInfo();
    if (!isValid) {
      e.preventDefault();
      const basicTab = document.getElementById('basic-tab');
      if (basicTab && !basicTab.classList.contains('active')) {
        new bootstrap.Tab(basicTab).show();
      }
      setTimeout(() => {
        const titleEl = document.getElementById('title');
        const slugEl  = document.getElementById('slug');
        if (titleEl && !titleEl.value.trim()) titleEl.focus();
        else if (slugEl && !slugEl.value.trim()) slugEl.focus();
      }, 250);
    }
  });

}); // DOMContentLoaded
</script>
@endsection
