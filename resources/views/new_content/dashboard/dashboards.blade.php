@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/swiper/swiper.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/cards-advance.css')}}">
<style>
  .hover-primary:hover {
    color: #7367f0 !important;
    text-decoration: underline !important;
  }
</style>
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/swiper/swiper.js')}}"></script>
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('content')
<div class="row">
  <!-- Welcome Block -->
  <div class="col-12 mb-4">
    <div class="card border-0 shadow-sm bg-transparent">
      <div class="card-body p-0">
        <h3 class="fw-bold mb-1">Welcome to Michigan Explorer</h3>
        <p class="text-muted mb-0">Track and manage your website stats and user inquiries.</p>
      </div>
    </div>
  </div>
</div>

<!-- Widgets Rows -->
<div class="row mb-4">
  <!-- Total Contact Messages Widget -->
  <div class="col-lg-4 col-md-6 col-12 mb-4">
    <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #7367f0 !important;">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted d-block small mb-1">Total Contact</span>
          <h3 class="fw-bold mb-1 text-primary">{{ number_format($total_messages_count) }}</h3>
          <small class="text-muted">Total inquiries received</small>
        </div>
        <div class="avatar bg-light-primary p-2 rounded">
          <i class="fa-solid fa-messages fa-lg text-primary"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Latest inquiries section -->
<div class="row">
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4">
        <h5 class="mb-0 fw-bold"><i class="fa-regular fa-paper-plane me-2 text-primary"></i>Latest Contact Messages</h5>
        <a href="{{ route('contact-messages.index') }}" class="btn btn-primary btn-sm">View All Inquiries</a>
      </div>
      <div class="card-body pt-2">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>SR No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <!-- <th>Status</th> -->
                <th>Submitted Date</th>
              </tr>
            </thead>
            <tbody>
              @forelse($latest_contact_messages as $index => $msg)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $msg->full_name }}</strong></td>
                <td><a href="mailto:{{ $msg->email }}">{{ $msg->email }}</a></td>
                <td>{{ Str::limit($msg->subject, 45) }}</td>
                <!-- <td>
                  @if($msg->status === 'new')
                    <span class="badge bg-label-danger">New</span>
                  @elseif($msg->status === 'read')
                    <span class="badge bg-label-warning">Read</span>
                  @elseif($msg->status === 'replied')
                    <span class="badge bg-label-success">Replied</span>
                  @else
                    <span class="badge bg-label-secondary">Closed</span>
                  @endif
                </td> -->
                <td class="small text-muted">{{ $msg->created_at->format('M d, Y h:i A') }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">No contact messages received yet.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
