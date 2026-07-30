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

<div class="card" id="ajax-table-container">
  @include('new_content.user._table')
</div>
@endsection

@section('page-script')
<script type="text/javascript">
  $(document).ready(function (e) {
    //delete user
    $(document).on("click",".delete-user",function(e){
        e.preventDefault();
        var user_id = $(this).data('id');
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: 'GET',
                url: "{{ url('admin/delete_user') }}/"+user_id,
                success: function(response) {
                    if(response){
                        window.location.reload();
                    }
                }
            });
        }
    });

    //status change
    $(document).on('change', '.active_status', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var status = $(this).prop('checked') ? 1 : 0;
        $.ajax({
            url: '{{url("admin/status_user")}}/' + id + '/' + status,
            type: 'GET',
            success: function (response) {
                window.location.reload();
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });

  });
</script>
@endsection
