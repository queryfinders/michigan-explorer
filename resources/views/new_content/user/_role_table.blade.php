<div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <x-sortable-header column="role" label="ROLE NAME" />
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody>
        @forelse($roles as $roleData)
        <tr>
          <td>{{ $loop->iteration + ($roles->currentPage() - 1) * $roles->perPage() }}</td>
          <td><strong>{{ $roleData->role }}</strong></td>
          <td>
            <div class="btn-group" role="group">
              <a href="{{ route('edit-role', $roleData->id) }}" class="btn btn-sm btn-primary edit-btn"><i class="fa fa-edit"></i></a>
              <button type="button" data-id="{{ $roleData->id }}" class="btn btn-sm btn-danger delete-btn deleteRole"><i class="fa fa-trash"></i></button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="3" class="text-center">No roles found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    
    <div class="d-flex justify-content-end mt-3 me-3">
        {{ $roles->links() }}
    </div>
  </div>
