@extends('layouts/layoutMaster')
@php
use App\Helpers\AccessRights;
@endphp

@section('title', 'User List')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/cards-advance.css')}}">
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css')}}">
@endsection

@section('page-style')
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection

@section('content')

<div class="card">
  @include('layouts.messages')
  <h5 class="card-header">User List</h5>
  <div class="d-flex justify-content-end me-md-4">
    @if(AccessRights::accessRights('users.create'))
    <a href="{{ route('create-user') }}" type="button" class="btn btn-primary text-white me-3"> Add User</a>
    @endif
  </div>
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-user table">
      <thead>
        <tr>
        <th>#</th>
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
      <tbody></tbody>
    </table>
  </div>
</div>
@endsection


@section('page-script')
<script type="text/javascript">
  $(document).ready(function (e) {
    //user list
    var userTable = $('.datatables-user').DataTable();
    var userListRoute = "{{ route('user-list') }}";
    var userEditRoute = "{{ route('edit-user', ':id') }}";

    var canViewUserList = @json(AccessRights::accessRights('users.list'));
    var canViewUserUpdate = @json(AccessRights::accessRights('users.update'));
    var canViewUserDelete = @json(AccessRights::accessRights('users.delete'));

    if (canViewUserList) {
        getUserData();
    } else {
        $('.datatables-user').html('<p class="text-center">You do not have permission to view the user list.</p>');
    }

    function getUserData(){
        $.ajax({
            url: userListRoute,
            method: 'POST',
            "headers": {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                userTable.clear().destroy();
                userTable = $('.datatables-user').DataTable({
                    data: response.user,
                    scrollX: true,
                    ordering: false,
                    dom: '<"row"' + '<"col-md-2"<"me-3"l>>' + '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' + '>t' + '<"row mx-2"' + '<"col-sm-12 col-md-6"i>' + '<"col-sm-12 col-md-6"p>' + '>',
                    displayLength: 10,
                    lengthMenu: [10, 25, 50, 75, 100],
                    columns: [
                        {
                            data: null,
                            name: 'id',
                            render: function(data, type, row, meta) {
                                var index = meta.row + 1;
                                return index;
                            }
                        },
                        { data: null,
                            render: function(data, type, row, meta) {
                                return '<span class="text-capitalize">' + data.name + '</span>';
                            }
                        },
                        { data: 'email_id'},
                        { data: null,
                            render: function (data, type, row, meta) {
                                return '<a class="text-secondary" target="blank" href="https://api.whatsapp.com/send?phone=91' + data.contact_no + '">' + data.contact_no + '<i class="auto-style-6 fab fa-whatsapp ms-1"></i></a>';
                            },
                        },
                        { data: 'job_title'},
                        { data: 'role'},
                        { data: null,
                            render: function(data, type, row, meta) {
                                if (data.profile_url && data.profile_url !== "") {
                                    return '<img src="{{ asset("storage/app/public/") }}' + data.profile_url + '" alt="' + data.name + '" height="50" width="50" class="img-thumbnail" />';
                                } else {
                                    return '<img src="{{ asset("assets/img/image_not_found.png") }}" height="50" width="50" class="img-thumbnail" />';
                                }
                            }
                        },
                        {
                            render: function (data, type, row, meta) {
                                var $status = row['is_active'];
                                var id = row['id'];
                                var checked = $status == 1 ? 'checked' : '';
                                return '<label class="switch"><input type="checkbox" class="switch-input" data-id="' + id + '" data-status="' + $status + '" data-toggle="tooltip" title="Status" ' + checked + '><span class="switch-toggle-slider"></span></label>';
                            },
                        },
                        {
                          data: null,
                          render: function (data, type, row, meta) {
                                var editButton = '';
                                var deleteButton = '';
                                
                                if (canViewUserUpdate) {
                                    editButton = '<a href="' + userEditRoute.replace(':id', data.id) + '" type="button" class="btn btn-sm btn-primary edit-btn" id="edituser"><i class="fa fa-edit"></i></a>';
                                }
                                if (canViewUserDelete) {
                                    deleteButton = '<button type="button" value="' + data.id + '" class="btn btn-sm btn-danger delete-btn" id="deleteUser"><i class="fa fa-trash"></i></button>';
                                }
                                return '<div class="btn-group" role="group">' + editButton + deleteButton + '</div>';
                          }
                        },
                    ],
                    buttons: [
                    {
                        extend: 'collection',
                        className: 'btn btn-label-primary dropdown-toggle me-2',
                        text: '<i class="ti ti-file-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                        buttons: [
                            {
                                extend: 'print',
                                text: '<i class="ti ti-printer me-1" ></i>Print',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [0,1,2,3,4,5],
                                }
                            },
                            {
                                extend: 'csv',
                                text: '<i class="ti ti-file-text me-1" ></i>Csv',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [0,1,2,3,4,5],
                                }
                            },
                            {
                                extend: 'excel',
                                text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [0,1,2,3,4,5],
                                }
                            },
                            {
                                extend: 'pdf',
                                text: '<i class="ti ti-file-description me-1"></i>Pdf',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [0,1,2,3,4,5],
                                }
                            }
                        ]
                    }
                    ],
                });
            },
            error: function (xhr, error, thrown) {
                console.log('Error:', error);
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
