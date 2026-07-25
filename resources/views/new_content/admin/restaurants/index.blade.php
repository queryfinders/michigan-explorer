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
        <input type="text" class="form-control global-search-input" placeholder="Search..." style="width: 220px;" />
        <a href="{{ route('restaurants.create') }}" class="btn btn-warning text-white">Add Restaurant</a>
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
        @foreach($restaurants as $restaurant)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td><strong>{{ $restaurant->name }}</strong></td>
          <td>{{ $restaurant->category ? $restaurant->category->name : 'N/A' }}</td>
          <td>{{ $restaurant->city }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input restaurant-status-switch" data-id="{{ $restaurant->id }}" data-status="{{ $restaurant->status }}" {{ $restaurant->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('restaurants.edit', $restaurant->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('restaurants.destroy', $restaurant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
            Showing {{ $restaurants->firstItem() ?? 0 }} to {{ $restaurants->lastItem() ?? 0 }} out of {{ $restaurants->total() }} records
        </div>
        <div>
            {{ $restaurants->links() }}
        </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.restaurant-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).data('status');
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


