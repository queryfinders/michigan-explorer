@extends('layouts/layoutMaster')

@section('title', 'Add Attraction')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attractions.index') }}">Attractions</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Attraction</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Attraction</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('attractions.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      @include('new_content.admin.attractions.form')

    </form>
  </div>
</div>

@endsection
