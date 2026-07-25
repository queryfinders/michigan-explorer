@extends('layouts/layoutMaster')

@section('title', 'Edit Attraction Category')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attraction-categories.index') }}">Attraction Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Attraction Category</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Attraction Category</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('attraction-categories.update', $attractionCategory->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label class="form-label" for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $attractionCategory->name }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="slug">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" value="{{ $attractionCategory->slug }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control" id="description" name="description">{{ $attractionCategory->description }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="status">Status</label>
        <select class="form-select" id="status" name="status">
          <option value="1" {{ $attractionCategory->status == 1 ? 'selected' : '' }}>Active</option>
          <option value="0" {{ $attractionCategory->status == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ route('attraction-categories.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection
