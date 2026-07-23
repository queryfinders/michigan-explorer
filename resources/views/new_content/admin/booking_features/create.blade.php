@extends('layouts/layoutMaster')

@php
    $popularIcons = [
        'fas fa-check', 'fas fa-check-circle', 'fas fa-times-circle', 'fas fa-dollar-sign',
        'fas fa-credit-card', 'fas fa-calendar-alt', 'fas fa-calendar-check', 'fas fa-clock',
        'fas fa-bolt', 'fas fa-coffee', 'fas fa-utensils', 'fas fa-ban',
        'fas fa-shield-alt', 'fas fa-percent', 'fas fa-tags', 'fas fa-gift',
        'fas fa-mobile-alt', 'fas fa-envelope', 'fas fa-info-circle', 'fas fa-star',
        'fas fa-wallet', 'fas fa-receipt', 'fas fa-file-invoice-dollar', 'fas fa-user-check'
    ];
@endphp

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
              <label for="icon" class="form-label">Icon <span class="text-danger">*</span></label>
              <div class="dropdown">
                <button class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center" type="button" id="iconDropdownButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: #fff; border: 1px solid #d9dee3; padding: 8px 12px; height: 38px;">
                  <span><i id="selected_icon_display" class="{{ old('icon', 'fas fa-check') }} me-2"></i> <span id="selected_icon_text">{{ old('icon', 'fas fa-check') }}</span></span>
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
              <input type="hidden" name="icon" id="icon_input" value="{{ old('icon', 'fas fa-check') }}">
              @error('icon')
                <div class="text-danger mt-1 small">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="sort_order" class="form-label">Sort Order</label>
              <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" required />
              @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <input type="hidden" name="is_active" value="1" />
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

<style>
    .icon-option:hover {
        background-color: #f8f9fa;
        border-color: #4f46e5 !important;
    }
    .icon-picker-grid {
        scrollbar-width: thin;
    }
</style>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
      const iconInput = document.getElementById('icon_input');
      const selectedIconDisplay = document.getElementById('selected_icon_display');
      const selectedIconText = document.getElementById('selected_icon_text');
      const iconSearch = document.getElementById('iconSearch');
      const iconOptions = document.querySelectorAll('.icon-option');

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
</script>
@endsection
