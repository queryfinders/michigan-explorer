@extends('layouts/layoutMaster')

@section('title', 'View Contact Message')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('contact-messages.index') }}">Contact Messages</a></li>
    <li class="breadcrumb-item active" aria-current="page">View Message</li>
  </ol>
</nav>

<div class="row">
  <!-- Left Side: Message Details -->
  <div class="col-lg-8 col-md-12 mb-4">
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-transparent border-0 pt-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="fa-regular fa-envelope me-2 text-primary"></i>Inquiry Details</h5>
        <a href="{{ route('contact-messages.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
      </div>
      <div class="card-body">
        <h5 class="fw-bold text-dark mb-2">{{ $message->subject }}</h5>
        <div class="d-flex flex-wrap gap-3 text-muted small mb-4">
          <div><i class="fa-regular fa-user me-1"></i> From: {{ $message->full_name }}</div>
          <div><i class="fa-regular fa-envelope me-1"></i> Email: <a href="mailto:{{ $message->email }}">{{ $message->email }}</a></div>
          <div><i class="fa-solid fa-phone me-1"></i> Phone: {{ $message->phone ?: 'N/A' }}</div>
        </div>

        <h6 class="fw-bold text-dark mb-2">Message Content</h6>
        <div class="p-4 bg-light rounded-3 text-dark mb-0 lh-lg" style="white-space: pre-wrap;">{{ $message->message }}</div>
      </div>
    </div>
  </div>

  <!-- Right Side: Status and Metadata -->
  <div class="col-lg-4 col-md-12">
    <!-- Status Control -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-transparent border-0 pt-4">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-circle-nodes text-primary me-2"></i>Status Management</h6>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('contact-messages.updateStatus', $message->id) }}">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-semibold">Current Status</label>
            <select name="status" class="form-select select2 mb-3">
              <option value="new" {{ $message->status === 'new' ? 'selected' : '' }}>New</option>
              <option value="read" {{ $message->status === 'read' ? 'selected' : '' }}>Read</option>
              <option value="replied" {{ $message->status === 'replied' ? 'selected' : '' }}>Replied</option>
              <option value="closed" {{ $message->status === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary w-100">Update Status</button>
        </form>
      </div>
    </div>

    <!-- Metadata Details -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-transparent border-0 pt-4">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-circle-info text-primary me-2"></i>Metadata</h6>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted small">Submitted Date</span>
          <span class="small fw-semibold text-dark">{{ $message->created_at->format('M d, Y h:i A') }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted small">Replied Date</span>
          <span class="small fw-semibold text-dark">{{ $message->replied_at ? $message->replied_at->format('M d, Y h:i A') : 'N/A' }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted small">IP Address</span>
          <span class="small font-monospace text-dark">{{ $message->ip_address }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-0">
          <span class="text-muted small">Browser</span>
          <span class="small fw-semibold text-dark">{{ $browser }}</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    if ($.fn.select2) {
        $('.select2').select2({
            minimumResultsForSearch: Infinity,
            width: '100%'
        });
    }
});
</script>
@endsection
