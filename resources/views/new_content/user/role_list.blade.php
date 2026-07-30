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

<div class="card" id="ajax-table-container">
  @include('new_content.user._role_table')
</div>
@endsection

@section('page-script')
<script type="text/javascript">
  $(document).ready(function (e) {
    //delete role
    $(document).on("click",".deleteRole",function(e){
        e.preventDefault();
        var role_id = $(this).data('id');
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: 'GET',
                url: "{{ url('admin/delete_role') }}/"+role_id,
                success: function(response) {
                    if(response){
                        window.location.reload();
                    }
                }
            });
        }
    });

  });
</script>
@endsection
