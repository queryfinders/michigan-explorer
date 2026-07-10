@extends('layouts/layoutMaster')

@section('title', 'Restaurants')

@section('content')
<div class="card">
  @include('layouts.messages')
  <h5 class="card-header">Restaurants</h5>
  <div class="d-flex justify-content-end me-md-4 mb-3">
    <a href="{{ route('restaurants.create') }}" class="btn btn-primary text-white me-3">Add Restaurant</a>
  </div>
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
