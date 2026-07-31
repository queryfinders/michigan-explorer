@extends('layouts/layoutMaster')

@section('title', 'Edit Restaurant Category')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('restaurant-categories.index') }}">Restaurant Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Restaurant Category</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Restaurant Category</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('restaurant-categories.update', $restaurantCategory->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('new_content.admin.restaurant_categories.form', ['restaurantCategory' => $restaurantCategory])
    </form>
  </div>
</div>
@endsection
