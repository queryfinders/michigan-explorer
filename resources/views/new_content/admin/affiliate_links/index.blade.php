@extends('layouts/layoutMaster')

@section('title', 'Affiliate Links')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">Settings</a></li>
    <li class="breadcrumb-item active" aria-current="page">Affiliate Links</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Affiliate Links</h3>
    <p class="text-muted mb-0">Manage all third-party booking affiliate links.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
        <i class="bx bx-filter-alt"></i> Filters
      </button>
      <button type="submit" form="filterForm" name="export" value="csv" class="btn btn-success">
        <i class="bx bx-export"></i> Export CSV
      </button>
      <a href="{{ route('affiliate-links.create') }}" class="btn btn-warning text-white">Add Link</a>
  </div>
</div>

<div class="collapse mb-4 {{ request()->anyFilled(['search', 'status']) ? 'show' : '' }}" id="filterCollapse">
  <div class="card card-body">
    <form id="filterForm" method="GET" action="{{ route('affiliate-links.index') }}">
      <div class="row g-3">
        <div class="col-md-5">
          <label class="form-label">Search Name/Provider</label>
          <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search...">
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
            <a href="{{ route('affiliate-links.index') }}" class="btn btn-secondary w-50">Reset</a>
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
    @include('new_content.admin.affiliate_links._table')
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.status-toggle-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).prop('checked') ? 1 : 0;
          var $switch = $(this);

          $.ajax({
              url: '{{ url("admin/affiliate-links/status") }}/' + id + '/' + status,
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
