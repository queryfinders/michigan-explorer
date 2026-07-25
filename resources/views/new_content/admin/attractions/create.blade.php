@extends('layouts/layoutMaster')

@section('title', 'Add Attraction')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attractions.index') }}">Attractions</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Attraction</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Attraction</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('attractions.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="mb-3">
        <label class="form-label" for="attraction_category_id">Category</label>
        <select class="form-select" id="attraction_category_id" name="attraction_category_id" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label" for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="slug">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control" id="description" name="description"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="city">City</label>
        <select class="form-select select2" id="city" name="city">
          <option value="">Select a city</option>
          @foreach(config('michigan_cities') as $m_city)
            <option value="{{ $m_city }}" {{ old('city') == $m_city ? 'selected' : '' }}>{{ $m_city }}</option>
          @endforeach
        </select>
      </div>
      <h6 class="mt-4 fw-bold border-bottom pb-2">SEO & Schema Settings</h6>
      <div class="mb-3">
         <label class="form-label" for="meta_title">Meta Title</label>
         <input type="text" class="form-control" id="meta_title" name="meta_title" />
      </div>
      <div class="mb-3">
         <label class="form-label" for="meta_description">Meta Description</label>
         <textarea class="form-control" id="meta_description" name="meta_description" rows="2"></textarea>
      </div>
      <div class="mb-3">
         <label class="form-label" for="canonical_url">Canonical URL</label>
         <input type="url" class="form-control" id="canonical_url" name="canonical_url" />
      </div>
      <div class="mb-3">
         <label class="form-label" for="og_title">OG Title</label>
         <input type="text" class="form-control" id="og_title" name="og_title" />
      </div>
      <div class="mb-3">
         <label class="form-label" for="og_description">OG Description</label>
         <textarea class="form-control" id="og_description" name="og_description" rows="2"></textarea>
      </div>
      <div class="mb-3">
         <label class="form-label" for="schema_markup">Schema Markup (JSON-LD)</label>
         <textarea class="form-control" id="schema_markup" name="schema_markup" rows="8" placeholder="Enter JSON-LD Schema markup here"></textarea>
      </div>
      <div class="mb-3 border-top pt-3">
        <label class="form-label fw-semibold" for="video_file">Promo Video</label>
        <input type="file" class="form-control" id="video_file" name="video_file" accept="video/mp4,video/x-m4v,video/*" />
        <div class="form-text">Supported: MP4, MOV, WebM. Max 30MB. This video will play on the attraction's detail page.</div>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold" for="video_url">OR YouTube Video URL</label>
        <input type="url" class="form-control" id="video_url" name="video_url" placeholder="e.g. https://www.youtube.com/watch?v=dQw4w9WgXcQ" />
        <div class="form-text">Paste a YouTube link directly instead of uploading a video file.</div>
      </div>
      <div class="mb-3">
        <label class="form-label" for="status">Status</label>
        <select class="form-select" id="status" name="status">
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Save</button>
      <a href="{{ route('attractions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection
