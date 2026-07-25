@extends('layouts/layoutMaster')

@section('title', 'Add Hotel Policy')

@section('content')
<div class="row">
  <div class="col-md-12">
    <nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hotel-policies.index') }}">Hotel Policies</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Hotel Policy</li>
  </ol>
</nav>

<div class="card mb-4">
      <h5 class="card-header">Add Hotel Policy</h5>
      <div class="card-body">
        <form action="{{ route('hotel-policies.store') }}" method="POST">
          @csrf
          
          <div class="mb-3">
            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Check-in Time" required />
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="input_type">Input Type <span class="text-danger">*</span></label>
            <select class="form-select @error('input_type') is-invalid @enderror" id="input_type" name="input_type" required>
                <option value="text" {{ old('input_type') == 'text' ? 'selected' : '' }}>Single Line Text</option>
                <option value="textarea" {{ old('input_type') == 'textarea' ? 'selected' : '' }}>Multi-line Text Area</option>
            </select>
            @error('input_type')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="sort_order">Sort Order <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" required />
            @error('sort_order')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Active</label>
            </div>
          </div>

          <div class="pt-3">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Save</button>
            <a href="{{ route('hotel-policies.index') }}" class="btn btn-label-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
