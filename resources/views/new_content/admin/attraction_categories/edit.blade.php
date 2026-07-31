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
      @include('new_content.admin.attraction_categories.form', ['attractionCategory' => $attractionCategory])
    </form>
  </div>
</div>
@endsection
