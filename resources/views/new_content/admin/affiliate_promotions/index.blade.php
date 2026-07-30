@extends('layouts/layoutMaster')

@section('title', 'Affiliate Promotions')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">Settings</a></li>
    <li class="breadcrumb-item active" aria-current="page">Affiliate Promotions</li>
  </ol>
</nav>

<style>
  /* Align select2 dropdown height with standard bootstrap inputs */
  .select2-container--default .select2-selection--single {
    height: 38px !important;
    border: 1px solid #dbdade !important;
    border-radius: 0.375rem !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px !important;
    padding-left: 12px !important;
    color: #5d596c !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px !important;
  }
  .select2-dropdown {
    border: 1px solid #dbdade !important;
    border-radius: 0.375rem !important;
    box-shadow: 0 0.25rem 1rem rgba(168, 170, 174, 0.25) !important;
  }
  .select2-container--default.select2-container--open .select2-selection--single {
    border-color: #7367f0 !important;
  }
  .select2-results__option {
    padding: 8px 12px !important;
    font-size: 0.9rem !important;
    border-radius: 0.25rem !important;
    margin: 2px 4px !important;
  }
  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #7367f0 !important;
    color: #fff !important;
  }
  .select2-container--default .select2-results__option[aria-selected=true] {
    background-color: rgba(115, 103, 240, 0.08) !important;
    color: #7367f0 !important;
    font-weight: 600;
  }

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

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Affiliate Promotions</h3>
    <p class="text-muted mb-0">Create and manage dynamic promotional banners across your website.</p>
  </div>
  <div>
    <a href="{{ route('affiliate-promotions.create') }}" class="btn btn-warning text-white">Add Promotion</a>
  </div>
</div>

@include('layouts.messages')

<!-- Filters Section -->
<div class="card mb-4 border-0 shadow-sm">
  <div class="card-body">
    <form method="GET" action="{{ route('affiliate-promotions.index') }}" class="row g-3">
      <div class="col-lg-3 col-md-6 col-12">
        <label class="form-label fw-semibold">Search</label>
        <input type="text" name="search" class="form-control" placeholder="Search by title..." value="{{ request('search') }}" style="height: 38px;">
      </div>
      <div class="col-lg-3 col-md-6 col-12">
        <label class="form-label fw-semibold">Placement</label>
        
        @php
            $placements = [
                '' => 'All Placements',
                'homepage_banner' => 'Homepage Banner',
                'hotel_detail' => 'Hotel Detail',
                'restaurant_detail' => 'Restaurant Detail',
                'attraction_detail' => 'Attraction Detail',
                'blog_detail' => 'Blog Detail',
                'footer_banner' => 'Footer Banner',
            ];
            $selectedPlacement = request('placement', '');
            $selectedPlacementName = $placements[$selectedPlacement] ?? 'All Placements';
        @endphp

        <input type="hidden" name="placement" id="placement_value" value="{{ $selectedPlacement }}">
        
        <div class="cuisine-dropdown-wrapper" id="placementDropdownWrapper">
          <div class="cuisine-dropdown-trigger" id="placementTrigger" onclick="toggleCuisineDropdown('placement')">
            <div class="cuisine-tags-area" id="placementTagsArea">
              <span class="category-selected-text" id="placementPlaceholder">{{ $selectedPlacementName }}</span>
            </div>
            <i class="fas fa-chevron-down cuisine-dropdown-arrow" id="placementArrow"></i>
          </div>
          <div class="cuisine-dropdown-panel" id="placementDropdownPanel" style="display:none;">
            <div class="cuisine-search-wrap">
              <i class="fas fa-search cuisine-search-icon"></i>
              <input type="text" class="cuisine-search-input" id="placementSearchInput"
                     placeholder="Search placements..." oninput="filterCuisineDropdown(this.value, 'placement')" autocomplete="off" />
            </div>
            <div class="cuisine-divider"></div>
            <div class="cuisine-items-list" id="placementItemsList">
              @foreach($placements as $val => $name)
              <label class="cuisine-item {{ $selectedPlacement === $val ? 'selected' : '' }}" id="placement-label-{{ empty($val) ? 'all' : $val }}">
                <input type="radio" name="_place_radio" value="{{ $val }}"
                       id="place_rb_{{ empty($val) ? 'all' : $val }}"
                       class="cat-rb d-none"
                       data-name="{{ $name }}"
                       data-id="{{ $val }}"
                       {{ $selectedPlacement === $val ? 'checked' : '' }}
                       onchange="onCuisineDropdownChange(this, 'placement')" />
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
      </div>
      <div class="col-lg-2 col-md-6 col-12">
        <label class="form-label fw-semibold">Active Status</label>
        @php
            $statuses = [
                '' => 'All',
                '1' => 'Active',
                '0' => 'Inactive'
            ];
            $selectedStatus = request('status', '');
            $selectedStatusName = $statuses[$selectedStatus] ?? 'All';
        @endphp
        <input type="hidden" name="status" id="status_value" value="{{ $selectedStatus }}">
        <div class="cuisine-dropdown-wrapper" id="statusDropdownWrapper">
          <div class="cuisine-dropdown-trigger" id="statusTrigger" onclick="toggleCuisineDropdown('status')">
            <div class="cuisine-tags-area">
              <span class="category-selected-text" id="statusPlaceholder">{{ $selectedStatusName }}</span>
            </div>
            <i class="fas fa-chevron-down cuisine-dropdown-arrow" id="statusArrow"></i>
          </div>
          <div class="cuisine-dropdown-panel" id="statusDropdownPanel" style="display:none;">
            <div class="cuisine-items-list" id="statusItemsList" style="padding-top: 6px;">
              @foreach($statuses as $val => $name)
              <label class="cuisine-item {{ $selectedStatus === (string)$val ? 'selected' : '' }}" id="status-label-{{ $val === '' ? 'all' : $val }}">
                <input type="radio" name="_status_radio" value="{{ $val }}"
                       id="status_rb_{{ $val === '' ? 'all' : $val }}"
                       class="cat-rb d-none"
                       data-name="{{ $name }}"
                       data-id="{{ $val }}"
                       {{ $selectedStatus === (string)$val ? 'checked' : '' }}
                       onchange="onCuisineDropdownChange(this, 'status')" />
                <span class="cuisine-item-name">{{ $name }}</span>
                <span class="cuisine-item-check"><i class="fas fa-check"></i></span>
              </label>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-12">
        <label class="form-label d-none d-lg-block">&nbsp;</label>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-warning text-white flex-grow-1" style="height: 38px;">Filter</button>
          <a href="{{ route('affiliate-promotions.index') }}" class="btn btn-label-secondary d-flex align-items-center justify-content-center" style="height: 38px; min-width: 80px;">Reset</a>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Promotions Table -->
<div class="card border-0 shadow-sm">
  <div id="ajax-table-container">
    @include('new_content.admin.affiliate_promotions._table')
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {

      // Initialize Select2 on all filter dropdowns (matching Newsletter Subscribers style)
      if ($.fn.select2) {
          $('.select2').each(function() {
              var $this = $(this);
              $this.select2({
                  minimumResultsForSearch: Infinity,
                  width: '100%',
                  dropdownParent: $this.parent()
              });
          });
      }

      // Status toggle handler
      $(document).on('change', '.status-toggle-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).prop('checked') ? 1 : 0;
          var $switch = $(this);

          $.ajax({
              url: '{{ url("admin/affiliate-promotions/status") }}/' + id + '/' + status,
              type: 'GET',
              success: function (response) {
                  if (response.success) {
                      $switch.data('status', response.status);
                  }
              },
              error: function (xhr, status, error) {
                  console.error(error);
          }
          });
      });
  });

  // Custom Generalized Dropdown Logic
  function toggleCuisineDropdown(type) {
    const panel  = document.getElementById(type + 'DropdownPanel');
    const arrow  = document.getElementById(type + 'Arrow');
    const trigger = document.getElementById(type + 'Trigger');
    const isOpen = panel.style.display !== 'none';
    
    // Close all dropdowns first
    document.querySelectorAll('.cuisine-dropdown-panel').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.cuisine-dropdown-arrow').forEach(a => a.style.transform = 'rotate(0deg)');
    document.querySelectorAll('.cuisine-dropdown-trigger').forEach(t => t.classList.remove('open'));
    
    if (isOpen) {
        panel.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)';
        trigger.classList.remove('open');
    } else {
        panel.style.display = 'block';
        arrow.style.transform = 'rotate(180deg)';
        trigger.classList.add('open');
        const input = document.getElementById(type + 'SearchInput');
        if(input) input.focus();
    }
  }

  function filterCuisineDropdown(val, type) {
    const term  = val.toLowerCase();
    const items = document.querySelectorAll('#' + type + 'ItemsList .cuisine-item');
    let   found = 0;
    items.forEach(item => {
      const name = item.querySelector('.cuisine-item-name').textContent.toLowerCase();
      const show = name.includes(term);
      item.style.display = show ? '' : 'none';
      if (show) found++;
    });
    const noRes = document.getElementById(type + 'NoResults');
    if(noRes) noRes.classList.toggle('d-none', found > 0);
  }

  function onCuisineDropdownChange(rb, type) {
    const id    = rb.dataset.id;
    const name  = rb.dataset.name;
    const hidden= document.getElementById(type + '_value');
    const ph    = document.getElementById(type + 'Placeholder');

    hidden.value = id;
    document.querySelectorAll('#' + type + 'ItemsList .cuisine-item').forEach(l => l.classList.remove('selected'));
    
    const labelId = type + '-label-' + (id === '' ? 'all' : id);
    const label = document.getElementById(labelId);
    if(label) label.classList.add('selected');

    if(ph) ph.textContent = name;
    
    // Auto-close dropdown
    document.getElementById(type + 'DropdownPanel').style.display = 'none';
    const arrow = document.getElementById(type + 'Arrow');
    if(arrow) arrow.style.transform = 'rotate(0deg)';
    const trigger = document.getElementById(type + 'Trigger');
    if(trigger) trigger.classList.remove('open');
  }

  // Close dropdown on outside click
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.cuisine-dropdown-wrapper')) {
      document.querySelectorAll('.cuisine-dropdown-panel').forEach(p => p.style.display = 'none');
      document.querySelectorAll('.cuisine-dropdown-arrow').forEach(a => a.style.transform = 'rotate(0deg)');
      document.querySelectorAll('.cuisine-dropdown-trigger').forEach(t => t.classList.remove('open'));
    }
  });

</script>
@endsection
