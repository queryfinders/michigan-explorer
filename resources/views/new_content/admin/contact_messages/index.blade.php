@extends('layouts/layoutMaster')

@section('title', 'Contact Messages')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Contact Messages</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <div>
    <h3 class="mb-1 fw-bold">Contact Messages</h3>
    <p class="text-muted mb-0">Review and manage contact submissions from users.</p>
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



<!-- Filters Section -->
<div class="collapse mb-4 {{ request()->anyFilled(['search', 'status', 'date_from', 'date_to']) ? 'show' : '' }}" id="filterCollapse">
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form id="filterForm" method="GET" action="{{ route('contact-messages.index') }}" class="row g-3">
      <div class="col-lg-3 col-md-6 col-12">
        <label class="form-label fw-semibold">Search Messages</label>
        <input type="text" name="search" class="form-control" placeholder="Search by name, email or subject..." value="{{ request('search') }}" style="height: 38px;">
      </div>
      <div class="col-lg-3 col-md-6 col-12">
        <label class="form-label fw-semibold">Status</label>
        @php
            $statusOptions = ['' => 'All Statuses', 'new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'closed' => 'Closed'];
        @endphp
        <x-filter-dropdown name="status" :options="$statusOptions" :selected="request('status')" placeholder="All Statuses" />
      </div>
      <div class="col-lg-2 col-md-6 col-12">
        <label class="form-label fw-semibold">Date From</label>
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
      </div>
      <div class="col-lg-2 col-md-6 col-12">
        <label class="form-label fw-semibold">Date To</label>
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
      </div>
      <div class="col-lg-2 col-md-12 col-12 d-flex align-items-end">
        <div class="d-flex gap-2 w-100">
          <a href="{{ route('contact-messages.index') }}" class="btn btn-secondary w-50" style="height: 38px;">Reset</a>
          <button type="submit" class="btn btn-primary w-50" style="height: 38px;">Filter</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body pt-4">
    <div id="ajax-table-container">
      @include('new_content.admin.contact_messages._table')
    </div>
  </div>
</div>

<!-- Hidden delete form -->
<form id="deleteMessageForm" method="POST" action="" class="d-none">
  @csrf
  @method('DELETE')
</form>
@endsection

@section('page-script')
<script>
$(document).ready(function() {


    // Delete button handler using SweetAlert2
    $('.delete-message-btn').on('click', function() {
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
                var form = $('#deleteMessageForm');
                form.attr('action', '{{ url("admin/contact-messages") }}/' + id);
                form.submit();
            }
        });
    });
});
</script>
@endsection
