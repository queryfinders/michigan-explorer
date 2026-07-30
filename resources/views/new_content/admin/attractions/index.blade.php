@extends('layouts/layoutMaster')

@section('title', 'Attractions')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Attractions</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Attractions</h3>
    <p class="text-muted mb-0">Manage all local attractions and points of interest.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
        <input type="text" class="form-control global-search-input" placeholder="Search..." style="width: 220px;" />
        <a href="{{ route('attractions.create') }}" class="btn btn-warning text-white">Add Attraction</a>
    </div>
</div>


@include('layouts.messages')

<div class="card">
  <div id="ajax-table-container">
    @include('new_content.admin.attractions._table')
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.attraction-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).prop('checked') ? 1 : 0;
          var $switch = $(this);
          
          
          
          $.ajax({
              url: '{{ url("admin/attractions/status") }}/' + id + '/' + status,
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
