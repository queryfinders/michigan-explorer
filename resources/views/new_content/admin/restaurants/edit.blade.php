@extends('layouts/layoutMaster')

@section('title', 'Edit Restaurant')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Restaurant</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('restaurants.update', $restaurant->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label class="form-label" for="restaurant_category_id">Category</label>
        <select class="form-select" id="restaurant_category_id" name="restaurant_category_id" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $restaurant->restaurant_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label" for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $restaurant->name }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="slug">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" value="{{ $restaurant->slug }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control" id="description" name="description">{{ $restaurant->description }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="city">City</label>
        <input type="text" class="form-control" id="city" name="city" value="{{ $restaurant->city }}" />
      </div>
      <h6 class="mt-4 fw-bold border-bottom pb-2">SEO & Schema Settings</h6>
      <div class="mb-3">
        <label class="form-label" for="meta_title">Meta Title</label>
        <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ $restaurant->seo->meta_title ?? '' }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="meta_description">Meta Description</label>
        <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ $restaurant->seo->meta_description ?? '' }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="canonical_url">Canonical URL</label>
        <input type="url" class="form-control" id="canonical_url" name="canonical_url" value="{{ $restaurant->seo->canonical_url ?? '' }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="og_title">OG Title</label>
        <input type="text" class="form-control" id="og_title" name="og_title" value="{{ $restaurant->seo->og_title ?? '' }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="og_description">OG Description</label>
        <textarea class="form-control" id="og_description" name="og_description" rows="2">{{ $restaurant->seo->og_description ?? '' }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="schema_markup">Schema Markup (JSON-LD)</label>
        <textarea class="form-control" id="schema_markup" name="schema_markup" rows="8" placeholder="Enter JSON-LD Schema markup here">{{ $restaurant->seo->schema_markup ?? '' }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="status">Status</label>
        <select class="form-select" id="status" name="status">
          <option value="1" {{ $restaurant->status == 1 ? 'selected' : '' }}>Active</option>
          <option value="0" {{ $restaurant->status == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection
