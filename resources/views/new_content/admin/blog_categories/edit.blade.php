@extends('layouts/layoutMaster')

@section('title', 'Edit Blog Category')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('blog-categories.index') }}">Blog Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Blog Category</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Blog Category</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('blog-categories.update', $blogCategory->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('new_content.admin.blog_categories.form', ['blogCategory' => $blogCategory])
    </form>
  </div>
</div>
@endsection
