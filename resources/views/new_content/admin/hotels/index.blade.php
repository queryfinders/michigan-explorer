@extends('layouts/layoutMaster')

@section('title', 'Hotels')

@section('content')
<div class="card">
  @include('layouts.messages')
  <h5 class="card-header">Hotels</h5>
  <div class="d-flex justify-content-end me-md-4 mb-3">
    <a href="{{ route('hotels.create') }}" class="btn btn-primary text-white me-3">Add Hotel</a>
  </div>
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Category</th>
          <th>City</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($hotels as $hotel)
        <tr>
          <td>{{ $hotel->id }}</td>
          <td>{{ $hotel->name }}</td>
          <td>{{ $hotel->category ? $hotel->category->name : 'N/A' }}</td>
          <td>{{ $hotel->city }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input hotel-status-switch" data-id="{{ $hotel->id }}" data-status="{{ $hotel->status }}" {{ $hotel->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('hotels.edit', $hotel->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('hotels.destroy', $hotel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $hotels->links() }}
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.hotel-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).data('status');
          var $switch = $(this);

          $.ajax({
              url: '{{ url("admin/hotels/status") }}/' + id + '/' + status,
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
