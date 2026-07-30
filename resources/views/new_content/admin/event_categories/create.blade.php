@extends('layouts/layoutMaster')

@section('title', 'Add Event Category')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('event-categories.index') }}">Event Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Event Category</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Event Category</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('event-categories.store') }}" method="POST">
      @csrf
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Music Festival" required />
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="e.g. music-festival" required />
          @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control tinymce-editor" id="description" name="description">{{ old('description') }}</textarea>
      </div>
      <input type="hidden" name="status" value="1">
      <button type="submit" class="btn btn-primary">Save</button>
      <a href="{{ route('event-categories.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection

@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
  tinymce.init({
    selector: '.tinymce-editor',
    height: 300,
    menubar: false,
    plugins: [
      'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
      'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
      'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
    'bold italic forecolor | alignleft aligncenter ' +
    'alignright alignjustify | bullist numlist outdent indent | ' +
    'removeformat | help',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
  });

  $(document).ready(function() {
      // Auto generate slug
      $('#name').on('input', function() {
          let nameVal = $(this).val();
          $('#slug').val(nameVal.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, ''));
      });

      // jQuery Validation
      $('form').validate({
          rules: {
              name: { required: true },
              slug: { required: true }
          },
          messages: {
              name: { required: "Please enter category name" },
              slug: { required: "Please enter category slug" }
          },
          errorElement: 'div',
          errorClass: 'invalid-feedback d-block',
          highlight: function(element) {
              $(element).addClass('is-invalid');
          },
          unhighlight: function(element) {
              $(element).removeClass('is-invalid');
          },
          errorPlacement: function(error, element) {
              error.insertAfter(element);
          }
      });
  });
</script>
@endsection
