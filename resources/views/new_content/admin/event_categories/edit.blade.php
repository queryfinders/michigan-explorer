@extends('layouts/layoutMaster')

@section('title', 'Edit Event Category')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('event-categories.index') }}">Event Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Event Category</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Event Category</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('event-categories.update', $eventCategory->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('new_content.admin.event_categories.form', ['eventCategory' => $eventCategory])
    </form>
  </div>
</div>
@endsection
