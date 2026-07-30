<div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <x-sortable-header column="name" label="Name" />
          <x-sortable-header column="icon" label="Icon" />
          <x-sortable-header column="sort_order" label="Sort Order" />
          <x-sortable-header column="is_active" label="Status" />
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($features as $feature)
        <tr>
          <td>{{ $loop->iteration + ($features->currentPage() - 1) * $features->perPage() }}</td>
          <td><strong>{{ $feature->name }}</strong></td>
          <td>
            @if($feature->icon)
            <span class="badge bg-light text-dark p-2 border">
              <i class="{{ $feature->icon }} me-2 text-primary"></i> <code>{{ $feature->icon }}</code>
            </span>
            @endif
          </td>
          <td>{{ $feature->sort_order }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input feature-status-switch" data-id="{{ $feature->id }}" data-status="{{ $feature->is_active }}" {{ $feature->is_active == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('booking-features.edit', $feature->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('booking-features.destroy', $feature->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="d-flex justify-content-end mt-3 me-3">
    {{ $features->links() }}
  </div>
