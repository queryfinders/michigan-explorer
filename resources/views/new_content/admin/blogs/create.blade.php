@extends('layouts/layoutMaster')

@section('title', 'Add Blog')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Blog</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('blogs.store') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label class="form-label" for="blog_category_id">Category</label>
        <select class="form-select" id="blog_category_id" name="blog_category_id">
            <option value="">Select Category</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label" for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="slug">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="excerpt">Excerpt</label>
        <textarea class="form-control" id="excerpt" name="excerpt" rows="2"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="content">Content</label>
        <textarea class="form-control" id="content" name="content" rows="10"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="published_at">Publish Date & Time</label>
        <input type="datetime-local" class="form-control" id="published_at" name="published_at" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="status">Status</label>
        <select class="form-select" id="status" name="status" required>
          <option value="draft">Draft</option>
          <option value="published">Published</option>
          <option value="scheduled">Scheduled</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Save</button>
      <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection
