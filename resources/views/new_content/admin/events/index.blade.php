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
        <input type="text" class="form-control global-search-input" placeholder="Search..." style="width: 220px;" />
        <a href="{{ route('events.create') }}" class="btn btn-warning text-white">Add Event</a>
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
