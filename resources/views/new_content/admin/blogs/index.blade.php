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
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
        <i class="bx bx-filter-alt"></i> Filters
      </button>
      <button type="submit" form="filterForm" name="export" value="csv" class="btn btn-success">
        <i class="bx bx-export"></i> Export CSV
      </button>
      <a href="{{ route('blogs.create') }}" class="btn btn-warning text-white">Add Blog</a>
  </div>
</div>

<div class="collapse mb-4 {{ request()->anyFilled(['title', 'category', 'author', 'status', 'date_from', 'date_to']) ? 'show' : '' }}" id="filterCollapse">
  <div class="card card-body">
    <form id="filterForm" method="GET" action="{{ route('blogs.index') }}">
      <div class="row g-3">
        <div class="col-md-2">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control" value="{{ request('title') }}" placeholder="Search title...">
        </div>
        <div class="col-md-2">
          <label class="form-label">Category</label>
          @php
            $catOptions = ['' => 'All Categories'];
            if(isset($categories)) {
                foreach($categories as $cat) {
                    $catOptions[$cat->id] = $cat->name;
                }
            }
          @endphp
          <x-filter-dropdown name="category" :options="$catOptions" :selected="request('category')" placeholder="All Categories" :searchable="true" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Author</label>
          @php
            $authorOptions = ['' => 'All Authors'];
            if(isset($authors)) {
                foreach($authors as $author) {
                    $authorOptions[$author->id] = $author->name;
                }
            }
          @endphp
          <x-filter-dropdown name="author" :options="$authorOptions" :selected="request('author')" placeholder="All Authors" :searchable="true" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Status</label>
          @php
            $statusOptions = ['' => 'All Status', 'published' => 'Published', 'draft' => 'Draft', 'scheduled' => 'Scheduled'];
          @endphp
          <x-filter-dropdown name="status" :options="$statusOptions" :selected="request('status')" placeholder="All Status" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Published From</label>
          <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
          <label class="form-label">Published To</label>
          <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
      </div>
      <div class="mt-3 d-flex justify-content-end gap-2">
        <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Reset</a>
        <button type="submit" class="btn btn-primary">Apply Filters</button>
      </div>
    </form>
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


