@extends('layouts/layoutMaster')

@section('title', 'Hotel Categories')

@section('content')
<div class="card">
  @include('layouts.messages')
  <h5 class="card-header">Hotel Categories</h5>
  <div class="d-flex justify-content-end me-md-4 mb-3">
    <a href="{{ route('hotel-categories.create') }}" class="btn btn-primary text-white me-3">Add Category</a>
  </div>
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Slug</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($categories as $category)
        <tr>
          <td>{{ $category->id }}</td>
          <td>{{ $category->name }}</td>
          <td>{{ $category->slug }}</td>
          <td>{{ $category->status ? 'Active' : 'Inactive' }}</td>
          <td>
            <a href="{{ route('hotel-categories.edit', $category->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('hotel-categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
        {{ $categories->links() }}
    </div>
  </div>
</div>
@endsection
