@extends('layouts/layoutMaster')

@section('title', 'Edit Affiliate Promotion')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('affiliate-promotions.index') }}">Affiliate Promotions</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Promotion</li>
  </ol>
</nav>

<div class="card mb-4 border-0 shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0 fw-bold">Edit Affiliate Promotion</h5>
  </div>
  <div class="card-body">
    <form id="affiliatePromotionEditForm" action="{{ route('affiliate-promotions.update', $promotion->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row g-3">
        <!-- Placement & Priority -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Placement <span class="text-danger">*</span></label>
          <select name="placement" class="form-select @error('placement') is-invalid @enderror" required>
            @php
              $placements = [
                'homepage_banner'   => 'Homepage Banner',
                'homepage_sidebar'  => 'Homepage Sidebar',
                'hotel_detail'      => 'Hotel Detail',
                'restaurant_detail' => 'Restaurant Detail',
                'attraction_detail' => 'Attraction Detail',
                'blog_detail'       => 'Blog Detail',
                'footer_banner'     => 'Footer Banner',
              ];
            @endphp
            @foreach($placements as $value => $label)
              <option value="{{ $value }}" {{ old('placement', $promotion->placement) == $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
          @error('placement')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
          <input type="number" name="priority" class="form-control @error('priority') is-invalid @enderror" value="{{ old('priority', $promotion->priority) }}" min="1" required>
          <small class="text-muted">1 = Highest Priority</small>
          @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Badge & CTA Button -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Badge Text <span class="text-danger">*</span></label>
          <input type="text" name="badge_text" class="form-control @error('badge_text') is-invalid @enderror" value="{{ old('badge_text', $promotion->badge_text) }}" required>
          @error('badge_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">CTA Button Text <span class="text-danger">*</span></label>
          <input type="text" name="cta_text" class="form-control @error('cta_text') is-invalid @enderror" value="{{ old('cta_text', $promotion->cta_text) }}" required>
          @error('cta_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Title -->
        <div class="col-12">
          <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $promotion->title) }}" required>
          @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Subtitle -->
        <div class="col-12">
          <label class="form-label fw-semibold">Subtitle <span class="text-danger">*</span></label>
          <textarea name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" rows="3" required>{{ old('subtitle', $promotion->subtitle) }}</textarea>
          @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Affiliate Link Selection -->
        <div class="col-12">
          <label class="form-label fw-semibold">Destination Affiliate Link</label>
          <select name="affiliate_link_id" class="form-select @error('affiliate_link_id') is-invalid @enderror">
            <option value="">Select Platform Link...</option>
            @foreach($affiliateLinks as $link)
              <option value="{{ $link->id }}" {{ old('affiliate_link_id', $promotion->affiliate_link_id) == $link->id ? 'selected' : '' }}>{{ $link->name }} ({{ $link->provider }})</option>
            @endforeach
          </select>
          @error('affiliate_link_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Desktop Banner Image -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Desktop Banner Image</label>
          @if($promotion->desktop_image)
            <div class="mb-2">
              <img src="{{ asset($promotion->desktop_image) }}" class="rounded img-fluid" style="max-height: 100px; border: 1px solid #dbdade;" alt="Current Desktop Banner">
              <small class="d-block text-muted mt-1">Current desktop banner. Upload below to replace it.</small>
            </div>
          @endif
          <input type="file" name="desktop_image" class="form-control @error('desktop_image') is-invalid @enderror">
          <small class="text-muted">Leave empty to keep current image (max 2MB).</small>
          @error('desktop_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Mobile Banner Image -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Mobile Banner Image</label>
          @if($promotion->mobile_image)
            <div class="mb-2">
              <img src="{{ asset($promotion->mobile_image) }}" class="rounded img-fluid" style="max-height: 100px; border: 1px solid #dbdade;" alt="Current Mobile Banner">
              <small class="d-block text-muted mt-1">Current mobile banner. Upload below to replace it.</small>
            </div>
          @endif
          <input type="file" name="mobile_image" class="form-control @error('mobile_image') is-invalid @enderror">
          <small class="text-muted">Optional. Falls back to desktop image if empty (max 2MB).</small>
          @error('mobile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Starts At & Ends At Scheduling -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Starts At</label>
          <input type="datetime-local" name="starts_at" class="form-control @error('starts_at') is-invalid @enderror"
                 value="{{ old('starts_at', $promotion->starts_at ? $promotion->starts_at->format('Y-m-d\TH:i') : '') }}">
          <small class="text-muted">Leave empty to activate immediately.</small>
          @error('starts_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Ends At</label>
          <input type="datetime-local" name="ends_at" class="form-control @error('ends_at') is-invalid @enderror"
                 value="{{ old('ends_at', $promotion->ends_at ? $promotion->ends_at->format('Y-m-d\TH:i') : '') }}">
          <small class="text-muted">Leave empty for no expiration.</small>
          @error('ends_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Active Status Toggle -->
        <div class="col-12 my-3">
          <div class="form-check form-switch form-check-md">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold ms-2" for="is_active">Promotion Active</label>
          </div>
        </div>

        <!-- Buttons -->
        <div class="col-12 pt-3 border-top d-flex gap-2">
          <button type="submit" class="btn btn-primary">Update Promotion</button>
          <a href="{{ route('affiliate-promotions.index') }}" class="btn btn-label-secondary">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
