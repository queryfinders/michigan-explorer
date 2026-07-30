@extends('layouts/layoutMaster')

@section('title', 'Add Hotel Category')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hotel-categories.index') }}">Hotel Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Hotel Category</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Hotel Category</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('hotel-categories.store') }}" method="POST">
      @csrf
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required />
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" required />
          @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control tinymce" id="description" name="description"></textarea>
      </div>
      <input type="hidden" name="status" value="1" />
      <button type="submit" class="btn btn-primary">Save</button>
      <a href="{{ route('hotel-categories.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection

@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
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

    // Auto-fill slug from category name
    $('#name').on('input', function() {
      var name = $(this).val();
      var slug = name.toLowerCase()
                     .trim()
                     .replace(/[^a-z0-9\s-]/g, '')
                     .replace(/\s+/g, '-')
                     .replace(/-+/g, '-');
      $('#slug').val(slug);
    });

    // jQuery Validation
    $('form').validate({
      rules: {
        name: { required: true },
        slug: { required: true }
      },
      messages: {
        name: { required: "Please enter category name" },
        slug: { required: "Please enter slug" }
      },
      errorElement: 'div',
      errorClass: 'text-danger mt-1 small',
      errorPlacement: function(error, element) {
        error.insertAfter(element);
      }
    });
  });
</script>
@endsection
