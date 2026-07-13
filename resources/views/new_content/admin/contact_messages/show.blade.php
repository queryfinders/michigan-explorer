@extends('layouts/layoutMaster')

@section('title', 'View Contact Message')

@section('content')
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Message from {{ $message->name }}</h5>
    <a href="{{ route('contact-messages.index') }}" class="btn btn-sm btn-secondary">Back</a>
  </div>
  <div class="card-body">
    <table class="table table-bordered mb-4">
      <tbody>
        <tr>
          <th class="auto-style-3">Date</th>
          <td>{{ $message->created_at->format('F d, Y h:i A') }}</td>
        </tr>
        <tr>
          <th>Name</th>
          <td>{{ $message->name }}</td>
        </tr>
        <tr>
          <th>Email</th>
          <td><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></td>
        </tr>
        <tr>
          <th>Phone</th>
          <td>{{ $message->phone ?? 'N/A' }}</td>
        </tr>
        <tr>
          <th>Subject</th>
          <td>{{ $message->subject ?? 'N/A' }}</td>
        </tr>
      </tbody>
    </table>
    
    <h6 class="fw-bold">Message Content</h6>
    <div class="p-3 bg-light border rounded">
      {!! nl2br(e($message->message)) !!}
    </div>
  </div>
</div>
@endsection
