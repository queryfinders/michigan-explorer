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
        @foreach($hotels as $hotel)
        <tr>
          <td>{{ $loop->iteration + ($hotels->currentPage() - 1) * $hotels->perPage() }}</td>
          <td><strong>{{ $hotel->name }}</strong></td>
          <td>{{ $hotel->category ? $hotel->category->name : 'N/A' }}</td>
          <td>{{ $hotel->city }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input hotel-status-switch" data-id="{{ $hotel->id }}" data-status="{{ $hotel->status }}" {{ $hotel->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('hotels.edit', $hotel->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('hotels.destroy', $hotel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
            Showing {{ $hotels->firstItem() ?? 0 }} to {{ $hotels->lastItem() ?? 0 }} out of {{ $hotels->total() }} records
        </div>
        <div>
            {{ $hotels->appends(request()->query())->links() }}
        </div>
    </div>
  </div>
