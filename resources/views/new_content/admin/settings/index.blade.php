@extends('layouts/layoutMaster')

@section('title', 'System Settings')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Settings</li>
  </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Settings</h3>
    <p class="text-muted mb-0">Configure system settings and variables.</p>
  </div>
</div>

@include('layouts.messages')

<div class="card mb-4">
  <div class="card-body">
    <form id="settingsForm" action="{{ route('settings.store') }}" method="POST" novalidate>
      @csrf
      <div id="ajaxSuccessAlert" class="alert alert-success d-none mb-3"></div>
      
      <h6 class="mt-3 fw-bold border-bottom pb-2">General Settings</h6>
      <div class="mb-3">
        <label class="form-label" for="site_name">Site Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('site_name') is-invalid @enderror" id="site_name" name="site_name" value="{{ old('site_name', $settings->where('key', 'site_name')->first()->value ?? 'Michigan Explorer') }}" placeholder="Enter site name" required />
        @error('site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label" for="site_description">Site Description</label>
        <textarea class="form-control @error('site_description') is-invalid @enderror" id="site_description" name="site_description" rows="2" placeholder="Enter site description">{{ old('site_description', $settings->where('key', 'site_description')->first()->value ?? '') }}</textarea>
        @error('site_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label" for="contact_email">Contact Email <span class="text-danger">*</span></label>
          <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings->where('key', 'contact_email')->first()->value ?? '') }}" placeholder="Enter contact email" required />
          @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label" for="contact_phone">Contact Phone <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings->where('key', 'contact_phone')->first()->value ?? '') }}" placeholder="Enter contact phone" required />
          @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label" for="contact_headquarters">Headquarters Address</label>
        <textarea class="form-control @error('contact_headquarters') is-invalid @enderror" id="contact_headquarters" name="contact_headquarters" rows="3" placeholder="e.g. 100 Traverse City&#10;Michigan, MI 49684&#10;United States">{{ old('contact_headquarters', $settings->where('key', 'contact_headquarters')->first()->value ?? '') }}</textarea>
        @error('contact_headquarters')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label" for="contact_map_url">Google Maps Embed Code or URL</label>
        <textarea class="form-control @error('contact_map_url') is-invalid @enderror" id="contact_map_url" name="contact_map_url" rows="3" placeholder="Paste the full <iframe> embed code from Google Maps or just the URL">{{ old('contact_map_url', $settings->where('key', 'contact_map_url')->first()->value ?? '') }}</textarea>
        @error('contact_map_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      
      <h6 class="mt-4 fw-bold border-bottom pb-2">Social Media Links</h6>
      <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
          <label class="form-label" for="social_facebook">Facebook URL</label>
          <input type="url" class="form-control @error('social_facebook') is-invalid @enderror" id="social_facebook" name="social_facebook" value="{{ old('social_facebook', $settings->where('key', 'social_facebook')->first()->value ?? '') }}" placeholder="https://facebook.com/..." />
          @error('social_facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
          <label class="form-label" for="social_twitter">X URL</label>
          <input type="url" class="form-control @error('social_twitter') is-invalid @enderror" id="social_twitter" name="social_twitter" value="{{ old('social_twitter', $settings->where('key', 'social_twitter')->first()->value ?? '') }}" placeholder="https://x.com/..." />
          @error('social_twitter')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
          <label class="form-label" for="social_instagram">Instagram URL</label>
          <input type="url" class="form-control @error('social_instagram') is-invalid @enderror" id="social_instagram" name="social_instagram" value="{{ old('social_instagram', $settings->where('key', 'social_instagram')->first()->value ?? '') }}" placeholder="https://instagram.com/..." />
          @error('social_instagram')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
          <label class="form-label" for="social_youtube">YouTube URL</label>
          <input type="url" class="form-control @error('social_youtube') is-invalid @enderror" id="social_youtube" name="social_youtube" value="{{ old('social_youtube', $settings->where('key', 'social_youtube')->first()->value ?? '') }}" placeholder="https://youtube.com/..." />
          @error('social_youtube')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
      
      <div class="mt-4">
        <button type="submit" class="btn btn-primary">Save Settings</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('page-script')
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('settingsForm');
    
    if (form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear all previous errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.ajax-error-feedback').forEach(el => el.remove());
        
        // Also hide the top alerts if they exist
        const topAlert = document.querySelector('.alert');
        if (topAlert && topAlert.id !== 'ajaxSuccessAlert') topAlert.style.display = 'none';
        
        const ajaxSuccessAlert = document.getElementById('ajaxSuccessAlert');
        if (ajaxSuccessAlert) {
            ajaxSuccessAlert.classList.add('d-none');
            ajaxSuccessAlert.innerText = '';
        }
        
        const btn = form.querySelector('button[type="submit"]');
        const originalBtnHTML = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving Settings...';
        btn.disabled = true;
        
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json().then(data => ({status: response.status, body: data})))
        .then(result => {
            btn.innerHTML = originalBtnHTML;
            btn.disabled = false;
            
            if (result.status === 422) {
                // Validation errors
                let firstInvalid = null;
                for (const [field, messages] of Object.entries(result.body.errors)) {
                    const input = document.getElementById(field);
                    if (input) {
                        input.classList.add('is-invalid');
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback d-block ajax-error-feedback';
                        errorDiv.innerText = messages[0];
                        input.parentNode.appendChild(errorDiv);
                        
                        if (!firstInvalid) firstInvalid = input;
                    }
                }
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }
            } else if (result.status === 200) {
                // Success
                if (ajaxSuccessAlert) {
                    ajaxSuccessAlert.innerText = result.body.message || 'Settings saved successfully!';
                    ajaxSuccessAlert.classList.remove('d-none');
                    ajaxSuccessAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    setTimeout(() => {
                        ajaxSuccessAlert.classList.add('d-none');
                    }, 5000);
                }
            } else {
                alert('An error occurred. Please try again.');
            }
        })
        .catch(error => {
            btn.innerHTML = originalBtnHTML;
            btn.disabled = false;
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
      });
    }
  });
</script>
@endsection
