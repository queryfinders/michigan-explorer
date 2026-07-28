@extends('layouts/layoutMaster')

@section('title', 'Edit Hotel')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hotels.index') }}">Hotels</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Hotel</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Hotel</h5>
  </div>
  <div class="card-body">
    <form id="hotelEditForm" action="{{ route('hotels.update', $hotel->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      @include('new_content.admin.hotels.form')

    </form>
  </div>
</div>

@endsection
