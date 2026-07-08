@extends('layouts/layoutMaster')

@section('title', 'Role List')

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
  <h5 class="card-header">Role List</h5>
  <div class="d-flex justify-content-end me-md-4">
    <a href="{{ route('create-role') }}" type="button" class="btn btn-primary text-white me-3"> Add Role</a>
  </div>
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-role table">
      <thead>
        <tr>
        <th>#</th>
        <th>Role Name</th>
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
    //role list
    var roleTable = $('.datatables-role').DataTable();
    var roleListRoute = "{{ route('role-list') }}";
    var roleEditRoute = "{{ route('edit-role', ':id') }}";

    getRoleData();
    function getRoleData(){
        $.ajax({
            url: roleListRoute,
            method: 'POST',
            "headers": {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                roleTable.clear().destroy();
                roleTable = $('.datatables-role').DataTable({
                    data: response.role,
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
                        { data: 'role'},
                        {
                          data: null,
                          render: function (data, type, row, meta) {
                              // Render the action buttons column
                              return '<div class="btn-group" role="group"><a href="' + roleEditRoute.replace(':id', data.id) + '" type="button" class="btn btn-sm btn-primary edit-btn" id="editrole"><i class="fa fa-edit"></i></a><button type="button" value="' + data.id + '" class="btn btn-sm btn-danger delete-btn" id="deleteRole"><i class="fa fa-trash"></i></button></div>';
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
                                    columns: [0],
                                }
                            },
                            {
                                extend: 'csv',
                                text: '<i class="ti ti-file-text me-1" ></i>Csv',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [0],
                                }
                            },
                            {
                                extend: 'excel',
                                text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [0],
                                }
                            },
                            {
                                extend: 'pdf',
                                text: '<i class="ti ti-file-description me-1"></i>Pdf',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [0],
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

  })
</script>
@endsection
