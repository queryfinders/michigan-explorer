<div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <x-sortable-header column="name" label="User Name" />
          <x-sortable-header column="email_id" label="Email" />
          <x-sortable-header column="contact_no" label="Contact No" />
          <x-sortable-header column="job_title" label="Job Title" />
          <x-sortable-header column="role" label="Role" />
          <th>Profile</th>
          <x-sortable-header column="is_active" label="Status" />
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr>
          <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
          <td class="text-capitalize">{{ $user->name }}</td>
          <td>{{ $user->email_id }}</td>
          <td>
            @if($user->contact_no)
              <a class="text-secondary" target="_blank" href="https://api.whatsapp.com/send?phone=91{{ $user->contact_no }}">
                {{ $user->contact_no }}<i class="fab fa-whatsapp ms-1 text-success"></i>
              </a>
            @endif
          </td>
          <td>{{ $user->job_title }}</td>
          <td>{{ collect($user)->has('role') ? $user->role : '' }}</td>
          <td>
            @if($user->profile_url)
              <img src="{{ url($user->profile_url) }}" alt="profile" style="width:30px;height:30px;border-radius:50%;object-fit:cover;" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random';" />
            @else
              <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" style="width:30px;height:30px;border-radius:50%;object-fit:cover;" />
            @endif
          </td>
          <td>
            @php
                $canViewUserUpdate = \App\Helpers\AccessRights::accessRights('users.update');
            @endphp
            <div class="form-check form-switch p-0 m-0" style="display: flex; justify-content: center; align-items: center;">
              <input class="form-check-input active_status m-0" type="checkbox" role="switch" id="status_{{ $user->id }}" data-id="{{ $user->id }}" {{ $user->is_active == 1 ? 'checked' : '' }} {{ $canViewUserUpdate ? '' : 'disabled' }}>
            </div>
          </td>
          <td>
            @php
                $canViewUserDelete = \App\Helpers\AccessRights::accessRights('users.delete');
            @endphp
            @if($canViewUserUpdate)
              <a href="{{ route('edit-user', $user->id) }}" class="btn btn-sm btn-primary py-1 px-2 m-1">
                <i class="fa fa-edit"></i>
              </a>
            @endif
            @if($canViewUserDelete)
              <button class="btn btn-sm btn-danger py-1 px-2 m-1 delete-user" data-id="{{ $user->id }}">
                <i class="fa fa-trash"></i>
              </button>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" class="text-center">No users found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    
    <div class="d-flex justify-content-end mt-3 me-3">
        {{ $users->links() }}
    </div>
  </div>
