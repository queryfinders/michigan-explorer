@extends('layouts/layoutMaster')

@section('title', 'Hotel Categories')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">Hotels</a></li>
    <li class="breadcrumb-item active" aria-current="page">Categories</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Hotel Categories</h3>
    <p class="text-muted mb-0">Manage all hotel categories and classifications.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
        <i class="bx bx-filter-alt"></i> Filters
      </button>
      <button type="submit" form="filterForm" name="export" value="csv" class="btn btn-success">
        <i class="bx bx-export"></i> Export CSV
      </button>
      <a href="{{ route('hotel-categories.create') }}" class="btn btn-warning text-white">Add Category</a>
  </div>
</div>

<div class="collapse mb-4 {{ request()->anyFilled(['search', 'status']) ? 'show' : '' }}" id="filterCollapse">
  <div class="card card-body">
    <form id="filterForm" method="GET" action="{{ route('hotel-categories.index') }}">
      <div class="row g-3">
        <div class="col-md-5">
          <label class="form-label">Search Name</label>
          <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search name...">
        </div>
        <div class="col-md-4">
          <label class="form-label">Status</label>
          @php
            $statusOptions = ['' => 'All Status', '1' => 'Active', '0' => 'Inactive'];
          @endphp
          <x-filter-dropdown name="status" :options="$statusOptions" :selected="request('status')" placeholder="All Status" />
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <div class="d-flex gap-2 w-100">
            <a href="{{ route('hotel-categories.index') }}" class="btn btn-secondary w-50">Reset</a>
            <button type="submit" class="btn btn-primary w-50">Filter</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

@include('layouts.messages')

<div class="card">
  <div id="ajax-table-container">
    @include('new_content.admin.hotel_categories._table')
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.category-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).prop('checked') ? 1 : 0;
          var $switch = $(this);

          $.ajax({
              url: '{{ url("admin/hotel-categories/status") }}/' + id + '/' + status,
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
