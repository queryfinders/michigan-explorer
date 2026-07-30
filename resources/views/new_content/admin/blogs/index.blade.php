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
  <div id="ajax-table-container">
    @include('new_content.admin.blogs._table')
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.blog-status-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).prop('checked') ? 1 : 0;
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


