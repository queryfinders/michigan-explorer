@extends('layouts/layoutMaster')

@section('title', 'Add Booking Feature')

@section('content')
<div class="row">
  <div class="col-md-12">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('booking-features.index') }}">Booking Features</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Booking Feature</li>
      </ol>
    </nav>

    <div class="card mb-4">
      <h5 class="card-header">Add Booking Feature</h5>
      <div class="card-body">
        <form action="{{ route('booking-features.store') }}" method="POST">
          @csrf
          @include('new_content.admin.booking_features.form')
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
