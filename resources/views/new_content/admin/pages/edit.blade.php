@extends('layouts/layoutMaster')

@section('title', 'Edit Page')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Page</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('pages.update', $page->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label class="form-label" for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ $page->title }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="slug">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" value="{{ $page->slug }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="content">Content</label>
        <textarea class="form-control" id="content" name="content" rows="15">{{ $page->content }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="meta_title">Meta Title</label>
        <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ $page->meta_title }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="meta_description">Meta Description</label>
        <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ $page->meta_description }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="status">Status</label>
        <select class="form-select" id="status" name="status">
          <option value="1" {{ $page->status == 1 ? 'selected' : '' }}>Active</option>
          <option value="0" {{ $page->status == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ route('pages.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection
