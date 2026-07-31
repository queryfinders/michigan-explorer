@extends('layouts/layoutMaster')

@section('title', 'Add Attraction Category')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('attraction-categories.index') }}">Attraction Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Attraction Category</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Attraction Category</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('attraction-categories.store') }}" method="POST">
      @csrf
      @include('new_content.admin.attraction_categories.form')
    </form>
  </div>
</div>
@endsection
