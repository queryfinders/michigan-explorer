@extends('layouts/layoutMaster')

@section('title', 'Add Affiliate Promotion')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('affiliate-promotions.index') }}">Affiliate Promotions</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Affiliate Promotion</li>
  </ol>
</nav>

<div class="card mb-4 border-0 shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0 fw-bold">Add Affiliate Promotion</h5>
  </div>
  <div class="card-body">
    <form id="affiliatePromotionCreateForm" action="{{ route('affiliate-promotions.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row g-3">
        <!-- Placement & Priority -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Placement <span class="text-danger">*</span></label>
          <select name="placement" class="form-select @error('placement') is-invalid @enderror" required>
            <option value="homepage_banner" {{ old('placement') == 'homepage_banner' ? 'selected' : '' }}>Homepage Banner</option>
            <option value="homepage_sidebar" {{ old('placement') == 'homepage_sidebar' ? 'selected' : '' }}>Homepage Sidebar</option>
            <option value="hotel_detail" {{ old('placement') == 'hotel_detail' ? 'selected' : '' }}>Hotel Detail</option>
            <option value="restaurant_detail" {{ old('placement') == 'restaurant_detail' ? 'selected' : '' }}>Restaurant Detail</option>
            <option value="attraction_detail" {{ old('placement') == 'attraction_detail' ? 'selected' : '' }}>Attraction Detail</option>
            <option value="blog_detail" {{ old('placement') == 'blog_detail' ? 'selected' : '' }}>Blog Detail</option>
            <option value="footer_banner" {{ old('placement') == 'footer_banner' ? 'selected' : '' }}>Footer Banner</option>
          </select>
          @error('placement')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
          <input type="number" name="priority" class="form-control @error('priority') is-invalid @enderror" value="{{ old('priority', 1) }}" min="1" required>
          <small class="text-muted">1 = Highest Priority (rendered first if multiple promos are scheduled)</small>
          @error('priority')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Badge & CTA Button -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Badge Text <span class="text-danger">*</span></label>
          <input type="text" name="badge_text" class="form-control @error('badge_text') is-invalid @enderror" value="{{ old('badge_text', 'Special Promotion') }}" required placeholder="e.g. Special Offer, Limited Time">
          @error('badge_text')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">CTA Button Text <span class="text-danger">*</span></label>
          <input type="text" name="cta_text" class="form-control @error('cta_text') is-invalid @enderror" value="{{ old('cta_text', 'Claim Offer') }}" required placeholder="e.g. Claim Offer, Book Now">
          @error('cta_text')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Title -->
        <div class="col-12">
          <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. Save 20% on Romantic Lakefront Escapes">
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Subtitle -->
        <div class="col-12">
          <label class="form-label fw-semibold">Subtitle <span class="text-danger">*</span></label>
          <textarea name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" rows="3" required placeholder="Add promotion campaign summary or description..."></textarea>
          @error('subtitle')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Affiliate Link Selection -->
        <div class="col-12">
          <label class="form-label fw-semibold">Destination Affiliate Link</label>
          <select name="affiliate_link_id" class="form-select @error('affiliate_link_id') is-invalid @enderror">
            <option value="">Select Platform Link (for tracking redirects)...</option>
            @foreach($affiliateLinks as $link)
              <option value="{{ $link->id }}" {{ old('affiliate_link_id') == $link->id ? 'selected' : '' }}>{{ $link->name }} ({{ $link->provider }})</option>
            @endforeach
          </select>
          <small class="text-muted">Links the promotion CTA button to a registered provider redirect for analytics.</small>
          @error('affiliate_link_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Desktop Banner Image -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Desktop Banner Image <span class="text-danger">*</span></label>
          <input type="file" name="desktop_image" class="form-control @error('desktop_image') is-invalid @enderror" required>
          <small class="text-muted">Recommended aspect ratio: 16:9 or custom wide banner dimensions (max 2MB).</small>
          @error('desktop_image')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Mobile Banner Image -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Mobile Banner Image</label>
          <input type="file" name="mobile_image" class="form-control @error('mobile_image') is-invalid @enderror">
          <small class="text-muted">Optional: portrait layout image optimized for phone screens (max 2MB). Falls back to desktop image if empty.</small>
          @error('mobile_image')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Starts At & Ends At Scheduling -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Starts At (Schedule Activation)</label>
          <input type="datetime-local" name="starts_at" class="form-control @error('starts_at') is-invalid @enderror" value="{{ old('starts_at') }}">
          <small class="text-muted">Leave empty to activate instantly.</small>
          @error('starts_at')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Ends At (Schedule Expiration)</label>
          <input type="datetime-local" name="ends_at" class="form-control @error('ends_at') is-invalid @enderror" value="{{ old('ends_at') }}">
          <small class="text-muted">Leave empty to keep promotion active indefinitely.</small>
          @error('ends_at')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Status Toggle switch -->
        <div class="col-12 my-3">
          <div class="form-check form-switch form-check-md">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold ms-2" for="is_active">Promotion Active Status</label>
          </div>
        </div>

        <!-- Submit & Cancel Buttons -->
        <div class="col-12 pt-3 border-top d-flex gap-2">
          <button type="submit" class="btn btn-primary">Create Promotion</button>
          <a href="{{ route('affiliate-promotions.index') }}" class="btn btn-label-secondary">Cancel</a>
        </div>

      </div>
    </form>
  </div>
</div>
@endsection
