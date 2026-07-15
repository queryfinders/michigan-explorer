@extends('layouts/layoutMaster')

@section('title', 'Edit Amenity')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Amenity</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('amenities.update', $amenity->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label class="form-label" for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $amenity->name }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="slug">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" value="{{ $amenity->slug }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="icon">FontAwesome Icon Class</label>
        <div class="input-group">
          <span class="input-group-text"><i id="icon-preview" class="fas {{ $amenity->icon }}"></i></span>
          <input type="text" class="form-control" id="icon" name="icon" value="{{ $amenity->icon }}" required />
        </div>
        <div class="form-text">Enter a valid FontAwesome v5/v6 icon class (e.g. <code>fa-wifi</code>, <code>fa-swimming-pool</code>, <code>fa-dumbbell</code>).</div>
      </div>
      <div class="mb-3">
        <label class="form-label" for="status">Status</label>
        <select class="form-select" id="status" name="status">
          <option value="1" {{ $amenity->status == 1 ? 'selected' : '' }}>Active</option>
          <option value="0" {{ $amenity->status == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ route('amenities.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
      const nameInput = document.getElementById('name');
      const slugInput = document.getElementById('slug');
      const iconInput = document.getElementById('icon');
      const iconPreview = document.getElementById('icon-preview');

      // Auto-generate slug
      nameInput.addEventListener('input', function() {
          let text = this.value;
          text = text.toLowerCase()
                     .replace(/[^a-z0-9]+/g, '-')
                     .replace(/^-+|-+$/g, '');
          slugInput.value = text;
      });

      // Live Icon Preview
      iconInput.addEventListener('input', function() {
          let iconClass = this.value.trim();
          if (iconClass === '') {
              iconPreview.className = 'fas fa-question-circle';
          } else {
              iconPreview.className = 'fas ' + iconClass;
          }
      });
  });
</script>
@endsection
