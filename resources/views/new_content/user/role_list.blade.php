@extends('layouts/layoutMaster')
@php
use App\Helpers\AccessRights;
@endphp

@section('title', 'Role List')

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/cards-advance.css')}}">
@endsection

@section('content')
<nav aria-label="breadcrumb" class="mb-1">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Roles</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="mb-1 fw-bold">Role List</h3>
    <p class="text-muted mb-0">Manage all roles in Michigan Explorer.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
    <input type="text" class="form-control global-search-input" id="roleSearch" placeholder="Search..." style="width: 220px;" />
    <a href="{{ route('create-role') }}" class="btn btn-warning text-white">Add Role</a>
  </div>
</div>

@include('layouts.messages')

<div class="card">
  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <th>ROLE NAME</th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody id="role-table-body">
        <tr>
          <td colspan="3" class="text-center">Loading roles...</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
  $(document).ready(function (e) {
    var roleListRoute = "{{ route('role-list') }}";
    var roleEditRoute = "{{ route('edit-role', ':id') }}";
    var allRoles = [];

    getRoleData();

    function getRoleData(){
        $.ajax({
            url: roleListRoute,
            method: 'POST',
            "headers": {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if(response.role) {
                    allRoles = response.role;
                    renderRoles(allRoles);
                } else {
                    $('#role-table-body').html('<tr><td colspan="3" class="text-center">No records found</td></tr>');
                }
            },
            error: function (xhr, error, thrown) {
                $('#role-table-body').html('<tr><td colspan="3" class="text-center text-danger">Failed to load data.</td></tr>');
            }
        });
    }

    function renderRoles(roles) {
        var tbody = $('#role-table-body');
        tbody.empty();
        
        if (roles.length > 0) {
            $.each(roles, function(index, data) {
                var srNo = index + 1;
                var roleName = data.role || '';
                var action = '<div class="btn-group" role="group"><a href="' + roleEditRoute.replace(':id', data.id) + '" type="button" class="btn btn-sm btn-primary edit-btn"><i class="fa fa-edit"></i></a><button type="button" value="' + data.id + '" class="btn btn-sm btn-danger delete-btn" id="deleteRole"><i class="fa fa-trash"></i></button></div>';
                
                var row = '<tr>' +
                    '<td>' + srNo + '</td>' +
                    '<td><strong>' + roleName + '</strong></td>' +
                    '<td>' + action + '</td>' +
                    '</tr>';
                tbody.append(row);
            });
        } else {
            tbody.html('<tr><td colspan="3" class="text-center">No records found</td></tr>');
        }
    }

    // Client-side search since we removed DataTables
    $('#roleSearch').on('keyup', function() {
        var val = $(this).val().toLowerCase();
        var filtered = allRoles.filter(function(r) {
            return (r.role && r.role.toLowerCase().indexOf(val) > -1);
        });
        renderRoles(filtered);
    });

    //delete role
    $(document).on("click","#deleteRole",function(e){
        e.preventDefault();
        var role_id = $(this).val();
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: 'GET',
                url: "./delete_role/"+role_id,
                success: function(response) {
                    if(response){
                        getRoleData();
                    }
                }
            });
        }
    });
  });
</script>
@endsection
