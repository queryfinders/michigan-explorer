@extends('layouts/layoutMaster')

@section('title', 'Blogs')

@section('content')
<div class="card">
  @include('layouts.messages')
  <h5 class="card-header">Blogs</h5>
  <div class="d-flex justify-content-end me-md-4 mb-3">
    <a href="{{ route('blogs.create') }}" class="btn btn-primary text-white me-3">Add Blog</a>
  </div>
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Category</th>
          <th>Status</th>
          <th>Published At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($blogs as $blog)
        <tr>
          <td>{{ $blog->id }}</td>
          <td>{{ $blog->title }}</td>
          <td>{{ $blog->category ? $blog->category->name : 'N/A' }}</td>
          <td><span class="badge bg-{{ $blog->status == 'published' ? 'success' : ($blog->status == 'draft' ? 'secondary' : 'warning') }}">{{ ucfirst($blog->status) }}</span></td>
          <td>{{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') : 'N/A' }}</td>
          <td>
            <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
        {{ $blogs->links() }}
    </div>
  </div>
</div>
@endsection
