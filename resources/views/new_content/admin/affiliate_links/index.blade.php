@extends('layouts/layoutMaster')

@section('title', 'Affiliate Links')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">Settings</a></li>
    <li class="breadcrumb-item active" aria-current="page">Affiliate Links</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Affiliate Links</h3>
    <p class="text-muted mb-0">Manage all third-party booking affiliate links.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
    <form action="{{ route('affiliate-links.index') }}" method="GET" class="m-0 p-0">
        <input type="text" name="search" class="form-control global-search-input" placeholder="Search..." style="width: 220px;" value="{{ request('search') }}" />
    </form>
    <a href="{{ route('affiliate-links.create') }}" class="btn btn-warning text-white">Add Link</a>
  </div>
</div>

@include('layouts.messages')

<div class="card">
  <div id="ajax-table-container">
    @include('new_content.admin.affiliate_links._table')
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.status-toggle-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).prop('checked') ? 1 : 0;
          var $switch = $(this);

          $.ajax({
              url: '{{ url("admin/affiliate-links/status") }}/' + id + '/' + status,
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
