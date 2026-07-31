@extends('layouts/layoutMaster')

@section('title', 'Newsletter Subscribers')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Newsletter Subscribers</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <div>
    <h3 class="mb-1 fw-bold">Newsletter Subscribers</h3>
    <p class="text-muted mb-0">Manage and export all double opt-in subscriber memberships.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
        <i class="bx bx-filter-alt"></i> Filters
      </button>
      <button type="submit" form="filterForm" name="export" value="csv" class="btn btn-success">
        <i class="bx bx-export"></i> Export CSV
      </button>
  </div>
</div>

@include('layouts.messages')

</style>

<!-- Filters Section -->
<div class="collapse mb-4 {{ request()->anyFilled(['search', 'verified', 'active', 'source', 'date_from', 'date_to']) ? 'show' : '' }}" id="filterCollapse">
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form id="filterForm" method="GET" action="{{ route('subscribers.index') }}" class="row g-3">
      <div class="col-lg-3 col-md-6 col-12">
        <label class="form-label fw-semibold">Search Email</label>
        <input type="text" name="search" class="form-control" placeholder="Search by email..." value="{{ request('search') }}" style="height: 38px;">
      </div>
      <div class="col-lg-2 col-md-6 col-12">
        <label class="form-label fw-semibold">Verified Status</label>
        @php
            $verStatuses = ['' => 'All Statuses', '1' => 'Verified', '0' => 'Unverified'];
        @endphp
        <x-filter-dropdown name="verified" :options="$verStatuses" :selected="request('verified')" placeholder="All Statuses" />
      </div>
      <div class="col-lg-2 col-md-6 col-12">
        <label class="form-label fw-semibold">Active Status</label>
        @php
            $actStatuses = ['' => 'All', '1' => 'Active', '0' => 'Inactive'];
        @endphp
        <x-filter-dropdown name="active" :options="$actStatuses" :selected="request('active')" placeholder="All" />
      </div>
      <div class="col-lg-2 col-md-6 col-12">
        <label class="form-label fw-semibold">Source</label>
        @php
            $sources = ['' => 'All Sources', 'explorer_club' => 'Explorer Club', 'footer' => 'Footer'];
        @endphp
        <x-filter-dropdown name="source" :options="$sources" :selected="request('source')" placeholder="All Sources" />
      </div>
      <div class="col-lg-3 col-md-12 col-12 d-flex align-items-end">
        <div class="d-flex gap-2 w-100">
          <a href="{{ route('subscribers.index') }}" class="btn btn-secondary w-50" style="height: 38px;">Reset</a>
          <button type="submit" class="btn btn-primary w-50" style="height: 38px;">Filter</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>

<!-- Subscribers List Card -->
<div class="card border-0 shadow-sm">
  <div class="card-body pt-4">
    <div id="ajax-table-container">
      @include('new_content.admin.subscribers._table')
    </div>
  </div>
</div>

<!-- Hidden delete form -->
<form id="deleteSubscriberForm" method="POST" action="" class="d-none">
  @csrf
  @method('DELETE')
</form>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    // Delete button handler using SweetAlert2
    $('.delete-subscriber-btn').on('click', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#deleteSubscriberForm');
                form.attr('action', '{{ url("admin/subscribers") }}/' + id);
                form.submit();
            }
        });
    });
});

</script>
@endsection
