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
        @foreach($restaurants as $restaurant)
        <tr>
          <td>{{ $loop->iteration + ($restaurants->currentPage() - 1) * $restaurants->perPage() }}</td>
          <td><strong>{{ $restaurant->name }}</strong></td>
          <td>{{ $restaurant->category ? $restaurant->category->name : 'N/A' }}</td>
          <td>{{ $restaurant->city }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input restaurant-status-switch" data-id="{{ $restaurant->id }}" data-status="{{ $restaurant->status }}" {{ $restaurant->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('restaurants.edit', $restaurant->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('restaurants.destroy', $restaurant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
            Showing {{ $restaurants->firstItem() ?? 0 }} to {{ $restaurants->lastItem() ?? 0 }} out of {{ $restaurants->total() }} records
        </div>
        <div>
            {{ $restaurants->appends(request()->query())->links() }}
        </div>
    </div>
  </div>
