@extends('layouts/layoutMaster')

@section('title', 'Edit Booking Feature')

@section('content')
<div class="row">
  <div class="col-md-12">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('booking-features.index') }}">Booking Features</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Booking Feature</li>
      </ol>
    </nav>

    <div class="card mb-4">
      <h5 class="card-header">Edit Booking Feature</h5>
      <div class="card-body">
        <form action="{{ route('booking-features.update', $feature->id) }}" method="POST">
          @csrf
          @method('PUT')
          @include('new_content.admin.booking_features.form', ['bookingFeature' => $feature])
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
