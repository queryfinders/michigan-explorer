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
        @foreach($features as $feature)
        <tr>
          <td>{{ $loop->iteration + ($features->currentPage() - 1) * $features->perPage() }}</td>
          <td><strong>{{ $feature->name }}</strong></td>
          <td>{{ $feature->slug }}</td>
          <td>{{ $feature->sort_order }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input feature-status-switch" data-id="{{ $feature->id }}" data-status="{{ $feature->status }}" {{ $feature->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('features.edit', $feature->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('features.destroy', $feature->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
            Showing {{ $features->firstItem() ?? 0 }} to {{ $features->lastItem() ?? 0 }} out of {{ $features->total() }} records
        </div>
        <div>
            {{ $features->links() }}
        </div>
    </div>
  </div>
