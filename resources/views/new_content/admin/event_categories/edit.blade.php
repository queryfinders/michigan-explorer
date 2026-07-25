@extends('layouts/layoutMaster')

@section('title', 'Edit Event Category')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('event-categories.index') }}">Event Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Event Category</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Event Category</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('event-categories.update', $eventCategory->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label class="form-label" for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $eventCategory->name }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="slug">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" value="{{ $eventCategory->slug }}" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control" id="description" name="description">{{ $eventCategory->description }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label" for="status">Status</label>
        <select class="form-select" id="status" name="status">
          <option value="1" {{ $eventCategory->status == 1 ? 'selected' : '' }}>Active</option>
          <option value="0" {{ $eventCategory->status == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ route('event-categories.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection
