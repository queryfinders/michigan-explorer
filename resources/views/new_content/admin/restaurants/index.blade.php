@extends('layouts/layoutMaster')

@section('title', 'Restaurants')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Restaurants</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Restaurants</h3>
    <p class="text-muted mb-0">Manage all restaurants and eateries in Michigan.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
        <i class="bx bx-filter-alt"></i> Filters
      </button>
      <button type="submit" form="filterForm" name="export" value="csv" class="btn btn-success">
        <i class="bx bx-export"></i> Export CSV
      </button>
      <a href="{{ route('restaurants.create') }}" class="btn btn-warning text-white">Add Restaurant</a>
  </div>
</div>

<div class="collapse mb-4 {{ request()->anyFilled(['search', 'category', 'status', 'is_featured']) ? 'show' : '' }}" id="filterCollapse">
  <div class="card card-body">
    <form id="filterForm" method="GET" action="{{ route('restaurants.index') }}">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Search (Name, City)</label>
          <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search...">
        </div>
        <div class="col-md-3">
          <label class="form-label">Category</label>
          @php
            $catOptions = ['' => 'All Categories'];
            if(isset($categories)) {
                foreach($categories as $cat) {
                    $catOptions[$cat->id] = $cat->name;
                }
            }
          @endphp
          <x-filter-dropdown name="category" :options="$catOptions" :selected="request('category')" placeholder="All Categories" :searchable="true" />
        </div>
        <div class="col-md-3">
          <label class="form-label">Status</label>
          @php
            $statusOptions = ['' => 'All Status', '1' => 'Active', '0' => 'Inactive'];
          @endphp
          <x-filter-dropdown name="status" :options="$statusOptions" :selected="request('status')" placeholder="All Status" />
        </div>
        <div class="col-md-3">
          <label class="form-label">Featured</label>
          @php
            $featuredOptions = ['' => 'All', '1' => 'Featured', '0' => 'Not Featured'];
          @endphp
          <x-filter-dropdown name="is_featured" :options="$featuredOptions" :selected="request('is_featured')" placeholder="All" />
        </div>
      </div>
      <div class="mt-3 d-flex justify-content-end gap-2">
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">Reset</a>
        <button type="submit" class="btn btn-primary">Apply Filters</button>
      </div>
    </form>
  </div>
</div>


@include('layouts.messages')

<div class="card">
  <div id="ajax-table-container">
    @include('new_content.admin.restaurants._table')
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.restaurant-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).prop('checked') ? 1 : 0;
          var $switch = $(this);

          $.ajax({
              url: '{{ url("admin/restaurants/status") }}/' + id + '/' + status,
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


