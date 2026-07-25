@extends('layouts/layoutMaster')

@section('title', 'Hotels')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Hotels</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Hotels</h3>
    <p class="text-muted mb-0">Manage all hotels and lodgings in Michigan.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
        <input type="text" class="form-control global-search-input" placeholder="Search..." style="width: 220px;" />
        <a href="{{ route('hotels.create') }}" class="btn btn-warning text-white">Add Hotel</a>
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
          <th>Category</th>
          <th>City</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($hotels as $hotel)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td><strong>{{ $hotel->name }}</strong></td>
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
    <div class="d-flex justify-content-between align-items-center mt-4 px-3 mb-3">
        <div class="text-muted" style="font-size: 0.85rem;">
            Showing {{ $hotels->firstItem() ?? 0 }} to {{ $hotels->lastItem() ?? 0 }} out of {{ $hotels->total() }} records
        </div>
        <div>
            {{ $hotels->links() }}
        </div>
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


