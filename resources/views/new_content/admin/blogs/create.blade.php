@extends('layouts/layoutMaster')

@section('title', 'Add Blog')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blogs</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Blog</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h3 class="mb-1 fw-bold">Add Blog</h3>
    <p class="text-muted mb-0 small">Create a new blog post or article.</p>
  </div>
 
</div>

<div class="card mb-4">
  <div class="card-body p-4">
    <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogCreateForm">
      @csrf

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
                     value="{{ old('title') }}"
                     placeholder="e.g. The Ultimate 3-Day Traverse City" />
              @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <div class="text-danger small mt-1 d-none" id="title-error-msg">The title field is required.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="slug">Slug <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                       id="slug" name="slug"
                       value="{{ old('slug') }}"
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
                     value="{{ old('blog_category_id') }}" />
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
                             {{ old('blog_category_id') == $category->id ? 'checked' : '' }}
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

            <input type="hidden" name="author_id" id="author_id" value="{{ old('author_id') }}" />

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
              <input type="hidden" name="status" id="status" value="{{ old('status', 'published') }}" />
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
            <div class="col-md-6" id="publish-date-wrapper" style="display: {{ old('status', 'published') == 'scheduled' ? 'block' : 'none' }};">
              <label class="form-label fw-semibold" for="published_at">Publish Date & Time</label>
              <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="{{ old('published_at') }}" />
              <div class="text-danger small mt-1 d-none" id="pubdate-error-msg">Please select a publish date and time for scheduled post.</div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="content">Content <span class="text-danger">*</span></label>
            <textarea class="form-control" id="content" name="content" rows="14">{!! htmlspecialchars(old('content')) !!}</textarea>
            <div class="text-danger small mt-1 d-none" id="content-error-msg">The content field is required.</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="excerpt">Excerpt</label>
            <textarea class="form-control" id="excerpt" name="excerpt" rows="3" placeholder="Brief summary of the blog post">{{ old('excerpt') }}</textarea>
          </div>

          {{-- AUTHOR INFORMATION SECTION --}}
          <hr class="my-4">
          <h5 class="fw-bold mb-3 text-warning"><i class="ti ti-user me-1"></i> Author Information</h5>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="author_name">Author Name</label>
              <input type="text" class="form-control" id="author_name" name="author_name" value="{{ old('author_name') }}" placeholder="Enter author name" />
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="author_designation">Author Designation</label>
              <input type="text" class="form-control" id="author_designation" name="author_designation" value="{{ old('author_designation') }}" placeholder="e.g. Travel Blogger, Local Expert" />
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="author_avatar_file">Author Profile Icon</label>
              <input type="file" class="form-control" id="author_avatar_file" name="author_avatar_file" accept="image/*" onchange="previewAuthorAvatar(event)" />
              <div class="form-text">Recommended: Square format (e.g., 150x150px).</div>
              <div class="mt-2" id="author-avatar-preview-container" style="display: none;">
                <img id="author-avatar-preview" src="" class="rounded-circle object-fit-cover shadow-sm border border-2 border-warning" style="width: 80px; height: 80px;" alt="Author Avatar Preview">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="author_avatar_alt">Author Icon Alt Text</label>
              <input type="text" class="form-control" id="author_avatar_alt" name="author_avatar_alt" value="{{ old('author_avatar_alt') }}" placeholder="Enter author icon alt text" />
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="facebook_url">Facebook URL</label>
              <input type="text" class="form-control" id="facebook_url" name="facebook_url" value="{{ old('facebook_url') }}" placeholder="Facebook URL" />
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="twitter_url">Twitter URL</label>
              <input type="text" class="form-control" id="twitter_url" name="twitter_url" value="{{ old('twitter_url') }}" placeholder="Twitter URL" />
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="linkedin_url">LinkedIn URL</label>
              <input type="text" class="form-control" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url') }}" placeholder="LinkedIn URL" />
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="instagram_url">Instagram URL</label>
              <input type="text" class="form-control" id="instagram_url" name="instagram_url" value="{{ old('instagram_url') }}" placeholder="Instagram URL" />
            </div>
          </div>

        </div>{{-- /basic-pane --}}

        {{-- ============================================ --}}
        {{-- TAB 2: FEATURED IMAGE --}}
        {{-- ============================================ --}}
        <div class="tab-pane fade" id="image-pane" role="tabpanel">

          <div class="mb-3">
            <label class="form-label fw-semibold" for="featured_image_file">Upload Featured Image</label>
            <input class="form-control" type="file" id="featured_image_file"
                   name="featured_image_file" accept="image/*" onchange="previewImage(event)">
            <div class="form-text">Recommended: 1200×800px, JPG/PNG/WebP, max 2MB.</div>
          </div>

          <div class="mb-4 p-3 border rounded-3 d-none" id="featured-preview-wrap">
            <label class="form-label text-muted small fw-semibold d-block mb-2">New Image Preview</label>
            <img id="preview-img" src="" alt="Preview" class="img-thumbnail d-block" style="max-height:280px; object-fit:cover;" />
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="featured_image_alt">Image Alt Text <span class="text-muted fw-normal">(SEO)</span></label>
            <input type="text" class="form-control" id="featured_image_alt" name="featured_image_alt"
                   value="{{ old('featured_image_alt') }}"
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
                   value="{{ old('meta_title') }}"
                   placeholder="e.g. Traverse City Travel Guide | Michigan Explorer" />
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <label class="form-label fw-semibold mb-1" for="meta_description">Meta Description</label>
              <span class="text-muted small" id="meta_desc_count">(0 / 160)</span>
            </div>
            <textarea class="form-control" id="meta_description" name="meta_description" rows="3"
                      maxlength="160"
                      placeholder="e.g. Discover the best attractions, restaurants, and tours in Traverse City with our travel guide.">{{ old('meta_description') }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="canonical_url">Canonical URL</label>
            <input type="url" class="form-control" id="canonical_url" name="canonical_url"
                   value="{{ old('canonical_url') }}"
                   placeholder="e.g. https://michiganexplorer.com/blog/traverse-city-guide" />
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="og_title">OG Title</label>
            <input type="text" class="form-control" id="og_title" name="og_title"
                   value="{{ old('og_title') }}"
                   placeholder="e.g. Traverse City Travel Guide | Michigan Explorer" />
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <label class="form-label fw-semibold mb-1" for="og_description">OG Description</label>
              <span class="text-muted small" id="og_desc_count">(0 / 160)</span>
            </div>
            <textarea class="form-control" id="og_description" name="og_description" rows="3"
                      maxlength="160"
                      placeholder="e.g. Discover the best restaurants, beaches and hidden spots in Traverse City.">{{ old('og_description') }}</textarea>
          </div>

          <hr class="my-4">

          <h6 class="fw-bold text-muted mb-3"><i class="ti ti-code me-1"></i>Schema Markup</h6>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="schema_markup">JSON-LD Schema</label>
            <textarea class="form-control font-monospace" id="schema_markup" name="schema_markup" rows="10"
                      placeholder='{"@@context": "https://schema.org", "@@type": "BlogPosting", ...}'>{{ old('schema_markup') }}</textarea>
            <div class="form-text">Paste valid JSON-LD markup. Leave empty if not needed.</div>
          </div>

        </div>{{-- /seo-pane --}}

        <!-- Tab 5: FAQs -->
        <div class="tab-pane fade" id="faqs-pane" role="tabpanel">
          <div id="faqs-container"></div>
          <button type="button" class="btn btn-outline-primary mt-3" onclick="addFaq()">+ Add FAQ</button>
        </div>

      </div>{{-- /tab-content --}}

      <!-- Form Actions -->
      <div class="mt-4 pt-3 border-top">
        <button type="submit" class="btn btn-warning text-white me-2">Save</button>
        <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Cancel</a>
      </div>

    </form>
  </div>
</div>

{{-- ===== Add Category Modal ===== --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryModalLabel"><i class="fas fa-plus-circle me-1 text-primary"></i>Add New Category</h5>
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
          <span id="saveCategoryBtnText"><i class="fas fa-plus-circle me-1"></i>Add Category</span>
          <span id="saveCategoryBtnSpinner" class="d-none"><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
        </button>
      </div>
    </div>
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
.cuisine-panel-footer { display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; background: #f8f7ff; }

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
.status-selected-display .status-label { font-size: 0.9rem; font-weight: 500; color: #333; }
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
    const items = document.querySelectorAll('#catItemsList .cuisine-item');
    let found = 0;
    items.forEach(item => {
      const name = item.querySelector('.cuisine-item-name').textContent.toLowerCase();
      const show = name.includes(t);
      item.style.display = show ? '' : 'none';
      if (show) found++;
    });
    document.getElementById('catNoResults').classList.toggle('d-none', found > 0);
  }
  function onCatChange(rb) {
    const p = document.getElementById('catPlaceholder');
    const items = document.querySelectorAll('#catItemsList .cuisine-item');
    items.forEach(item => {
      const innerRb = item.querySelector('.cat-rb');
      item.classList.toggle('selected', innerRb && innerRb.checked);
    });
    document.getElementById('blog_category_id').value = rb.value;
    p.innerHTML = `<i class="ti ti-category me-1 text-muted"></i>${rb.dataset.name}`;
    p.style.color = '#333';
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

  function saveNewCategory() {
    const name = document.getElementById('new_category_name').value.trim();
    const slug = document.getElementById('new_category_slug').value.trim();
    const alertBox = document.getElementById('category-modal-alert');
    if (!name || !slug) { alertBox.className = 'alert alert-danger'; alertBox.textContent = 'Please enter name and slug.'; return; }
    alertBox.className = 'd-none';
    document.getElementById('saveCategoryBtnText').classList.add('d-none');
    document.getElementById('saveCategoryBtnSpinner').classList.remove('d-none');
    $.ajax({
      url: '{{ route("blog-categories.quick-store") }}', type: 'POST',
      data: { _token: '{{ csrf_token() }}', name, slug, status: 1 },
      success: function(response) {
        if (response.success) {
          const cat = response.category;
          const list = document.getElementById('catItemsList');
          const lbl = document.createElement('label');
          lbl.className = 'cuisine-item';
          lbl.id = 'cat-lbl-' + cat.id;
          lbl.innerHTML = `
            <input type="radio" name="_cat_radio" value="${cat.id}" id="cat_rb_${cat.id}" class="cat-rb d-none" data-name="${cat.name}" onchange="onCatChange(this)" />
            <span class="cuisine-item-name">${cat.name}</span>
            <span class="cuisine-item-check"><i class="ti ti-check"></i></span>
          `;
          list.insertBefore(lbl, list.firstChild);
          
          const newRb = lbl.querySelector('input[type="radio"]');
          newRb.checked = true;
          onCatChange(newRb);

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
  window.saveNewCategory = saveNewCategory;
  window.filterCat = filterCat;
  window.onCatChange = onCatChange;

  document.addEventListener('click', function(e) {
    if (!document.getElementById('catDropWrapper')?.contains(e.target) && !e.target.closest('#addCategoryModal')) closeCatDrop();
    if (!document.getElementById('statusDropWrapper')?.contains(e.target)) closeStatusDrop();
  });

  /* ── Auto slug ── */
  const title = document.getElementById('title'), slug = document.getElementById('slug');
  if (title && slug) {
    title.addEventListener('input', function() {
      slug.value = this.value.toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)+/g, '');
    });
  }

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
  let faqIndex = 0;
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

  /* ── Preview author avatar ── */
  window.previewAuthorAvatar = function(event) {
    const reader = new FileReader();
    reader.onload = function(){
      document.getElementById('author-avatar-preview').src = reader.result;
      document.getElementById('author-avatar-preview-container').style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
  };

  /* ── Validation ── */
  function validateBasicInfo() {
    if (typeof tinymce !== 'undefined') tinymce.triggerSave();
    const t = document.getElementById('title'), s = document.getElementById('slug'), c = document.getElementById('content');
    let ok = true;
    if (!t.value.trim()) { t.classList.add('is-invalid'); document.getElementById('title-error-msg').classList.remove('d-none'); ok = false; }
    else { t.classList.remove('is-invalid'); document.getElementById('title-error-msg').classList.add('d-none'); }
    if (!s.value.trim()) { s.classList.add('is-invalid'); document.getElementById('slug-error-msg').classList.remove('d-none'); ok = false; }
    else { s.classList.remove('is-invalid'); document.getElementById('slug-error-msg').classList.add('d-none'); }
    if (!c.value.trim()) { document.getElementById('content-error-msg').classList.remove('d-none'); ok = false; }
    else { document.getElementById('content-error-msg').classList.add('d-none'); }
    return ok;
  }

  /* Submit handler */
  document.getElementById('blogCreateForm')?.addEventListener('submit', function(e) {
    const isValid = validateBasicInfo();
    if (!isValid) {
      e.preventDefault();
      const basicTab = document.getElementById('basic-tab');
      if (basicTab && !basicTab.classList.contains('active')) {
        new bootstrap.Tab(basicTab).show();
      }
      setTimeout(() => {
        const t = document.getElementById('title'), s = document.getElementById('slug');
        if (!t.value.trim()) t.focus();
        else if (!s.value.trim()) s.focus();
      }, 250);
    }
  });

});
</script>
@endsection
