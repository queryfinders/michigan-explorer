@extends('layouts/layoutMaster')

@section('title', 'Edit Event')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Event</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Event</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label class="form-label" for="event_category_id">Category</label>
        <select class="form-select" id="event_category_id" name="event_category_id" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $event->event_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label" for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $event->name }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="slug">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" value="{{ $event->slug }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control" id="description" name="description">{{ $event->description }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="start_date">Start Date & Time</label>
        <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{ $event->start_date ? date('Y-m-d\TH:i', strtotime($event->start_date)) : '' }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="end_date">End Date & Time</label>
        <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ $event->end_date ? date('Y-m-d\TH:i', strtotime($event->end_date)) : '' }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="venue_name">Venue Name</label>
        <input type="text" class="form-control" id="venue_name" name="venue_name" value="{{ $event->venue_name }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="city">City</label>
        <select class="form-select select2" id="city" name="city">
          <option value="">Select a city</option>
          @foreach(config('michigan_cities') as $m_city)
            <option value="{{ $m_city }}" {{ old('city', $event->city) == $m_city ? 'selected' : '' }}>{{ $m_city }}</option>
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label" for="price">Price</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $event->price }}" />
      </div>
      <h6 class="mt-4 fw-bold border-bottom pb-2">SEO & Schema Settings</h6>
      <div class="mb-3">
        <label class="form-label" for="meta_title">Meta Title</label>
        <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ $event->seo->meta_title ?? '' }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="meta_description">Meta Description</label>
        <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ $event->seo->meta_description ?? '' }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="canonical_url">Canonical URL</label>
        <input type="url" class="form-control" id="canonical_url" name="canonical_url" value="{{ $event->seo->canonical_url ?? '' }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="og_title">OG Title</label>
        <input type="text" class="form-control" id="og_title" name="og_title" value="{{ $event->seo->og_title ?? '' }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="og_description">OG Description</label>
        <textarea class="form-control" id="og_description" name="og_description" rows="2">{{ $event->seo->og_description ?? '' }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="schema_markup">Schema Markup (JSON-LD)</label>
        <textarea class="form-control" id="schema_markup" name="schema_markup" rows="8" placeholder="Enter JSON-LD Schema markup here">{{ $event->seo->schema_markup ?? '' }}</textarea>
      </div>
      <!-- <div class="mb-3 border-top pt-3">
        <label class="form-label fw-semibold" for="video_file">Promo Video</label>
        <input type="file" class="form-control" id="video_file" name="video_file" accept="video/mp4,video/x-m4v,video/*" />
        <div class="form-text">Supported: MP4, MOV, WebM. Max 30MB. This video will play on the event's detail page.</div>
        @if($event->video && !str_starts_with($event->video, 'http'))
          <div class="mt-2">
            <span class="text-success small"><i class="fas fa-video me-1"></i> Current video uploaded: </span>
            <a href="{{ asset($event->video) }}" target="_blank" class="small fw-bold">{{ basename($event->video) }}</a>
          </div>
        @endif
      </div> -->
      <div class="mb-3">
        <label class="form-label fw-semibold" for="video_url">Video URL</label>
        <input type="url" class="form-control" id="video_url" name="video_url" value="{{ str_starts_with($event->video ?? '', 'http') ? $event->video : '' }}" placeholder="e.g. https://www.youtube.com/watch?v=dQw4w9WgXcQ" />
        <div class="form-text">Paste a YouTube link directly instead of uploading a video file.</div>
      </div>
      @if($event->video)
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="delete_video" name="delete_video" value="1">
        <label class="form-check-label text-danger" for="delete_video">Delete current video</label>
      </div>
      @endif
      <div class="mb-3">
        <label class="form-label" for="status">Status</label>
        <select class="form-select" id="status" name="status">
          <option value="1" {{ $event->status == 1 ? 'selected' : '' }}>Active</option>
          <option value="0" {{ $event->status == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection
