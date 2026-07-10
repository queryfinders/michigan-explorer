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
