@extends('layouts/layoutMaster')

@section('title', 'Add Restaurant')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('restaurants.index') }}">Restaurants</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Restaurant</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Restaurant</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('restaurants.store') }}" method="POST" enctype="multipart/form-data" id="restaurantCreateForm">
      @csrf

      @include('new_content.admin.restaurants.form')

    </form>
  </div>
</div>

@endsection
