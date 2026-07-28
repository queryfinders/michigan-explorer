@extends('layouts/layoutMaster')

@section('title', 'Edit Affiliate Link')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('affiliate-links.index') }}">Affiliate Links</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Affiliate Link</li>
  </ol>
</nav>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Edit Affiliate Link</h5>
  </div>
  <div class="card-body">
    <form id="affiliateLinkEditForm" action="{{ route('affiliate-links.update', $affiliateLink->id) }}" method="POST">
      @csrf
      @method('PUT')

      @include('new_content.admin.affiliate_links.form')

    </form>
  </div>
</div>
@endsection
