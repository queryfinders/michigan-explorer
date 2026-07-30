<div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <x-sortable-header column="name" label="Name" />
          <x-sortable-header column="slug" label="Slug" />
          <x-sortable-header column="sort_order" label="Sort Order" />
          <x-sortable-header column="status" label="Status" />
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($cuisines as $cuisine)
        <tr>
          <td>{{ $loop->iteration + ($cuisines->currentPage() - 1) * $cuisines->perPage() }}</td>
          <td><strong>{{ $cuisine->name }}</strong></td>
          <td>{{ $cuisine->slug }}</td>
          <td>{{ $cuisine->sort_order }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input cuisine-status-switch" data-id="{{ $cuisine->id }}" data-status="{{ $cuisine->status }}" {{ $cuisine->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('cuisines.edit', $cuisine->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('cuisines.destroy', $cuisine->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
            Showing {{ $cuisines->firstItem() ?? 0 }} to {{ $cuisines->lastItem() ?? 0 }} out of {{ $cuisines->total() }} records
        </div>
        <div>
            {{ $cuisines->links() }}
        </div>
    </div>
  </div>
