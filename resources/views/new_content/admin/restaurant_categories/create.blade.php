@extends('layouts/layoutMaster')

@section('title', 'Add Restaurant Category')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('restaurant-categories.index') }}">Restaurant Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Restaurant Category</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Restaurant Category</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('restaurant-categories.store') }}" method="POST">
      @csrf
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Fine Dining" required />
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="slug" name="slug" placeholder="e.g. fine-dining" required />
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control tinymce-editor" id="description" name="description"></textarea>
      </div>
      <input type="hidden" name="status" value="1">
      <button type="submit" class="btn btn-primary">Save</button>
      <a href="{{ route('restaurant-categories.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection


@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
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
</script>
@endsection
