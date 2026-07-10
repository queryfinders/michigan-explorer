@extends('layouts/layoutMaster')

@section('title', 'System Settings')

@section('content')
<div class="card mb-4">
  @include('layouts.messages')
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">System Settings</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('settings.store') }}" method="POST">
      @csrf
      
      <h6 class="mt-3 fw-bold border-bottom pb-2">General Settings</h6>
      <div class="mb-3">
        <label class="form-label" for="site_name">Site Name</label>
        <input type="text" class="form-control" id="site_name" name="site_name" value="{{ $settings->where('key', 'site_name')->first()->value ?? 'Michigan Explorer' }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="site_description">Site Description</label>
        <textarea class="form-control" id="site_description" name="site_description" rows="2">{{ $settings->where('key', 'site_description')->first()->value ?? '' }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="contact_email">Contact Email</label>
        <input type="email" class="form-control" id="contact_email" name="contact_email" value="{{ $settings->where('key', 'contact_email')->first()->value ?? '' }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="contact_phone">Contact Phone</label>
        <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="{{ $settings->where('key', 'contact_phone')->first()->value ?? '' }}" />
      </div>
      
      <h6 class="mt-4 fw-bold border-bottom pb-2">Social Media Links</h6>
      <div class="mb-3">
        <label class="form-label" for="social_facebook">Facebook URL</label>
        <input type="url" class="form-control" id="social_facebook" name="social_facebook" value="{{ $settings->where('key', 'social_facebook')->first()->value ?? '' }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="social_twitter">Twitter URL</label>
        <input type="url" class="form-control" id="social_twitter" name="social_twitter" value="{{ $settings->where('key', 'social_twitter')->first()->value ?? '' }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="social_instagram">Instagram URL</label>
        <input type="url" class="form-control" id="social_instagram" name="social_instagram" value="{{ $settings->where('key', 'social_instagram')->first()->value ?? '' }}" />
      </div>
      
      <div class="mt-4">
        <button type="submit" class="btn btn-primary">Save Settings</button>
      </div>
    </form>
  </div>
</div>
@endsection
