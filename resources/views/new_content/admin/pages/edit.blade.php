@extends('layouts/layoutMaster')

@section('title', 'Edit Page')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Page</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      
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
            <label class="form-label" for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $page->title }}" required />
          </div>
          <div class="mb-3">
            <label class="form-label" for="slug">Slug</label>
            <input type="text" class="form-control" id="slug" name="slug" value="{{ $page->slug }}" required />
          </div>
          {{-- 
          <div class="mb-3">
            <label class="form-label" for="content">Content</label>
            <textarea class="form-control tinymce" id="content" name="content" rows="15">{{ $page->content }}</textarea>
          </div>
          --}}
          <div class="mb-3">
            <label class="form-label" for="status">Status</label>
            <select class="form-select" id="status" name="status">
              <option value="1" {{ $page->status == 1 ? 'selected' : '' }}>Active</option>
              <option value="0" {{ $page->status == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
          </div>
        </div>

        <!-- Tab 2: Hero Banner -->
        <div class="tab-pane fade" id="banner-pane" role="tabpanel" aria-labelledby="banner-tab">
          <div class="mb-3">
            <label class="form-label" for="banner_title">Banner Title</label>
            <input type="text" class="form-control" id="banner_title" name="banner_title" value="{{ $page->banner_title }}" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="banner_subtitle">Banner Subtitle</label>
            <textarea class="form-control" id="banner_subtitle" name="banner_subtitle" rows="3">{{ $page->banner_subtitle }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label" for="banner_button_text">Button Text</label>
            <input type="text" class="form-control" id="banner_button_text" name="banner_button_text" value="{{ $page->banner_button_text }}" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="banner_button_link">Button Link</label>
            <input type="text" class="form-control" id="banner_button_link" name="banner_button_link" value="{{ $page->banner_button_link }}" placeholder="e.g., #all-hotels or https://example.com" />
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
            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ $page->seo->meta_title ?? '' }}" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="meta_description">Meta Description</label>
            <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ $page->seo->meta_description ?? '' }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label" for="canonical_url">Canonical URL</label>
            <input type="url" class="form-control" id="canonical_url" name="canonical_url" value="{{ $page->seo->canonical_url ?? '' }}" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="og_title">OG Title</label>
            <input type="text" class="form-control" id="og_title" name="og_title" value="{{ $page->seo->og_title ?? '' }}" />
          </div>
          <div class="mb-3">
            <label class="form-label" for="og_description">OG Description</label>
            <textarea class="form-control" id="og_description" name="og_description" rows="2">{{ $page->seo->og_description ?? '' }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label" for="schema_markup">Schema Markup (JSON-LD) - <span class="text-info">Auto-generated</span></label>
            <textarea class="form-control" id="schema_markup" name="schema_markup" rows="8" placeholder="Auto-generated on save" readonly disabled>{{ $page->seo->schema_markup ?? '' }}</textarea>
          </div>
        </div>

      </div>

      <div class="mt-4 pt-3 border-top">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('pages.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection

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
  });
</script>
@endsection
