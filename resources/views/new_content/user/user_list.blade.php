@extends('layouts/layoutMaster')
@php
use App\Helpers\AccessRights;
@endphp

@section('title', 'User List')

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/cards-advance.css')}}">
@endsection

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Users</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">User List</h3>
    <p class="text-muted mb-0">Manage all users in Michigan Explorer.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
    <input type="text" class="form-control global-search-input" placeholder="Search..." style="width: 220px;" />
    @if(AccessRights::accessRights('users.create'))
    <a href="{{ route('create-user') }}" class="btn btn-warning text-white">Add User</a>
    @endif
  </div>
</div>

@include('layouts.messages')

<div class="card">
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <th>User Name</th>
          <th>Email</th>
          <th>Contact No</th>
          <th>Job Title</th>
          <th>Role</th>
          <th>Profile</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="user-table-body">
        <tr>
          <td colspan="9" class="text-center">Loading users...</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
  $(document).ready(function (e) {
    var userListRoute = "{{ route('user-list') }}";
    var userEditRoute = "{{ route('edit-user', ':id') }}";

    var canViewUserList = @json(AccessRights::accessRights('users.list'));
    var canViewUserUpdate = @json(AccessRights::accessRights('users.update'));
    var canViewUserDelete = @json(AccessRights::accessRights('users.delete'));

    if (canViewUserList) {
        getUserData();
    } else {
        $('#user-table-body').html('<tr><td colspan="9" class="text-center">You do not have permission to view the user list.</td></tr>');
    }

    function getUserData() {
        $.ajax({
            url: userListRoute,
            method: 'POST',
            headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                var tbody = $('#user-table-body');
                tbody.empty();
                
                if (response.user && response.user.length > 0) {
                    $.each(response.user, function(index, data) {
                        var srNo = index + 1;
                        var name = '<span class="text-capitalize">' + (data.name || '') + '</span>';
                        var email = data.email_id || '';
                        var contact = '';
                        if (data.contact_no) {
                            contact = '<a class="text-secondary" target="_blank" href="https://api.whatsapp.com/send?phone=91' + data.contact_no + '">' + data.contact_no + '<i class="fab fa-whatsapp ms-1 text-success"></i></a>';
                        }
                        var jobTitle = data.job_title || '';
                        var role = data.role || '';
                        
                        // Fix profile url mapping
                        var imgUrl = data.profile_url ? "{{ asset('storage') }}" + data.profile_url : "{{ asset('assets/img/avatars/1.jpg') }}";
                        var profile = '<img src="' + imgUrl + '" onerror="this.onerror=null; this.src=\'{{ asset("assets/img/avatars/1.jpg") }}\';" alt="' + (data.name || '') + '" height="38" width="38" class="rounded-circle" style="object-fit: cover;" />';
                        
                        var checked = data.is_active == 1 ? 'checked' : '';
                        var status = '<label class="switch"><input type="checkbox" class="switch-input" data-id="' + data.id + '" data-status="' + data.is_active + '" ' + checked + '><span class="switch-toggle-slider"></span></label>';
                        
                        var editButton = '';
                        var deleteButton = '';
                        if (canViewUserUpdate) {
                            editButton = '<a href="' + userEditRoute.replace(':id', data.id) + '" class="btn btn-sm btn-primary edit-btn me-1" id="edituser"><i class="fa fa-edit"></i></a>';
                        }
                        if (canViewUserDelete) {
                            deleteButton = '<button type="button" value="' + data.id + '" class="btn btn-sm btn-danger delete-btn" id="deleteUser"><i class="fa fa-trash"></i></button>';
                        }
                        var action = '<div class="btn-group" role="group">' + editButton + deleteButton + '</div>';
                        
                        var row = '<tr>' +
                            '<td>' + srNo + '</td>' +
                            '<td><strong>' + name + '</strong></td>' +
                            '<td>' + email + '</td>' +
                            '<td>' + contact + '</td>' +
                            '<td>' + jobTitle + '</td>' +
                            '<td>' + role + '</td>' +
                            '<td>' + profile + '</td>' +
                            '<td>' + status + '</td>' +
                            '<td>' + action + '</td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                } else {
                    tbody.html('<tr><td colspan="9" class="text-center">No records found</td></tr>');
                }
            },
            error: function (xhr, error, thrown) {
                console.log('Error:', error);
                $('#user-table-body').html('<tr><td colspan="9" class="text-center text-danger">Failed to load data.</td></tr>');
            }
        });
    }

    //delete user
    $(document).on("click","#deleteUser",function(e){
        e.preventDefault();
        var user_id = $(this).val();
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: 'GET',
                url: "./delete_user/"+user_id,
                success: function(response) {
                    if(response){
                        getUserData();
                    }
                }
            });
        }
    });

    //status change
    $(document).on('change', '.switch-input', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var status = $(this).data('status');
        $.ajax({
            url: '{{url("admin/status_user")}}/' + id + '/' + status,
            type: 'GET',
            success: function (response) {
                getUserData();
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });

  })
</script>
@endsection
