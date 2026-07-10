@extends('layouts/layoutMaster')

@section('title', 'Attractions')

@section('content')
<div class="card">
  @include('layouts.messages')
  <h5 class="card-header">Attractions</h5>
  <div class="d-flex justify-content-end me-md-4 mb-3">
    <a href="{{ route('attractions.create') }}" class="btn btn-primary text-white me-3">Add Attraction</a>
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
        @foreach($attractions as $attraction)
        <tr>
          <td>{{ $attraction->id }}</td>
          <td>{{ $attraction->name }}</td>
          <td>{{ $attraction->category ? $attraction->category->name : 'N/A' }}</td>
          <td>{{ $attraction->city }}</td>
          <td>{{ $attraction->status ? 'Active' : 'Inactive' }}</td>
          <td>
            <a href="{{ route('attractions.edit', $attraction->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('attractions.destroy', $attraction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
        {{ $attractions->links() }}
    </div>
  </div>
</div>
@endsection
