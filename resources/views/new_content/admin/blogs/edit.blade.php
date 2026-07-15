@extends('layouts/layoutMaster')

@section('title', 'Edit Blog')

@section('vendor-style')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Blog: {{ $blog->title }}</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      
      <div class="row">
          <!-- Left Column -->
          <div class="col-md-8">
              <div class="mb-3">
                <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $blog->title }}" required />
              </div>
              
              <div class="mb-3">
                <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="slug" name="slug" value="{{ $blog->slug }}" required />
              </div>
              
              <div class="mb-3">
                <label class="form-label" for="content">Content <span class="text-danger">*</span></label>
                <textarea id="content" name="content" required>{!! $blog->content !!}</textarea>
              </div>

              <div class="mb-3">
                <label class="form-label" for="excerpt">Excerpt</label>
                <textarea class="form-control" id="excerpt" name="excerpt" rows="3">{{ $blog->excerpt }}</textarea>
              </div>

              <h6 class="mt-4 border-bottom pb-2">SEO & Schema Settings</h6>
              <div class="mb-3">
                <label class="form-label" for="meta_title">Meta Title</label>
                <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ $blog->seo->meta_title ?? '' }}" />
              </div>
              <div class="mb-3">
                <label class="form-label" for="meta_description">Meta Description</label>
                <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ $blog->seo->meta_description ?? '' }}</textarea>
              </div>
              <div class="mb-3">
                <label class="form-label" for="canonical_url">Canonical URL</label>
                <input type="url" class="form-control" id="canonical_url" name="canonical_url" value="{{ $blog->seo->canonical_url ?? '' }}" />
              </div>
              <div class="mb-3">
                <label class="form-label" for="og_title">OG Title</label>
                <input type="text" class="form-control" id="og_title" name="og_title" value="{{ $blog->seo->og_title ?? '' }}" />
              </div>
              <div class="mb-3">
                <label class="form-label" for="og_description">OG Description</label>
                <textarea class="form-control" id="og_description" name="og_description" rows="2">{{ $blog->seo->og_description ?? '' }}</textarea>
              </div>
              <div class="mb-3">
                <label class="form-label" for="schema_markup">Schema Markup (JSON-LD)</label>
                <textarea class="form-control" id="schema_markup" name="schema_markup" rows="8" placeholder="Enter JSON-LD Schema markup here">{{ $blog->seo->schema_markup ?? '' }}</textarea>
              </div>
          </div>

          <!-- Right Column -->
          <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="status" name="status" required>
                  <option value="draft" {{ $blog->status == 'draft' ? 'selected' : '' }}>Draft</option>
                  <option value="published" {{ $blog->status == 'published' ? 'selected' : '' }}>Published</option>
                  <option value="scheduled" {{ $blog->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                </select>
              </div>
              
              <div class="mb-3 {{ $blog->status == 'scheduled' ? 'd-block' : 'd-none' }}" id="publish-date-container">
                <label class="form-label" for="published_at">Publish Date & Time</label>
                <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="{{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('Y-m-d\TH:i') : '' }}" />
              </div>

              <div class="mb-3">
                <label class="form-label" for="blog_category_id">Category</label>
                <select class="form-select" id="blog_category_id" name="blog_category_id">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $blog->blog_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label" for="tags">Tags</label>
                <select class="form-select select2" id="tags" name="tags[]" multiple="multiple">
                    @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label" for="author_id">Author</label>
                <select class="form-select" id="author_id" name="author_id">
                    <option value="">Select Author</option>
                    @foreach($authors as $author)
                    <option value="{{ $author->id }}" {{ $blog->author_id == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                    @endforeach
                </select>
              </div>
              
              <div class="mb-3">
                <label class="form-label" for="featured_image_file">Featured Image</label>
                @if($blog->featured_image)
                    <div class="mb-2">
                        <img src="{{ asset($blog->featured_image) }}" alt="Current Image" class="img-thumbnail auto-style-2">
                    </div>
                @endif
                <input class="form-control" type="file" id="featured_image_file" name="featured_image_file" accept="image/*">
              </div>

              <div class="form-check mb-4 mt-3">
                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ $blog->is_featured ? 'checked' : '' }}>
                <label class="form-check-label" for="is_featured">
                  Featured Post
                </label>
              </div>
              
              <button type="submit" class="btn btn-primary w-100 mb-2">Update Blog</button>
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
        
        $('#title').on('input', function() {
            var val = $(this).val();
            var slug = val.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
            $('#slug').val(slug);
        });
    });
</script>
@endsection
