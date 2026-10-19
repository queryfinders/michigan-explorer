@extends('layouts/layoutMaster')

@section('title', 'Edit Page')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pages.index') }}">Pages</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Page</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Page</h5>
  </div>
  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h6 class="alert-heading mb-1"><i class="fas fa-exclamation-circle me-1"></i> Please fix the following errors:</h6>
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <form action="{{ route('pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      
      @include('new_content.admin.pages.form', ['page' => $page])
      <div class="mt-4 pt-3 border-top">
        <button type="submit" class="btn btn-warning">Update</button>
        <a href="{{ route('pages.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
