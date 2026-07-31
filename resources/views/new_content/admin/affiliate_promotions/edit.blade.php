@extends('layouts/layoutMaster')

@section('title', 'Edit Affiliate Promotion')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('affiliate-promotions.index') }}">Affiliate Promotions</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Promotion</li>
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
    <h5 class="mb-0 fw-bold">Edit Affiliate Promotion</h5>
  </div>
  <div class="card-body">
    <form id="affiliatePromotionEditForm" action="{{ route('affiliate-promotions.update', $promotion->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      @include('new_content.admin.affiliate_promotions.form', ['promotion' => $promotion])

    </form>
  </div>
</div>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('form');
      if (form) {
          form.addEventListener('submit', function(event) {
              let isValid = true;
              let firstInvalid = null;

              const fields = [
                  { id: 'placement_value' },
                  { id: 'priority' },
                  { id: 'badge_text' },
                  { id: 'cta_text' },
                  { id: 'title' },
                  { id: 'subtitle' }
              ];

              fields.forEach(f => {
                  const el = document.getElementById(f.id);
                  if (el) {
                      if (!el.value.trim()) {
                          isValid = false;
                          if (!firstInvalid) firstInvalid = el;
                          if (f.id === 'placement_value') {
                              const trigger = document.getElementById('placementTrigger');
                              if (trigger) trigger.classList.add('border-danger');
                          } else {
                              el.classList.add('is-invalid');
                          }
                      } else {
                          if (f.id === 'placement_value') {
                              const trigger = document.getElementById('placementTrigger');
                              if (trigger) trigger.classList.remove('border-danger');
                          } else {
                              el.classList.remove('is-invalid');
                          }
                      }
                  }
              });

              if (!isValid) {
                  event.preventDefault();
                  setTimeout(() => {
                      if (firstInvalid) {
                          if (firstInvalid.id === 'placement_value') {
                              const trigger = document.getElementById('placementTrigger');
                              if (trigger) trigger.scrollIntoView({ behavior: 'smooth', block: 'center' });
                          } else {
                              firstInvalid.focus();
                              firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                          }
                      }
                  }, 100);
              }
          });
      }
  });

  // Custom Placement Dropdown Logic
  function togglePlacementDropdown() {
    const panel = document.getElementById('placementDropdownPanel');
    const arrow = document.getElementById('placementArrow');
    const trigger = document.getElementById('placementTrigger');
    const isOpen = panel.style.display === 'block';
    
    // close link dropdown if open
    document.getElementById('linkDropdownPanel').style.display = 'none';
    document.getElementById('linkArrow').style.transform = 'rotate(0deg)';
    document.getElementById('linkTrigger').classList.remove('open');

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

  function toggleLinkDropdown() {
    const panel = document.getElementById('linkDropdownPanel');
    const arrow = document.getElementById('linkArrow');
    const trigger = document.getElementById('linkTrigger');
    const isOpen = panel.style.display === 'block';
    
    // close placement dropdown if open
    document.getElementById('placementDropdownPanel').style.display = 'none';
    document.getElementById('placementArrow').style.transform = 'rotate(0deg)';
    document.getElementById('placementTrigger').classList.remove('open');

    if (isOpen) {
        panel.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)';
        trigger.classList.remove('open');
    } else {
        panel.style.display = 'block';
        arrow.style.transform = 'rotate(180deg)';
        trigger.classList.add('open');
        document.getElementById('linkSearchInput').focus();
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

  function filterLinks(val) {
    const term  = val.toLowerCase();
    const items = document.querySelectorAll('#linkItemsList .cuisine-item');
    let   found = 0;
    items.forEach(item => {
      const name = item.querySelector('.cuisine-item-name').textContent.toLowerCase();
      const show = name.includes(term);
      item.style.display = show ? '' : 'none';
      if (show) found++;
    });
    document.getElementById('linkNoResults').classList.toggle('d-none', found > 0);
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

  function onLinkChange(rb) {
    const id    = rb.dataset.id;
    const name  = rb.dataset.name;
    const hidden= document.getElementById('affiliate_link_id_value');
    const ph    = document.getElementById('linkPlaceholder');

    hidden.value = id;
    document.querySelectorAll('#linkItemsList .cuisine-item').forEach(l => l.classList.remove('selected'));
    
    const label = document.getElementById(id ? 'link-label-' + id : 'link-label-empty');
    if(label) label.classList.add('selected');

    ph.textContent = name;
    
    // Auto-close dropdown
    document.getElementById('linkDropdownPanel').style.display = 'none';
    document.getElementById('linkArrow').style.transform = 'rotate(0deg)';
    document.getElementById('linkTrigger').classList.remove('open');
    document.getElementById('linkTrigger').classList.remove('border-danger');
  }

  // Close dropdown on outside click
  document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('placementDropdownWrapper');
    const linkWrapper = document.getElementById('linkDropdownWrapper');
    
    if (wrapper && !wrapper.contains(e.target)) {
      const panel = document.getElementById('placementDropdownPanel');
      if(panel && panel.style.display !== 'none') {
        panel.style.display = 'none';
        document.getElementById('placementArrow').style.transform = 'rotate(0deg)';
        document.getElementById('placementTrigger').classList.remove('open');
      }
    }

    if (linkWrapper && !linkWrapper.contains(e.target)) {
      const panel = document.getElementById('linkDropdownPanel');
      if(panel && panel.style.display !== 'none') {
        panel.style.display = 'none';
        document.getElementById('linkArrow').style.transform = 'rotate(0deg)';
        document.getElementById('linkTrigger').classList.remove('open');
      }
    }
  });
</script>
@endsection
