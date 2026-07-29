@extends('layouts/layoutMaster')

@section('title', 'Add Affiliate Link')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('affiliate-links.index') }}">Affiliate Links</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Affiliate Link</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add Affiliate Link</h5>
  </div>
  <div class="card-body">
    <form id="affiliateLinkCreateForm" action="{{ route('affiliate-links.store') }}" method="POST">
      @csrf

      @include('new_content.admin.affiliate_links.form')

    </form>
  </div>
</div>
@endsection
