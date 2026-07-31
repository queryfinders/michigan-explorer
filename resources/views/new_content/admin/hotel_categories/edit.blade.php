@extends('layouts/layoutMaster')

@section('title', 'Edit Hotel Category')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hotel-categories.index') }}">Hotel Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Hotel Category</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Hotel Category</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('hotel-categories.update', $hotelCategory->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('new_content.admin.hotel_categories.form', ['hotelCategory' => $hotelCategory])
    </form>
  </div>
</div>
@endsection
