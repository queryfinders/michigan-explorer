@extends('layouts/layoutMaster')

@section('title', 'Contact Messages')

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Contact Messages</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Contact Messages</h3>
    <p class="text-muted mb-0">Review and manage contact submissions from users.</p>
  </div>
</div>


@include('layouts.messages')

<div class="card">
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
