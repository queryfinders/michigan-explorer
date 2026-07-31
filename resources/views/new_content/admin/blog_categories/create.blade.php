@extends('layouts/layoutMaster')

@section('title', 'Add Blog Category')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('blog-categories.index') }}">Blog Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Blog Category</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Blog Category</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('blog-categories.store') }}" method="POST">
      @csrf
      @include('new_content.admin.blog_categories.form')
    </form>
  </div>
</div>
@endsection
