@extends('layouts/layoutMaster')

@section('title', 'Events')

@section('content')
<div class="card">
  @include('layouts.messages')
  <h5 class="card-header">Events</h5>
  <div class="d-flex justify-content-end me-md-4 mb-3">
    <a href="{{ route('events.create') }}" class="btn btn-primary text-white me-3">Add Event</a>
  </div>
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Category</th>
          <th>Start Date</th>
          <th>City</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($events as $event)
        <tr>
          <td>{{ $event->id }}</td>
          <td>{{ $event->name }}</td>
          <td>{{ $event->category ? $event->category->name : 'N/A' }}</td>
          <td>{{ $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('M d, Y g:i A') : 'N/A' }}</td>
          <td>{{ $event->city }}</td>
          <td>{{ $event->status ? 'Active' : 'Inactive' }}</td>
          <td>
            <a href="{{ route('events.edit', $event->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
        {{ $events->links() }}
    </div>
  </div>
</div>
@endsection
