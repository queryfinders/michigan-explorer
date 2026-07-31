@extends('layouts/layoutMaster')

@section('title', 'Events')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Events</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Events</h3>
    <p class="text-muted mb-0">Manage all upcoming events and festivals.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
        <i class="bx bx-filter-alt"></i> Filters
      </button>
      <button type="submit" form="filterForm" name="export" value="csv" class="btn btn-success">
        <i class="bx bx-export"></i> Export CSV
      </button>
      <a href="{{ route('events.create') }}" class="btn btn-warning text-white">Add Event</a>
  </div>
</div>

<div class="collapse mb-4 {{ request()->anyFilled(['name', 'status', 'event_date']) ? 'show' : '' }}" id="filterCollapse">
  <div class="card card-body">
    <form id="filterForm" method="GET" action="{{ route('events.index') }}">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Event Name</label>
          <input type="text" name="name" class="form-control" value="{{ request('name') }}" placeholder="Search name...">
        </div>
        <div class="col-md-4">
          <label class="form-label">Status</label>
          @php
            $statusOptions = ['' => 'All Status', '1' => 'Active', '0' => 'Inactive'];
          @endphp
          <x-filter-dropdown name="status" :options="$statusOptions" :selected="request('status')" placeholder="All Status" />
        </div>
        <div class="col-md-4">
          <label class="form-label">Event Date</label>
          <input type="date" name="event_date" class="form-control" value="{{ request('event_date') }}">
        </div>
      </div>
      <div class="mt-3 d-flex justify-content-end gap-2">
        <a href="{{ route('events.index') }}" class="btn btn-secondary">Reset</a>
        <button type="submit" class="btn btn-primary">Apply Filters</button>
      </div>
    </form>
  </div>
</div>


@include('layouts.messages')

<div class="card">
  <div id="ajax-table-container">
    @include('new_content.admin.events._table')
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.event-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).prop('checked') ? 1 : 0;
          var $switch = $(this);
          
          
          
          $.ajax({
              url: '{{ url("admin/events/status") }}/' + id + '/' + status,
              type: 'GET',
              success: function (response) {
                  $switch.data('status', status);
               },
              error: function (xhr, status, error) {
                  console.error(error);
              }
          });
      });
  });
</script>
@endsection
