@extends('layouts/layoutMaster')

@section('title', 'Hotel Policies')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">Hotels</a></li>
    <li class="breadcrumb-item active" aria-current="page">Hotel Policies</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Hotel Policies</h3>
    <p class="text-muted mb-0">Manage hotel policies and rules.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
        <input type="text" class="form-control global-search-input" placeholder="Search..." style="width: 220px;" />
        <a href="{{ route('hotel-policies.create') }}" class="btn btn-warning text-white">Add Hotel Policy</a>
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
          <th>Input Type</th>
          <th>Sort Order</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($policies as $policy)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td><strong>{{ $policy->name }}</strong></td>
          <td>{{ ucfirst($policy->input_type) }}</td>
          <td>{{ $policy->sort_order }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input policy-status-switch" data-id="{{ $policy->id }}" data-status="{{ $policy->is_active }}" {{ $policy->is_active == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('hotel-policies.edit', $policy->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('hotel-policies.destroy', $policy->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
    {{ $policies->links() }}
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.policy-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).data('status');
          var $switch = $(this);
          
          var newStatus = status == 1 ? 0 : 1;
          
          $.ajax({
              url: '{{ url("admin/hotel-policies/status") }}/' + id + '/' + newStatus,
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

