@extends('layouts/layoutMaster')

@section('title', 'Newsletter Subscribers')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Newsletter Subscribers</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <div>
    <h3 class="mb-1 fw-bold">Newsletter Subscribers</h3>
    <p class="text-muted mb-0">Manage and export all double opt-in subscriber memberships.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
    <a href="{{ route('subscribers.export', 'csv') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-file-csv me-1"></i> Export CSV</a>
    <a href="{{ route('subscribers.export', 'excel') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-file-excel me-1"></i> Export Excel</a>
  </div>
</div>

@include('layouts.messages')

<style>
  /* Custom Dropdown Styles (from Affiliate Promotions/Attraction page) */
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
</style>

<!-- Filters Section -->
<div class="card mb-4 border-0 shadow-sm">
  <div class="card-body">
    <form method="GET" action="{{ route('subscribers.index') }}" class="row g-3">
      <div class="col-lg-3 col-md-6 col-12">
        <label class="form-label fw-semibold">Search Email</label>
        <input type="text" name="search" class="form-control" placeholder="Search by email..." value="{{ request('search') }}" style="height: 38px;">
      </div>
      <div class="col-lg-2 col-md-6 col-12">
        <label class="form-label fw-semibold">Verified Status</label>
        @php
            $verStatuses = ['' => 'All Statuses', '1' => 'Verified', '0' => 'Unverified'];
            $vStatus = request('verified', '');
        @endphp
        <input type="hidden" name="verified" id="verified_value" value="{{ $vStatus }}">
        <div class="cuisine-dropdown-wrapper" id="verifiedDropdownWrapper">
          <div class="cuisine-dropdown-trigger" id="verifiedTrigger" onclick="toggleCuisineDropdown('verified')">
            <div class="cuisine-tags-area">
              <span class="category-selected-text" id="verifiedPlaceholder">{{ $verStatuses[$vStatus] ?? 'All Statuses' }}</span>
            </div>
            <i class="fas fa-chevron-down cuisine-dropdown-arrow" id="verifiedArrow"></i>
          </div>
          <div class="cuisine-dropdown-panel" id="verifiedDropdownPanel" style="display:none;">
            <div class="cuisine-items-list" id="verifiedItemsList" style="padding-top: 6px;">
              @foreach($verStatuses as $val => $name)
              <label class="cuisine-item {{ $vStatus === (string)$val ? 'selected' : '' }}" id="verified-label-{{ $val === '' ? 'all' : $val }}">
                <input type="radio" name="_verified_radio" value="{{ $val }}"
                       class="cat-rb d-none" data-name="{{ $name }}" data-id="{{ $val }}"
                       {{ $vStatus === (string)$val ? 'checked' : '' }} onchange="onCuisineDropdownChange(this, 'verified')" />
                <span class="cuisine-item-name">{{ $name }}</span>
                <span class="cuisine-item-check"><i class="fas fa-check"></i></span>
              </label>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-md-6 col-12">
        <label class="form-label fw-semibold">Active Status</label>
        @php
            $actStatuses = ['' => 'All', '1' => 'Active', '0' => 'Inactive'];
            $aStatus = request('active', '');
        @endphp
        <input type="hidden" name="active" id="active_value" value="{{ $aStatus }}">
        <div class="cuisine-dropdown-wrapper" id="activeDropdownWrapper">
          <div class="cuisine-dropdown-trigger" id="activeTrigger" onclick="toggleCuisineDropdown('active')">
            <div class="cuisine-tags-area">
              <span class="category-selected-text" id="activePlaceholder">{{ $actStatuses[$aStatus] ?? 'All' }}</span>
            </div>
            <i class="fas fa-chevron-down cuisine-dropdown-arrow" id="activeArrow"></i>
          </div>
          <div class="cuisine-dropdown-panel" id="activeDropdownPanel" style="display:none;">
            <div class="cuisine-items-list" id="activeItemsList" style="padding-top: 6px;">
              @foreach($actStatuses as $val => $name)
              <label class="cuisine-item {{ $aStatus === (string)$val ? 'selected' : '' }}" id="active-label-{{ $val === '' ? 'all' : $val }}">
                <input type="radio" name="_active_radio" value="{{ $val }}"
                       class="cat-rb d-none" data-name="{{ $name }}" data-id="{{ $val }}"
                       {{ $aStatus === (string)$val ? 'checked' : '' }} onchange="onCuisineDropdownChange(this, 'active')" />
                <span class="cuisine-item-name">{{ $name }}</span>
                <span class="cuisine-item-check"><i class="fas fa-check"></i></span>
              </label>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-md-6 col-12">
        <label class="form-label fw-semibold">Source</label>
        @php
            $sources = ['' => 'All Sources', 'explorer_club' => 'Explorer Club', 'footer' => 'Footer'];
            $srcVal = request('source', '');
        @endphp
        <input type="hidden" name="source" id="source_value" value="{{ $srcVal }}">
        <div class="cuisine-dropdown-wrapper" id="sourceDropdownWrapper">
          <div class="cuisine-dropdown-trigger" id="sourceTrigger" onclick="toggleCuisineDropdown('source')">
            <div class="cuisine-tags-area">
              <span class="category-selected-text" id="sourcePlaceholder">{{ $sources[$srcVal] ?? 'All Sources' }}</span>
            </div>
            <i class="fas fa-chevron-down cuisine-dropdown-arrow" id="sourceArrow"></i>
          </div>
          <div class="cuisine-dropdown-panel" id="sourceDropdownPanel" style="display:none;">
            <div class="cuisine-items-list" id="sourceItemsList" style="padding-top: 6px;">
              @foreach($sources as $val => $name)
              <label class="cuisine-item {{ $srcVal === (string)$val ? 'selected' : '' }}" id="source-label-{{ $val === '' ? 'all' : $val }}">
                <input type="radio" name="_source_radio" value="{{ $val }}"
                       class="cat-rb d-none" data-name="{{ $name }}" data-id="{{ $val }}"
                       {{ $srcVal === (string)$val ? 'checked' : '' }} onchange="onCuisineDropdownChange(this, 'source')" />
                <span class="cuisine-item-name">{{ $name }}</span>
                <span class="cuisine-item-check"><i class="fas fa-check"></i></span>
              </label>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-12 col-12">
        <label class="form-label d-none d-lg-block">&nbsp;</label>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-warning text-white flex-grow-1" style="height: 38px;">Filter</button>
          <a href="{{ route('subscribers.index') }}" class="btn btn-label-secondary d-flex align-items-center justify-content-center" style="height: 38px; min-width: 80px;">Reset</a>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Subscribers List Card -->
<div class="card border-0 shadow-sm">
  <div class="card-body pt-4">
    <div id="ajax-table-container">
      @include('new_content.admin.subscribers._table')
    </div>
  </form>
</div>

<!-- Hidden delete form -->
<form id="deleteSubscriberForm" method="POST" action="" class="d-none">
  @csrf
  @method('DELETE')
  </div>
</div>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    // Delete button handler using SweetAlert2
    $('.delete-subscriber-btn').on('click', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#deleteSubscriberForm');
                form.attr('action', '{{ url("admin/subscribers") }}/' + id);
                form.submit();
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
