  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <x-sortable-header column="name" label="Name" />
          <th>Slug</th>
          <x-sortable-header column="status" label="Status" />
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($categories as $category)
        <tr>
          <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
          <td><strong>{{ $category->name }}</strong></td>
          <td>{{ $category->slug }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input category-status-switch" data-id="{{ $category->id }}" data-status="{{ $category->status }}" {{ $category->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('event-categories.edit', $category->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('event-categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
            Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} out of {{ $categories->total() }} records
        </div>
        <div>
            {{ $categories->appends(request()->query())->links() }}
        </div>
    </div>
  </div>
