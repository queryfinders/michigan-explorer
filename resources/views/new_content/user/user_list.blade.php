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
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
        <i class="bx bx-filter-alt"></i> Filters
      </button>
      <button type="submit" form="filterForm" name="export" value="csv" class="btn btn-success">
        <i class="bx bx-export"></i> Export CSV
      </button>
      @if(AccessRights::accessRights('users.create'))
      <a href="{{ route('create-user') }}" class="btn btn-warning text-white">Add User</a>
      @endif
    </div>
  </div>
  
  <div class="collapse mb-4 {{ request()->anyFilled(['search', 'role', 'status', 'date_from', 'date_to']) ? 'show' : '' }}" id="filterCollapse">
    <div class="card card-body">
      <form id="filterForm" method="GET" action="{{ route('user-add') }}">
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Search (Name, Email, Phone)</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search...">
          </div>
          <div class="col-md-3">
            <label class="form-label">Role</label>
            @php
              $roleOptions = ['' => 'All Roles'];
              if(isset($roles)) {
                  foreach($roles as $r) {
                      $roleOptions[$r->id] = $r->role;
                  }
              }
            @endphp
            <x-filter-dropdown name="role" :options="$roleOptions" :selected="request('role')" placeholder="All Roles" />
          </div>
          <div class="col-md-2">
            <label class="form-label">Status</label>
            @php
              $statusOptions = ['' => 'All Status', '1' => 'Active', '0' => 'Inactive'];
            @endphp
            <x-filter-dropdown name="status" :options="$statusOptions" :selected="request('status')" placeholder="All Status" />
          </div>
          <div class="col-md-2">
            <label class="form-label">Registration From</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
          </div>
          <div class="col-md-2">
            <label class="form-label">Registration To</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
          </div>
        </div>
        <div class="mt-3 d-flex justify-content-end gap-2">
          <a href="{{ route('user-add') }}" class="btn btn-secondary">Reset</a>
          <button type="submit" class="btn btn-primary">Apply Filters</button>
        </div>
      </form>
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
