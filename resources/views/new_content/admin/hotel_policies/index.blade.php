@extends('layouts/layoutMaster')

@section('title', 'Hotel Policies')

@section('content')
<div class="card">
  @include('layouts.messages')
  <h5 class="card-header">Hotel Policies</h5>
  <div class="d-flex justify-content-end me-md-4 mb-3">
    <a href="{{ route('hotel-policies.create') }}" class="btn btn-primary text-white me-3">Add Hotel Policy</a>
  </div>
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
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
          <td>{{ $policy->id }}</td>
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
