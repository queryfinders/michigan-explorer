  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <x-sortable-header column="title" label="Title" />
          <th>Category</th>
          <x-sortable-header column="status" label="Status" />
          <x-sortable-header column="published_at" label="Published At" />
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($blogs as $blog)
        <tr>
          <td>{{ $loop->iteration + ($blogs->currentPage() - 1) * $blogs->perPage() }}</td>
          <td><strong>{{ $blog->title }}</strong></td>
          <td>{{ $blog->category ? $blog->category->name : 'N/A' }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input blog-status-switch" data-id="{{ $blog->id }}" data-status="{{ $blog->status }}" {{ $blog->status == 'published' ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td id="pub-date-{{ $blog->id }}">{{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') : 'N/A' }}</td>
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
    <div class="d-flex justify-content-between align-items-center mt-4 px-3 mb-3">
        <div class="text-muted" style="font-size: 0.85rem;">
            Showing {{ $blogs->firstItem() ?? 0 }} to {{ $blogs->lastItem() ?? 0 }} out of {{ $blogs->total() }} records
        </div>
        <div>
            {{ $blogs->appends(request()->query())->links() }}
        </div>
    </div>
  </div>
