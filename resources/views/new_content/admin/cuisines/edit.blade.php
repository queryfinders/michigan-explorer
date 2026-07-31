@extends('layouts/layoutMaster')

@section('title', 'Edit Cuisine')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('cuisines.index') }}">Cuisines</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Cuisine</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Cuisine</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('cuisines.update', $cuisine->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('new_content.admin.cuisines.form', ['cuisine' => $cuisine])
    </form>
  </div>
</div>
@endsection
