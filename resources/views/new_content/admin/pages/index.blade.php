@extends('layouts/layoutMaster')

@section('title', 'Pages')

@section('content')
<div class="card">
  @include('layouts.messages')
  <h5 class="card-header">Pages</h5>
  <div class="d-flex justify-content-end me-md-4 mb-3">
    <a href="{{ route('pages.create') }}" class="btn btn-primary text-white me-3">Add Page</a>
  </div>
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Slug</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pages as $page)
        <tr>
          <td>{{ $page->id }}</td>
          <td>{{ $page->title }}</td>
          <td>{{ $page->slug }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input page-status-switch" data-id="{{ $page->id }}" data-status="{{ $page->status }}" {{ $page->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('pages.destroy', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
        {{ $pages->links() }}
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.page-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).data('status');
          var $switch = $(this);
          
          $.ajax({
              url: '{{ url("admin/pages/status") }}/' + id + '/' + status,
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
