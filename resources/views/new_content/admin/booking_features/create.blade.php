@extends('layouts/layoutMaster')

@section('title', 'Add Booking Feature')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <h5 class="card-header">Add Booking Feature</h5>
      <div class="card-body">
        <form action="{{ route('booking-features.store') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required />
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="icon" class="form-label">Icon Class (e.g. fas fa-check)</label>
              <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', 'fas fa-check') }}" />
              @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="sort_order" class="form-label">Sort Order</label>
              <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" required />
              @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3 d-flex align-items-center mt-4">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active Status</label>
              </div>
            </div>
          </div>
          
          <div class="mt-4">
            <button type="submit" class="btn btn-primary me-2">Save Changes</button>
            <a href="{{ route('booking-features.index') }}" class="btn btn-label-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
