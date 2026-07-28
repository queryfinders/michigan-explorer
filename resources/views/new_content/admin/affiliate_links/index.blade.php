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
    <form action="{{ route('affiliate-links.index') }}" method="GET" class="d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Search..." style="width: 220px;" value="{{ request('search') }}" />
        <button type="submit" class="btn btn-warning text-white"><i class="fa fa-search"></i></button>
    </form>
    <a href="{{ route('affiliate-links.create') }}" class="btn btn-warning text-white">Add Link</a>
  </div>
</div>

@include('layouts.messages')

<div class="card">
  <div class="table-responsive pt-0">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>SR NO</th>
          <th>Name</th>
          <th>Provider</th>
          <th>Destination Link</th>
          <th>Total Clicks</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($affiliateLinks as $link)
        <tr>
          <td>{{ $loop->iteration + ($affiliateLinks->currentPage() - 1) * $affiliateLinks->perPage() }}</td>
          <td><strong>{{ $link->name }}</strong></td>
          <td>{{ $link->provider ?? 'N/A' }}</td>
          <td><a href="{{ $link->link }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 250px;" title="{{ $link->link }}">{{ $link->link }}</a></td>
          <td><span class="badge bg-label-info">{{ number_format($link->total_clicks) }} Clicks</span></td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input status-toggle-switch" data-id="{{ $link->id }}" data-status="{{ $link->is_active }}" {{ $link->is_active == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('affiliate-links.show', $link->id) }}" class="btn btn-sm btn-info" title="View Stats"><i class="fa fa-chart-bar"></i></a>
            <a href="{{ route('affiliate-links.edit', $link->id) }}" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
            <form action="{{ route('affiliate-links.destroy', $link->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this affiliate link?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach

        @if($affiliateLinks->isEmpty())
        <tr>
          <td colspan="7" class="text-center">No affiliate links found.</td>
        </tr>
        @endif
      </tbody>
    </table>
    
    <div class="d-flex justify-content-between align-items-center mt-4 px-3 mb-3">
        <div class="text-muted" style="font-size: 0.85rem;">
            Showing {{ $affiliateLinks->firstItem() ?? 0 }} to {{ $affiliateLinks->lastItem() ?? 0 }} out of {{ $affiliateLinks->total() }} records
        </div>
        <div>
            {{ $affiliateLinks->appends(request()->input())->links() }}
        </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
      $(document).on('change', '.status-toggle-switch', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var status = $(this).data('status');
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
