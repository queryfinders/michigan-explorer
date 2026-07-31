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

</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Affiliate Promotions</h3>
    <p class="text-muted mb-0">Create and manage dynamic promotional banners across your website.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
      <i class="bx bx-filter-alt"></i> Filters
    </button>
    <button type="submit" form="filterForm" name="export" value="csv" class="btn btn-success">
      <i class="bx bx-export"></i> Export CSV
    </button>
    <a href="{{ route('affiliate-promotions.create') }}" class="btn btn-warning text-white">Add Promotion</a>
  </div>
</div>

@include('layouts.messages')

<!-- Filters Section -->
<div class="collapse mb-4 {{ request()->anyFilled(['search', 'placement', 'status']) ? 'show' : '' }}" id="filterCollapse">
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form id="filterForm" method="GET" action="{{ route('affiliate-promotions.index') }}" class="row g-3">
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
        @endphp
        <x-filter-dropdown name="placement" :options="$placements" :selected="request('placement')" placeholder="All Placements" :searchable="true" />
      </div>
      <div class="col-lg-2 col-md-6 col-12">
        <label class="form-label fw-semibold">Active Status</label>
        @php
            $statuses = [
                '' => 'All',
                '1' => 'Active',
                '0' => 'Inactive'
            ];
        @endphp
        <x-filter-dropdown name="status" :options="$statuses" :selected="request('status')" placeholder="All Status" />
      </div>
      <div class="col-lg-4 col-md-6 col-12 d-flex align-items-end">
        <div class="d-flex gap-2 w-100">
          <a href="{{ route('affiliate-promotions.index') }}" class="btn btn-secondary w-50" style="height: 38px;">Reset</a>
          <button type="submit" class="btn btn-primary w-50" style="height: 38px;">Filter</button>
        </div>
      </div>
    </form>
  </div>
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

</script>
@endsection
