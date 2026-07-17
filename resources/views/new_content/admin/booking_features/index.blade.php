@extends('layouts/layoutMaster')

@section('title', 'Booking Features')

@section('content')
<div class="card">
  @include('layouts.messages')
  <h5 class="card-header">Booking Features</h5>
  <div class="d-flex justify-content-end me-md-4 mb-3">
    <a href="{{ route('booking-features.create') }}" class="btn btn-primary text-white me-3">Add Booking Feature</a>
  </div>
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
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
          <td>{{ $feature->id }}</td>
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
