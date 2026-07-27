@extends('layouts/layoutMaster')

@section('title', 'Cuisines')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">Restaurants</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cuisines</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Cuisines</h3>
    <p class="text-muted mb-0">Manage cuisines for your restaurants.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
        <input type="text" class="form-control global-search-input" placeholder="Search..." style="width: 220px;" />
        <a href="{{ route('cuisines.create') }}" class="btn btn-warning text-white">Add Cuisine</a>
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
          <th>Slug</th>
          <th>Sort Order</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($cuisines as $cuisine)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td><strong>{{ $cuisine->name }}</strong></td>
          <td>{{ $cuisine->slug }}</td>
          <td>{{ $cuisine->sort_order }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input cuisine-status-switch" data-id="{{ $cuisine->id }}" data-status="{{ $cuisine->status }}" {{ $cuisine->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('cuisines.edit', $cuisine->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('cuisines.destroy', $cuisine->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
            Showing {{ $cuisines->firstItem() ?? 0 }} to {{ $cuisines->lastItem() ?? 0 }} out of {{ $cuisines->total() }} records
        </div>
        <div>
            {{ $cuisines->links() }}
        </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.cuisine-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).data('status');
          var $switch = $(this);

          $.ajax({
              url: '{{ url("admin/cuisines/status") }}/' + id + '/' + status,
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
