@extends('layouts/layoutMaster')

@section('title', 'Edit Hotel Policy')

@section('content')
<div class="row">
  <div class="col-md-12">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('hotel-policies.index') }}">Hotel Policies</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Hotel Policy</li>
      </ol>
    </nav>

    <div class="card mb-4">
      <h5 class="card-header">Edit Hotel Policy</h5>
      <div class="card-body">
        <form action="{{ route('hotel-policies.update', $policy->id) }}" method="POST">
          @csrf
          @method('PUT')
          
          @include('new_content.admin.hotel_policies.form', ['policy' => $policy])
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
