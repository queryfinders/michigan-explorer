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
</div>

@include('layouts.messages')

<style>
  /* Align select2 dropdown height with standard bootstrap inputs */
  .select2-container--default .select2-selection--single {
    height: 38px !important;
    border: 1px solid #dbdade !important;
    border-radius: 0.375rem !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px !important;
    padding-left: 12px !important;
    color: #5d596c !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px !important;
  }
  .select2-dropdown {
    border: 1px solid #dbdade !important;
    border-radius: 0.375rem !important;
    box-shadow: 0 0.25rem 1rem rgba(168, 170, 174, 0.25) !important;
  }
  .select2-container--default.select2-container--open .select2-selection--single {
    border-color: #7367f0 !important;
  }
  .select2-results__option {
    padding: 8px 12px !important;
    font-size: 0.9rem !important;
    border-radius: 0.25rem !important;
    margin: 2px 4px !important;
  }
  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #7367f0 !important;
    color: #fff !important;
  }
  .select2-container--default .select2-results__option[aria-selected=true] {
    background-color: rgba(115, 103, 240, 0.08) !important;
    color: #7367f0 !important;
    font-weight: 600;
  }
  .hover-primary:hover {
    color: #7367f0 !important;
    text-decoration: underline !important;
  }
</style>

<!-- Filters Section -->
<div class="card mb-4 border-0 shadow-sm">
  <div class="card-body">
    <form method="GET" action="{{ route('contact-messages.index') }}" class="row g-3">
      <div class="col-lg-5 col-md-8 col-12">
        <label class="form-label fw-semibold">Search Messages</label>
        <input type="text" name="search" class="form-control" placeholder="Search by name, email or subject..." value="{{ request('search') }}" style="height: 38px;">
      </div>
      <!-- <div class="col-lg-4 col-md-4 col-12">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select select2" data-allow-clear="true">
          <option value="">All Statuses</option>
          <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
          <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
          <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied</option>
          <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
      </div> -->
      <div class="col-lg-3 col-md-12 col-12">
        <label class="form-label d-none d-lg-block">&nbsp;</label>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary flex-grow-1" style="height: 38px;">Filter</button>
          <a href="{{ route('contact-messages.index') }}" class="btn btn-label-secondary d-flex align-items-center justify-content-center" style="height: 38px; min-width: 80px;">Reset</a>
        </div>
      </div>
    </form>
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
    // Initialize Select2 on the filter dropdowns
    if ($.fn.select2) {
        $('.select2').each(function() {
            var $this = $(this);
            $this.select2({
                minimumResultsForSearch: Infinity,
                width: '100%',
                dropdownParent: $this.parent()
            });
        });
    }

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
