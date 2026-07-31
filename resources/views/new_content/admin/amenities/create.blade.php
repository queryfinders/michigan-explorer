@extends('layouts/layoutMaster')

@section('title', 'Add Amenity')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('amenities.index') }}">Amenities</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Amenity</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Amenity</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('amenities.store') }}" method="POST">
      @csrf
      @include('new_content.admin.amenities.form')
    </form>
  </div>
</div>
@endsection
