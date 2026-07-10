@extends('layouts/layoutMaster')

@section('title', 'Contact Messages')

@section('content')
<div class="card">
  @include('layouts.messages')
  <h5 class="card-header">Contact Messages</h5>
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Name</th>
          <th>Email</th>
          <th>Subject</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($messages as $msg)
        <tr class="{{ $msg->is_read ? '' : 'table-warning' }}">
          <td>{{ $msg->created_at->format('M d, Y H:i') }}</td>
          <td>{{ $msg->name }}</td>
          <td>{{ $msg->email }}</td>
          <td>{{ Str::limit($msg->subject, 30) }}</td>
          <td>
            @if($msg->is_read)
              <span class="badge bg-success">Read</span>
            @else
              <span class="badge bg-warning text-dark">Unread</span>
            @endif
          </td>
          <td>
            <a href="{{ route('contact-messages.show', $msg->id) }}" class="btn btn-sm btn-info text-white"><i class="fa fa-eye"></i></a>
            <form action="{{ route('contact-messages.destroy', $msg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
        {{ $messages->links() }}
    </div>
  </div>
</div>
@endsection
