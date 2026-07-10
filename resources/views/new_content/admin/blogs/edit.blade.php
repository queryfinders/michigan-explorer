@extends('layouts/layoutMaster')

@section('title', 'Edit Blog')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Blog</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('blogs.update', $blog->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label class="form-label" for="blog_category_id">Category</label>
        <select class="form-select" id="blog_category_id" name="blog_category_id">
            <option value="">Select Category</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $blog->blog_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label" for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ $blog->title }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="slug">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" value="{{ $blog->slug }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="excerpt">Excerpt</label>
        <textarea class="form-control" id="excerpt" name="excerpt" rows="2">{{ $blog->excerpt }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="content">Content</label>
        <textarea class="form-control" id="content" name="content" rows="10">{{ $blog->content }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="published_at">Publish Date & Time</label>
        <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="{{ $blog->published_at ? date('Y-m-d\TH:i', strtotime($blog->published_at)) : '' }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="status">Status</label>
        <select class="form-select" id="status" name="status" required>
          <option value="draft" {{ $blog->status == 'draft' ? 'selected' : '' }}>Draft</option>
          <option value="published" {{ $blog->status == 'published' ? 'selected' : '' }}>Published</option>
          <option value="scheduled" {{ $blog->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection
