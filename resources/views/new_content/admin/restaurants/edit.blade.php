@extends('layouts/layoutMaster')

@section('title', 'Edit Restaurant')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('restaurants.index') }}">Restaurants</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Restaurant</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Restaurant</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('restaurants.update', $restaurant->id) }}" method="POST" enctype="multipart/form-data" id="restaurantEditForm">
      @csrf
      @method('PUT')

      @include('new_content.admin.restaurants.form')

    </form>
  </div>
</div>

@endsection
