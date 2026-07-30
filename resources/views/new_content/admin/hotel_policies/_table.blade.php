<div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <x-sortable-header column="name" label="Name" />
          <x-sortable-header column="input_type" label="Input Type" />
          <x-sortable-header column="sort_order" label="Sort Order" />
          <x-sortable-header column="is_active" label="Status" />
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($policies as $policy)
        <tr>
          <td>{{ $loop->iteration + ($policies->currentPage() - 1) * $policies->perPage() }}</td>
          <td><strong>{{ $policy->name }}</strong></td>
          <td>{{ ucfirst($policy->input_type) }}</td>
          <td>{{ $policy->sort_order }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input policy-status-switch" data-id="{{ $policy->id }}" data-status="{{ $policy->is_active }}" {{ $policy->is_active == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('hotel-policies.edit', $policy->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('hotel-policies.destroy', $policy->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
    {{ $policies->links() }}
  </div>
