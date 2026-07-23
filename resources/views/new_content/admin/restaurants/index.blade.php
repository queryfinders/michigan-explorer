@extends('layouts/layoutMaster')

@section('title', 'Restaurants')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Restaurants</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Restaurants</h3>
    <p class="text-muted mb-0">Manage all restaurants and eateries in Michigan.</p>
  </div>
  <div>
    <a href="{{ route('restaurants.create') }}" class="btn btn-primary">Add Restaurant</a>
  </div>
</div>


@include('layouts.messages')

<div class="card">
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Category</th>
          <th>City</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($restaurants as $restaurant)
        <tr>
          <td>{{ $restaurant->id }}</td>
          <td>{{ $restaurant->name }}</td>
          <td>{{ $restaurant->category ? $restaurant->category->name : 'N/A' }}</td>
          <td>{{ $restaurant->city }}</td>
          <td>{{ $restaurant->status ? 'Active' : 'Inactive' }}</td>
          <td>
            <a href="{{ route('restaurants.edit', $restaurant->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('restaurants.destroy', $restaurant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $restaurants->links() }}
    </div>
  </div>
</div>
@endsection
