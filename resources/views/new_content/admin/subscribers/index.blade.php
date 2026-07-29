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
    <a href="{{ route('subscribers.export', 'csv') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-file-csv me-1"></i> Export CSV</a>
    <a href="{{ route('subscribers.export', 'excel') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-file-excel me-1"></i> Export Excel</a>
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
</style>

<!-- Filters Section -->
<div class="card mb-4 border-0 shadow-sm">
  <div class="card-body">
    <form method="GET" action="{{ route('subscribers.index') }}" class="row g-3">
      <div class="col-lg-3 col-md-6 col-12">
        <label class="form-label fw-semibold">Search Email</label>
        <input type="text" name="search" class="form-control" placeholder="Search by email..." value="{{ request('search') }}" style="height: 38px;">
      </div>
      <div class="col-lg-2 col-md-6 col-12">
        <label class="form-label fw-semibold">Verified Status</label>
        <select name="verified" class="form-select select2" data-allow-clear="true">
          <option value="">All Statuses</option>
          <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
          <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Unverified</option>
        </select>
      </div>
      <div class="col-lg-2 col-md-6 col-12">
        <label class="form-label fw-semibold">Active Status</label>
        <select name="active" class="form-select select2" data-allow-clear="true">
          <option value="">All</option>
          <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
          <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <div class="col-lg-2 col-md-6 col-12">
        <label class="form-label fw-semibold">Source</label>
        <select name="source" class="form-select select2" data-allow-clear="true">
          <option value="">All Sources</option>
          <option value="explorer_club" {{ request('source') === 'explorer_club' ? 'selected' : '' }}>Explorer Club</option>
          <option value="footer" {{ request('source') === 'footer' ? 'selected' : '' }}>Footer</option>
        </select>
      </div>
      <div class="col-lg-3 col-md-12 col-12">
        <label class="form-label d-none d-lg-block">&nbsp;</label>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-warning text-white flex-grow-1" style="height: 38px;">Filter</button>
          <a href="{{ route('subscribers.index') }}" class="btn btn-label-secondary d-flex align-items-center justify-content-center" style="height: 38px; min-width: 80px;">Reset</a>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Subscribers List Card -->
<div class="card border-0 shadow-sm">
  <div class="card-body pt-4">
    <div class="table-responsive pt-0">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>SR NO</th>
            <th>Email</th>
            <th>Source</th>
            <th>Verified</th>
            <th>Subscription Date</th>
            <th>Verified Date</th>
            <th width="100" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($subscribers as $index => $sub)
          <tr>
            <td>{{ ($subscribers->currentPage() - 1) * $subscribers->perPage() + $loop->iteration }}</td>
            <td>
              <strong class="text-dark">{{ $sub->email }}</strong>
            </td>
            <td>
              @if($sub->source === 'explorer_club')
                <span class="badge bg-label-primary">Explorer Club</span>
              @else
                <span class="badge bg-label-secondary">Footer</span>
              @endif
            </td>
            <td>
              @if($sub->is_verified)
                <span class="badge bg-label-success"><i class="fa-solid fa-circle-check me-1"></i> Verified</span>
              @else
                <span class="badge bg-label-warning"><i class="fa-solid fa-clock me-1"></i> Pending</span>
              @endif
            </td>
            <td class="small text-muted">{{ $sub->created_at->format('M d, Y H:i A') }}</td>
            <td class="small text-muted">
              {{ $sub->verified_at ? $sub->verified_at->format('M d, Y H:i A') : 'N/A' }}
            </td>
            <td class="text-center">
              <button type="button" class="btn btn-sm btn-danger delete-subscriber-btn" data-id="{{ $sub->id }}"><i class="fa fa-trash"></i></button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-center py-4 text-muted">No subscribers found matching the filters.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
      
      <div class="d-flex justify-content-between align-items-center mt-4 px-3 mb-3 flex-wrap gap-2">
        <div class="text-muted" style="font-size: 0.85rem;">
          Showing {{ $subscribers->firstItem() ?? 0 }} to {{ $subscribers->lastItem() ?? 0 }} out of {{ $subscribers->total() }} records
        </div>
        <div>
          {{ $subscribers->appends(request()->query())->links() }}
        </div>
      </div>
    </div>
  </form>
</div>

<!-- Hidden delete form -->
<form id="deleteSubscriberForm" method="POST" action="" class="d-none">
  @csrf
  @method('DELETE')
  </div>
</div>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    // Initialize Select2 on the filter dropdowns to match the Category dropdown styling
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
