@extends('layouts/layoutMaster')

@php
    $popularIcons = [
        'fas fa-wifi', 'fas fa-parking', 'fas fa-swimming-pool', 'fas fa-dumbbell', 
        'fas fa-spa', 'fas fa-coffee', 'fas fa-utensils', 'fas fa-cocktail', 
        'fas fa-concierge-bell', 'fas fa-paw', 'fas fa-tv', 'fas fa-snowflake', 
        'fas fa-hot-tub', 'fas fa-wheelchair', 'fas fa-baby', 'fas fa-key', 
        'fas fa-shield-alt', 'fas fa-tree', 'fas fa-star', 'fas fa-bed', 
        'fas fa-clock', 'fas fa-wine-glass', 'fas fa-luggage-cart', 'fas fa-bicycle',
        'fas fa-hiking', 'fas fa-campground', 'fas fa-map-marker-alt', 'fas fa-map-pin',
        'fas fa-wind', 'fas fa-tshirt', 'fas fa-sink', 'fas fa-glass-martini-alt',
        'fas fa-bus', 'fas fa-umbrella-beach', 'fas fa-golf-ball', 'fas fa-fire',
        'fas fa-smoking', 'fas fa-ban', 'fas fa-users', 'fas fa-child',
        'fas fa-arrows-alt-v', 'fas fa-lock'
    ];
@endphp

@section('title', 'Add Amenity')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('amenities.index') }}">Amenities</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Amenity</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Amenity</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('amenities.store') }}" method="POST">
      @csrf
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="e.g. Free WiFi" value="{{ old('name') }}" required />
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" placeholder="e.g. free-wifi" value="{{ old('slug') }}" required />
          @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label" for="icon">Icon <span class="text-danger">*</span></label>
        <div class="dropdown">
          <button class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center" type="button" id="iconDropdownButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: #fff; border: 1px solid #d9dee3; padding: 8px 12px; height: 38px;">
            <span><i id="selected_icon_display" class="{{ old('icon', '') }} me-2"></i> <span id="selected_icon_text">{{ old('icon', 'Select Icon') }}</span></span>
            <i class="ti ti-chevron-down"></i>
          </button>
          <div class="dropdown-menu w-100 p-3" aria-labelledby="iconDropdownButton">
            <input type="text" class="form-control mb-3" id="iconSearch" placeholder="Search icons...">
            <div class="d-flex flex-wrap gap-2 icon-picker-grid" id="iconGrid" style="max-height: 200px; overflow-y: auto;">
              @foreach($popularIcons as $ic)
                <div class="icon-option p-2 border rounded cursor-pointer text-center" data-icon="{{ $ic }}" style="width: 45px; height: 45px; display:flex; align-items:center; justify-content:center; cursor: pointer;" title="{{ $ic }}">
                  <i class="{{ $ic }} fs-5"></i>
                </div>
              @endforeach
            </div>
          </div>
        </div>
        <input type="hidden" name="icon" id="icon_input" value="{{ old('icon', '') }}">
        @error('icon')
          <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
        <div class="form-text">Search and select an icon for the amenity.</div>
      </div>

<style>
    .icon-option:hover {
        background-color: #f8f9fa;
        border-color: #4f46e5 !important;
    }
    .icon-picker-grid {
        scrollbar-width: thin;
    }
</style>
      <input type="hidden" name="status" value="1" />
      <button type="submit" class="btn btn-primary">Save</button>
      <a href="{{ route('amenities.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection

@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
      const nameInput = document.getElementById('name');
      const slugInput = document.getElementById('slug');
      const iconInput = document.getElementById('icon_input');
      const selectedIconDisplay = document.getElementById('selected_icon_display');
      const selectedIconText = document.getElementById('selected_icon_text');
      const iconSearch = document.getElementById('iconSearch');
      const iconOptions = document.querySelectorAll('.icon-option');

      // Auto-generate slug
      nameInput.addEventListener('input', function() {
          let text = this.value;
          text = text.toLowerCase()
                     .replace(/[^a-z0-9]+/g, '-')
                     .replace(/^-+|-+$/g, '');
          slugInput.value = text;
      });

      // Icon Picker Search
      iconSearch.addEventListener('input', function() {
          const term = this.value.toLowerCase();
          iconOptions.forEach(opt => {
              const iconName = opt.getAttribute('data-icon').toLowerCase();
              opt.style.display = iconName.includes(term) ? 'flex' : 'none';
          });
      });

      // Icon Selection
      iconOptions.forEach(opt => {
          opt.addEventListener('click', function() {
              const iconName = this.getAttribute('data-icon');
              iconInput.value = iconName;
              selectedIconDisplay.className = `${iconName} me-2`;
              selectedIconText.textContent = iconName;
          });
      });
  });

  $(document).ready(function() {
      // jQuery Validation
      $('form').validate({
          rules: {
              name: { required: true },
              slug: { required: true },
              icon: { required: true }
          },
          messages: {
              name: { required: "Please enter amenity name" },
              slug: { required: "Please enter slug" },
              icon: { required: "Please select an icon" }
          },
          errorElement: 'div',
          errorClass: 'text-danger mt-1 small',
          errorPlacement: function(error, element) {
              if(element.attr("name") == "icon") {
                  error.insertAfter(element.parent());
              } else {
                  error.insertAfter(element);
              }
          },
          ignore: [] // Allow validating hidden fields like icon_input
      });
  });
</script>
@endsection
