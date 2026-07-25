@extends('layouts/layoutMaster')

@section('title', 'Add Restaurant Category')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('restaurant-categories.index') }}">Restaurant Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Restaurant Category</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Restaurant Category</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('restaurant-categories.store') }}" method="POST">
      @csrf
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
        <label class="form-label" for="status">Status</label>
        <select class="form-select" id="status" name="status">
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Save</button>
      <a href="{{ route('restaurant-categories.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection
