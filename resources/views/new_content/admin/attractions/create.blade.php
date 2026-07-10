@extends('layouts/layoutMaster')

@section('title', 'Add Attraction')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Attraction</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('attractions.store') }}" method="POST">
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
        <input type="text" class="form-control" id="city" name="city" />
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
