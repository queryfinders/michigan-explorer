@extends('layouts/layoutMaster')

@section('title', 'Booking Features')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">Hotels</a></li>
    <li class="breadcrumb-item active" aria-current="page">Booking Features</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Booking Features</h3>
    <p class="text-muted mb-0">Manage booking feature options.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
        <input type="text" class="form-control global-search-input" placeholder="Search..." style="width: 220px;" />
        <a href="{{ route('booking-features.create') }}" class="btn btn-warning text-white">Add Booking Feature</a>
    </div>
</div>


@include('layouts.messages')

<div class="card">
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <th>Name</th>
          <th>Icon</th>
          <th>Sort Order</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($features as $feature)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td><strong>{{ $feature->name }}</strong></td>
          <td>
            @if($feature->icon)
            <span class="badge bg-light text-dark p-2 border">
              <i class="{{ $feature->icon }} me-2 text-primary"></i> <code>{{ $feature->icon }}</code>
            </span>
            @endif
          </td>
          <td>{{ $feature->sort_order }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input feature-status-switch" data-id="{{ $feature->id }}" data-status="{{ $feature->is_active }}" {{ $feature->is_active == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('booking-features.edit', $feature->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('booking-features.destroy', $feature->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
  <div class="d-flex justify-content-center mt-3">
    {{ $features->links() }}
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.feature-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).data('status');
          var $switch = $(this);
          
          var newStatus = status == 1 ? 0 : 1;
          
          $.ajax({
              url: '{{ url("admin/booking-features/status") }}/' + id + '/' + newStatus,
              type: 'GET',
              success: function (response) {
                  $switch.data('status', newStatus);
              },
              error: function (xhr, status, error) {
                  console.error(error);
              }
          });
      });
  });
</script>
@endsection

