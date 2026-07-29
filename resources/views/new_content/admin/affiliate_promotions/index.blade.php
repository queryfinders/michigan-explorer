@extends('layouts/layoutMaster')

@section('title', 'Affiliate Promotions')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">Settings</a></li>
    <li class="breadcrumb-item active" aria-current="page">Affiliate Promotions</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <div>
    <h3 class="mb-1 fw-bold">Affiliate Promotions</h3>
    <p class="text-muted mb-0">Create and manage dynamic promotional banners across your website.</p>
  </div>
  <div class="d-flex align-items-center gap-2 flex-wrap">
    <form action="{{ route('affiliate-promotions.index') }}" method="GET" class="d-flex gap-2">
        <select name="placement" class="form-select" style="width: 180px;" onchange="this.form.submit()">
            <option value="">All Placements</option>
            <option value="homepage_banner" {{ request('placement') === 'homepage_banner' ? 'selected' : '' }}>Homepage Banner</option>
            <option value="homepage_sidebar" {{ request('placement') === 'homepage_sidebar' ? 'selected' : '' }}>Homepage Sidebar</option>
            <option value="hotel_detail" {{ request('placement') === 'hotel_detail' ? 'selected' : '' }}>Hotel Detail</option>
            <option value="restaurant_detail" {{ request('placement') === 'restaurant_detail' ? 'selected' : '' }}>Restaurant Detail</option>
            <option value="attraction_detail" {{ request('placement') === 'attraction_detail' ? 'selected' : '' }}>Attraction Detail</option>
            <option value="blog_detail" {{ request('placement') === 'blog_detail' ? 'selected' : '' }}>Blog Detail</option>
            <option value="footer_banner" {{ request('placement') === 'footer_banner' ? 'selected' : '' }}>Footer Banner</option>
        </select>
        <input type="text" name="search" class="form-control" placeholder="Search..." style="width: 180px;" value="{{ request('search') }}" />
        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
    </form>
    <a href="{{ route('affiliate-promotions.create') }}" class="btn btn-primary">Add Promotion</a>
  </div>
</div>

@include('layouts.messages')

<div class="card border-0 shadow-sm">
  <div class="table-responsive pt-0">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>SR No</th>
          <th>Image</th>
          <th>Badge & Title</th>
          <th>Placement</th>
          <th>Affiliate Link</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Schedule</th>
          <th>Analytics</th>
          <th width="100" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($promotions as $promo)
        <tr>
          <td>{{ $loop->iteration + ($promotions->currentPage() - 1) * $promotions->perPage() }}</td>
          <td>
            @if($promo->desktop_image)
              <img src="{{ asset($promo->desktop_image) }}" alt="Thumbnail" class="rounded" style="width: 65px; height: 40px; object-fit: cover; border: 1px solid #dbdade;">
            @else
              <span class="text-muted">No Image</span>
            @endif
          </td>
          <td>
            <span class="badge bg-light-primary text-primary mb-1">{{ $promo->badge_text }}</span>
            <div class="fw-bold text-dark text-truncate" style="max-width: 200px;" title="{{ $promo->title }}">{{ $promo->title }}</div>
          </td>
          <td>
            <span class="badge bg-label-secondary text-capitalize">{{ str_replace('_', ' ', $promo->placement) }}</span>
          </td>
          <td>
            @if($promo->affiliateLink)
              <span class="fw-semibold text-dark">{{ $promo->affiliateLink->name }}</span>
              <small class="text-muted d-block">{{ $promo->affiliateLink->provider }}</small>
            @else
              <span class="text-muted">None</span>
            @endif
          </td>
          <td>
             <span class="badge bg-label-info">Priority {{ $promo->priority }}</span>
          </td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input status-toggle-switch" data-id="{{ $promo->id }}" data-status="{{ $promo->is_active ? 1 : 0 }}" {{ $promo->is_active ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            @if($promo->starts_at || $promo->ends_at)
              <small class="d-block text-muted"><strong>Start:</strong> {{ $promo->starts_at ? $promo->starts_at->format('M d, Y h:i A') : 'Immediately' }}</small>
              <small class="d-block text-muted"><strong>End:</strong> {{ $promo->ends_at ? $promo->ends_at->format('M d, Y h:i A') : 'No Expiry' }}</small>
            @else
              <span class="badge bg-label-success">Always Active</span>
            @endif
          </td>
          <td>
             <span class="badge bg-label-success d-block mb-1">{{ number_format($promo->clicks_count) }} Clicks</span>
             @if($promo->last_click_at)
                <small class="text-muted d-block" style="font-size: 0.75rem;"><strong>Last:</strong> {{ $promo->last_click_at->format('M d, Y') }}</small>
             @endif
          </td>
          <td class="text-center">
            <a href="{{ route('affiliate-promotions.edit', $promo->id) }}" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
            <form action="{{ route('affiliate-promotions.destroy', $promo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this promotion?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach

        @if($promotions->isEmpty())
        <tr>
          <td colspan="10" class="text-center py-4 text-muted">No promotions found.</td>
        </tr>
        @endif
      </tbody>
    </table>
    
    <div class="d-flex justify-content-between align-items-center mt-4 px-3 mb-3">
        <div class="text-muted" style="font-size: 0.85rem;">
            Showing {{ $promotions->firstItem() ?? 0 }} to {{ $promotions->lastItem() ?? 0 }} out of {{ $promotions->total() }} records
        </div>
        <div>
            {{ $promotions->appends(request()->input())->links() }}
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
              url: '{{ url("admin/affiliate-promotions/status") }}/' + id + '/' + status,
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
