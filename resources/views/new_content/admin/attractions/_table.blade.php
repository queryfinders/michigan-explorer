  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <x-sortable-header column="name" label="Name" />
          <th>Category</th>
          <x-sortable-header column="city" label="City" />
          <x-sortable-header column="status" label="Status" />
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($attractions as $attraction)
        <tr>
          <td>{{ $loop->iteration + ($attractions->currentPage() - 1) * $attractions->perPage() }}</td>
          <td><strong>{{ $attraction->name }}</strong></td>
          <td>{{ $attraction->category ? $attraction->category->name : 'N/A' }}</td>
          <td>{{ $attraction->city }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input attraction-status-switch" data-id="{{ $attraction->id }}" data-status="{{ $attraction->status }}" {{ $attraction->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('attractions.edit', $attraction->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('attractions.destroy', $attraction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
            Showing {{ $attractions->firstItem() ?? 0 }} to {{ $attractions->lastItem() ?? 0 }} out of {{ $attractions->total() }} records
        </div>
        <div>
            {{ $attractions->appends(request()->query())->links() }}
        </div>
    </div>
  </div>
