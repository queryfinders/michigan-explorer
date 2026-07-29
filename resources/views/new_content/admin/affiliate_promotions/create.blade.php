@extends('layouts/layoutMaster')

@section('title', 'Add Affiliate Promotion')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('affiliate-promotions.index') }}">Affiliate Promotions</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Affiliate Promotion</li>
  </ol>
</nav>

<style>
  /* Custom Dropdown Styles (from Attraction page) */
  .cuisine-dropdown-wrapper { position: relative; width: 100%; }
  .cuisine-dropdown-trigger {
    display: flex; align-items: center; justify-content: space-between;
    min-height: 38px; padding: 6px 12px; border: 1px solid #dbdade;
    border-radius: 0.375rem; background: #fff; cursor: pointer;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out; gap: 10px; user-select: none;
    width: 100%;
  }
  .cuisine-dropdown-trigger:hover { border-color: #7367f0; }
  .cuisine-dropdown-trigger.open { border-color: #7367f0; box-shadow: 0 0 0 3px rgba(115,103,240,.15); }
  .cuisine-tags-area { display: flex; flex-wrap: wrap; gap: 6px; flex: 1; align-items: center; min-height: 24px; }
  .cuisine-placeholder { color: #5d596c; font-size: .9rem; display: flex; align-items: center; }
  .category-selected-text { color: #5d596c; font-size: .9rem; }
  .cuisine-dropdown-arrow { font-size: .8rem; color: #9ea5b1; transition: transform .25s; flex-shrink: 0; }
  .cuisine-dropdown-arrow.rotated { transform: rotate(180deg); }
  .cuisine-dropdown-panel {
    position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: #fff; border: 1.5px solid #d5d9e0; border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,.12); z-index: 1055; overflow: hidden;
    animation: dropdownFade .18s ease;
  }
  @keyframes dropdownFade { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }
  .cuisine-search-wrap { display: flex; align-items: center; padding: 10px 14px; gap: 10px; background: #f8f7ff; }
  .cuisine-search-icon { color: #9ea5b1; font-size: .9rem; flex-shrink: 0; }
  .cuisine-search-input { border: none; outline: none; background: transparent; font-size: .9rem; width: 100%; color: #3a3a3a; }
  .cuisine-search-input::placeholder { color: #b0b8c9; }
  .cuisine-divider { height: 1px; background: #eeedf5; }
  .cuisine-items-list { max-height: 240px; overflow-y: auto; padding: 6px 0; }
  .cuisine-items-list::-webkit-scrollbar { width: 4px; }
  .cuisine-items-list::-webkit-scrollbar-thumb { background: #d5d9e0; border-radius: 4px; }
  .cuisine-item {
    display: flex; align-items: center; gap: 10px; padding: 9px 16px;
    cursor: pointer; margin: 0; font-weight: 400; transition: background .13s;
  }
  .cuisine-item:hover { background: #f4f2ff; }
  .cuisine-item.selected { background: #ede9ff; }
  .cuisine-item.selected:hover { background: #e4dfff; }
  .cuisine-item input[type="radio"] { display: none; }
  .cuisine-item-name { flex: 1; font-size: .9rem; color: #3a3a3a; }
  .cuisine-item.selected .cuisine-item-name { color: #5a50d6; font-weight: 600; }
  .cuisine-item-check { font-size: .8rem; color: #7367f0; display: none; }
  .cuisine-item.selected .cuisine-item-check { display: block; }
  .cuisine-no-results { padding: 16px; text-align: center; color: #9ea5b1; font-size: .88rem; }
</style>

<div class="card mb-4 border-0 shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0 fw-bold">Add Affiliate Promotion</h5>
  </div>
  <div class="card-body">
    <form id="affiliatePromotionCreateForm" action="{{ route('affiliate-promotions.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row g-3">
        <!-- Placement & Priority -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Placement <span class="text-danger">*</span></label>
          
          @php
              $placements = [
                  'homepage_banner' => 'Homepage Banner',
                  'homepage_sidebar' => 'Homepage Sidebar',
                  'hotel_detail' => 'Hotel Detail',
                  'restaurant_detail' => 'Restaurant Detail',
                  'attraction_detail' => 'Attraction Detail',
                  'blog_detail' => 'Blog Detail',
                  'footer_banner' => 'Footer Banner',
              ];
              $selectedPlacement = old('placement', '');
              $selectedPlacementName = $selectedPlacement ? ($placements[$selectedPlacement] ?? 'Select Placement...') : 'Select Placement...';
          @endphp

          <input type="hidden" name="placement" id="placement_value" value="{{ $selectedPlacement }}" required>
          
          <div class="cuisine-dropdown-wrapper" id="placementDropdownWrapper">
            <div class="cuisine-dropdown-trigger {{ $errors->has('placement') ? 'border-danger' : '' }}" id="placementTrigger" onclick="togglePlacementDropdown()">
              <div class="cuisine-tags-area" id="placementTagsArea">
                <span class="category-selected-text" id="placementPlaceholder">{{ $selectedPlacementName }}</span>
              </div>
              <i class="fas fa-chevron-down cuisine-dropdown-arrow" id="placementArrow"></i>
            </div>
            <div class="cuisine-dropdown-panel" id="placementDropdownPanel" style="display:none;">
              <div class="cuisine-search-wrap">
                <i class="fas fa-search cuisine-search-icon"></i>
                <input type="text" class="cuisine-search-input" id="placementSearchInput"
                       placeholder="Search placements..." oninput="filterPlacements(this.value)" autocomplete="off" />
              </div>
              <div class="cuisine-divider"></div>
              <div class="cuisine-items-list" id="placementItemsList">
                @foreach($placements as $val => $name)
                <label class="cuisine-item {{ $selectedPlacement === $val ? 'selected' : '' }}" id="place-label-{{ $val }}">
                  <input type="radio" name="_place_radio" value="{{ $val }}"
                         id="place_rb_{{ $val }}"
                         class="cat-rb d-none"
                         data-name="{{ $name }}"
                         data-id="{{ $val }}"
                         {{ $selectedPlacement === $val ? 'checked' : '' }}
                         onchange="onPlacementChange(this)" />
                  <span class="cuisine-item-name">{{ $name }}</span>
                  <span class="cuisine-item-check"><i class="fas fa-check"></i></span>
                </label>
                @endforeach
                <div class="cuisine-no-results d-none" id="placementNoResults">
                  <i class="fas fa-search-minus me-2"></i>No placements found
                </div>
              </div>
            </div>
          </div>
          @error('placement')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
          <input type="number" name="priority" class="form-control @error('priority') is-invalid @enderror" value="{{ old('priority', 1) }}" min="1" required>
          <small class="text-muted">1 = Highest Priority (rendered first if multiple promos are scheduled)</small>
          @error('priority')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Badge & CTA Button -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Badge Text <span class="text-danger">*</span></label>
          <input type="text" name="badge_text" class="form-control @error('badge_text') is-invalid @enderror" value="{{ old('badge_text', 'Special Promotion') }}" required placeholder="e.g. Special Offer, Limited Time">
          @error('badge_text')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">CTA Button Text <span class="text-danger">*</span></label>
          <input type="text" name="cta_text" class="form-control @error('cta_text') is-invalid @enderror" value="{{ old('cta_text', 'Claim Offer') }}" required placeholder="e.g. Claim Offer, Book Now">
          @error('cta_text')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Title -->
        <div class="col-12">
          <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. Save 20% on Romantic Lakefront Escapes">
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Subtitle -->
        <div class="col-12">
          <label class="form-label fw-semibold">Subtitle <span class="text-danger">*</span></label>
          <textarea name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" rows="3" required placeholder="Add promotion campaign summary or description..."></textarea>
          @error('subtitle')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Affiliate Link Selection -->
        <div class="col-12">
          <label class="form-label fw-semibold">Destination Affiliate Link</label>
          <select name="affiliate_link_id" class="form-select @error('affiliate_link_id') is-invalid @enderror">
            <option value="">Select Platform Link (for tracking redirects)...</option>
            @foreach($affiliateLinks as $link)
              <option value="{{ $link->id }}" {{ old('affiliate_link_id') == $link->id ? 'selected' : '' }}>{{ $link->name }} ({{ $link->provider }})</option>
            @endforeach
          </select>
          <small class="text-muted">Links the promotion CTA button to a registered provider redirect for analytics.</small>
          @error('affiliate_link_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Desktop Banner Image -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Desktop Banner Image <span class="text-danger">*</span></label>
          <input type="file" name="desktop_image" class="form-control @error('desktop_image') is-invalid @enderror" required>
          <small class="text-muted">Recommended aspect ratio: 16:9 or custom wide banner dimensions (max 2MB).</small>
          @error('desktop_image')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Mobile Banner Image 
        <div class="col-md-6">
          <label class="form-label fw-semibold">Mobile Banner Image</label>
          <input type="file" name="mobile_image" class="form-control @error('mobile_image') is-invalid @enderror">
          <small class="text-muted">Optional: portrait layout image optimized for phone screens (max 2MB). Falls back to desktop image if empty.</small>
          @error('mobile_image')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        -->

        <!-- Starts At & Ends At Scheduling -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Starts At (Schedule Activation)</label>
          <input type="datetime-local" name="starts_at" class="form-control @error('starts_at') is-invalid @enderror" value="{{ old('starts_at') }}">
          <small class="text-muted">Leave empty to activate instantly.</small>
          @error('starts_at')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Ends At (Schedule Expiration)</label>
          <input type="datetime-local" name="ends_at" class="form-control @error('ends_at') is-invalid @enderror" value="{{ old('ends_at') }}">
          <small class="text-muted">Leave empty to keep promotion active indefinitely.</small>
          @error('ends_at')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Status Toggle switch 
        <div class="col-12 my-3">
          <div class="form-check form-switch form-check-md">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold ms-2" for="is_active">Promotion Active Status</label>
          </div>
        </div>
        -->

        <!-- Submit & Cancel Buttons -->
        <div class="col-12 pt-3 border-top d-flex gap-2">
          <button type="submit" class="btn btn-primary">Create Promotion</button>
          <a href="{{ route('affiliate-promotions.index') }}" class="btn btn-label-secondary">Cancel</a>
        </div>

      </div>
    </form>
  </div>
</div>
@endsection

@section('page-script')
<script>
  // Custom Placement Dropdown Logic
  function togglePlacementDropdown() {
    const panel  = document.getElementById('placementDropdownPanel');
    const arrow  = document.getElementById('placementArrow');
    const trigger = document.getElementById('placementTrigger');
    const isOpen = panel.style.display !== 'none';
    
    if (isOpen) {
        panel.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)';
        trigger.classList.remove('open');
    } else {
        panel.style.display = 'block';
        arrow.style.transform = 'rotate(180deg)';
        trigger.classList.add('open');
        document.getElementById('placementSearchInput').focus();
    }
  }

  function filterPlacements(val) {
    const term  = val.toLowerCase();
    const items = document.querySelectorAll('#placementItemsList .cuisine-item');
    let   found = 0;
    items.forEach(item => {
      const name = item.querySelector('.cuisine-item-name').textContent.toLowerCase();
      const show = name.includes(term);
      item.style.display = show ? '' : 'none';
      if (show) found++;
    });
    document.getElementById('placementNoResults').classList.toggle('d-none', found > 0);
  }

  function onPlacementChange(rb) {
    const id    = rb.dataset.id;
    const name  = rb.dataset.name;
    const hidden= document.getElementById('placement_value');
    const ph    = document.getElementById('placementPlaceholder');

    hidden.value = id;
    document.querySelectorAll('#placementItemsList .cuisine-item').forEach(l => l.classList.remove('selected'));
    
    const label = document.getElementById('place-label-' + id);
    if(label) label.classList.add('selected');

    ph.textContent = name;
    
    // Auto-close dropdown
    document.getElementById('placementDropdownPanel').style.display = 'none';
    document.getElementById('placementArrow').style.transform = 'rotate(0deg)';
    document.getElementById('placementTrigger').classList.remove('open');
    
    // Remove error border if present
    document.getElementById('placementTrigger').classList.remove('border-danger');
  }

  // Close dropdown on outside click
  document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('placementDropdownWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
      const panel = document.getElementById('placementDropdownPanel');
      if(panel && panel.style.display !== 'none') {
        panel.style.display = 'none';
        document.getElementById('placementArrow').style.transform = 'rotate(0deg)';
        document.getElementById('placementTrigger').classList.remove('open');
      }
    }
  });
</script>
@endsection
