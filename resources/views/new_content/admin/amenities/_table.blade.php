<div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <x-sortable-header column="name" label="Name" />
          <x-sortable-header column="slug" label="Slug" />
          <x-sortable-header column="icon" label="Icon" />
          <x-sortable-header column="status" label="Status" />
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($amenities as $amenity)
        <tr>
          <td>{{ $loop->iteration + ($amenities->currentPage() - 1) * $amenities->perPage() }}</td>
          <td><strong>{{ $amenity->name }}</strong></td>
          <td>{{ $amenity->slug }}</td>
          <td>
            <span class="badge bg-light text-dark p-2 border">
              <i class="fas {{ $amenity->icon }} me-2 text-primary"></i> <code>{{ $amenity->icon }}</code>
            </span>
          </td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input amenity-status-switch" data-id="{{ $amenity->id }}" data-status="{{ $amenity->status == 1 ? 1 : 0 }}" {{ $amenity->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('amenities.edit', $amenity->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('amenities.destroy', $amenity->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    
    <div class="d-flex justify-content-end mt-3 me-3">
        {{ $amenities->links() }}
    </div>
  </div>
