@extends('layouts/layoutMaster')

@section('title', 'Amenities')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">Hotels</a></li>
    <li class="breadcrumb-item active" aria-current="page">Amenities</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Amenities</h3>
    <p class="text-muted mb-0">Manage hotel and room amenities.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
        <input type="text" class="form-control global-search-input" placeholder="Search..." style="width: 220px;" />
        <a href="{{ route('amenities.create') }}" class="btn btn-warning text-white">Add Amenity</a>
    </div>
</div>


@include('layouts.messages')

<div class="card" id="ajax-table-container">
    @include('new_content.admin.amenities._table')
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.amenity-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).prop('checked') ? 1 : 0;
          
          $.ajax({
              url: '{{ url("admin/amenities/status") }}/' + id + '/' + status,
              type: 'GET',
              success: function (response) {
                  // status updated successfully
              },
              error: function (xhr, status, error) {
                  console.error(error);
              }
          });
      });
  });
</script>
@endsection

