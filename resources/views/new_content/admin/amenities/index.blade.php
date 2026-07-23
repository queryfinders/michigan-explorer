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
  <div>
    <a href="{{ route('amenities.create') }}" class="btn btn-primary">Add Amenity</a>
  </div>
</div>


@include('layouts.messages')

<div class="card">
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Slug</th>
          <th>Icon</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($amenities as $amenity)
        <tr>
          <td>{{ $amenity->id }}</td>
          <td><strong>{{ $amenity->name }}</strong></td>
          <td>{{ $amenity->slug }}</td>
          <td>
            <span class="badge bg-light text-dark p-2 border">
              <i class="fas {{ $amenity->icon }} me-2 text-primary"></i> <code>{{ $amenity->icon }}</code>
            </span>
          </td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input amenity-status-switch" data-id="{{ $amenity->id }}" data-status="{{ $amenity->status }}" {{ $amenity->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('amenities.edit', $amenity->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('amenities.destroy', $amenity->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.amenity-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).data('status');
          var $switch = $(this);
          
          $.ajax({
              url: '{{ url("admin/amenities/status") }}/' + id + '/' + status,
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
