<div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <x-sortable-header column="title" label="Title" />
          <x-sortable-header column="slug" label="Slug" />
          <x-sortable-header column="status" label="Status" />
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pages as $page)
        <tr>
          <td>{{ $loop->iteration + ($pages->currentPage() - 1) * $pages->perPage() }}</td>
          <td><strong>{{ $page->title }}</strong></td>
          <td>{{ $page->slug }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input page-status-switch" data-id="{{ $page->id }}" data-status="{{ $page->status }}" {{ $page->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('pages.destroy', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
            Showing {{ $pages->firstItem() ?? 0 }} to {{ $pages->lastItem() ?? 0 }} out of {{ $pages->total() }} records
        </div>
        <div>
            {{ $pages->links() }}
        </div>
    </div>
  </div>
