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
        @foreach($attractions as $attraction)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td><strong>{{ $attraction->name }}</strong></td>
          <td>{{ $attraction->category ? $attraction->category->name : 'N/A' }}</td>
          <td>{{ $attraction->city }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input attraction-status-switch" data-id="{{ $attraction->id }}" data-status="{{ $attraction->status }}" {{ $attraction->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('attractions.edit', $attraction->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('attractions.destroy', $attraction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
            Showing {{ $attractions->firstItem() ?? 0 }} to {{ $attractions->lastItem() ?? 0 }} out of {{ $attractions->total() }} records
        </div>
        <div>
            {{ $attractions->links() }}
        </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.attraction-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).data('status');
          var $switch = $(this);
          
          var newStatus = status == 1 ? 0 : 1;
          
          $.ajax({
              url: '{{ url("admin/attractions/status") }}/' + id + '/' + newStatus,
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
