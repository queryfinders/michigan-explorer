@extends('layouts/layoutMaster')

@section('title', 'Add Hotel Category')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Hotel Category</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('hotel-categories.store') }}" method="POST">
      @csrf
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label" for="name">Name</label>
          <input type="text" class="form-control" id="name" name="name" required />
        </div>
        <div class="col-md-6">
          <label class="form-label" for="slug">Slug</label>
          <input type="text" class="form-control" id="slug" name="slug" required />
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
  });
</script>
@endsection
