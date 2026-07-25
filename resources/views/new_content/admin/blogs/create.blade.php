@extends('layouts/layoutMaster')

@section('title', 'Add Blog')

@section('vendor-style')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blogs</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Blog</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Blog</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      
      <div class="row">
          <!-- Left Column -->
          <div class="col-md-8">
              <div class="mb-3">
                <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" required />
              </div>
              
              <div class="mb-3">
                <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="slug" name="slug" required />
                <small class="text-muted">Unique URL friendly name (e.g., my-first-post)</small>
              </div>
              
              <div class="mb-3">
                <label class="form-label" for="content">Content <span class="text-danger">*</span></label>
                <textarea id="content" name="content" required></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label" for="excerpt">Excerpt</label>
                <textarea class="form-control" id="excerpt" name="excerpt" rows="3" placeholder="Short summary of the blog..."></textarea>
              </div>

              <h6 class="mt-4 border-bottom pb-2">SEO & Schema Settings</h6>
              <div class="mb-3">
                <label class="form-label" for="meta_title">Meta Title</label>
                <input type="text" class="form-control" id="meta_title" name="meta_title" />
              </div>
              <div class="mb-3">
                <label class="form-label" for="meta_description">Meta Description</label>
                <textarea class="form-control" id="meta_description" name="meta_description" rows="2"></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label" for="canonical_url">Canonical URL</label>
                <input type="url" class="form-control" id="canonical_url" name="canonical_url" />
              </div>
              <div class="mb-3">
                <label class="form-label" for="og_title">OG Title</label>
                <input type="text" class="form-control" id="og_title" name="og_title" />
              </div>
              <div class="mb-3">
                <label class="form-label" for="og_description">OG Description</label>
                <textarea class="form-control" id="og_description" name="og_description" rows="2"></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label" for="schema_markup">Schema Markup (JSON-LD)</label>
                <textarea class="form-control" id="schema_markup" name="schema_markup" rows="8" placeholder="Enter JSON-LD Schema markup here"></textarea>
              </div>
          </div>

          <!-- Right Column -->
          <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="status" name="status" required>
                  <option value="draft" selected>Draft</option>
                  <option value="published">Published</option>
                  <option value="scheduled">Scheduled</option>
                </select>
              </div>
              
              <div class="mb-3" id="publish-date-container" class="auto-style-1">
                <label class="form-label" for="published_at">Publish Date & Time</label>
                <input type="datetime-local" class="form-control" id="published_at" name="published_at" />
              </div>

              <div class="mb-3">
                <label class="form-label" for="blog_category_id">Category</label>
                <select class="form-select" id="blog_category_id" name="blog_category_id">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label" for="tags">Tags</label>
                <select class="form-select select2" id="tags" name="tags[]" multiple="multiple">
                    @foreach($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label" for="author_id">Author</label>
                <select class="form-select" id="author_id" name="author_id">
                    <option value="">Select Author</option>
                    @foreach($authors as $author)
                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                    @endforeach
                </select>
              </div>
              
              <div class="mb-3">
                <label class="form-label" for="featured_image_file">Featured Image</label>
                <input class="form-control" type="file" id="featured_image_file" name="featured_image_file" accept="image/*">
              </div>

              <div class="form-check mb-4 mt-3">
                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                <label class="form-check-label" for="is_featured">
                  Featured Post
                </label>
              </div>
              
              <button type="submit" class="btn btn-primary w-100 mb-2">Save Blog</button>
              <a href="{{ route('blogs.index') }}" class="btn btn-secondary w-100">Cancel</a>
          </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('vendor-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#content').summernote({
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('.select2').select2({
            placeholder: "Select tags",
            allowClear: true
        });

        $('#status').on('change', function() {
            if($(this).val() == 'scheduled') {
                $('#publish-date-container').slideDown();
            } else {
                $('#publish-date-container').slideUp();
            }
        });
        
        // Auto-generate slug from title
        $('#title').on('input', function() {
            var val = $(this).val();
            var slug = val.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
            $('#slug').val(slug);
        });
    });
</script>
@endsection
