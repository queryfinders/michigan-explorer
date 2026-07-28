@extends('layouts/layoutMaster')

@section('title', 'Blogs')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Blogs</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Blogs</h3>
    <p class="text-muted mb-0">Manage all blog posts and articles.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
        <input type="text" class="form-control global-search-input" placeholder="Search..." style="width: 220px;" />
        <a href="{{ route('blogs.create') }}" class="btn btn-warning text-white">Add Blog</a>
    </div>
</div>


@include('layouts.messages')

<div class="card">
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <th>Title</th>
          <th>Category</th>
          <th>Status</th>
          <th>Published At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($blogs as $blog)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td><strong>{{ $blog->title }}</strong></td>
          <td>{{ $blog->category ? $blog->category->name : 'N/A' }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input blog-status-switch" data-id="{{ $blog->id }}" data-status="{{ $blog->status }}" {{ $blog->status == 'published' ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td id="pub-date-{{ $blog->id }}">{{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') : 'N/A' }}</td>
          <td>
            <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
            Showing {{ $blogs->firstItem() ?? 0 }} to {{ $blogs->lastItem() ?? 0 }} out of {{ $blogs->total() }} records
        </div>
        <div>
            {{ $blogs->links() }}
        </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.blog-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).data('status');
          var $switch = $(this);

          $.ajax({
              url: '{{ url("admin/blogs/status") }}/' + id + '/' + status,
              type: 'GET',
              success: function (response) {
                  if (response.success) {
                      $switch.data('status', response.status);
                      
                      // Update date if active
                      if (response.status === 'published') {
                          var now = new Date();
                          var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                          var dateStr = months[now.getMonth()] + ' ' + String(now.getDate()).padStart(2, '0') + ', ' + now.getFullYear();
                          $('#pub-date-' + id).text(dateStr);
                      } else {
                          // Optional: keep it or show N/A when draft
                          // Let's reload page or just update text if preferred, keeping it simple
                      }
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


