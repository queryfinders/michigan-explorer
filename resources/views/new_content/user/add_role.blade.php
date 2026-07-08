@extends('layouts/layoutMaster')

@section('title', 'Create Role')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/cards-advance.css')}}">
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
@endsection

@section('page-style')
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
@endsection

@section('content')

<div class="card">
    <h5 class="card-header">{{ isset($role) ? "Edit Role" : "Create Role" }}</h5>
    <div class="card-body">
        <form id="role" class="mb-3 role" action="{{ isset($role) && isset($role->id) ? route('store-role', $role->id) : route('store-role') }}" data-mode="{{ isset($role) && isset($role->id) ? 'update' : 'insert' }}" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="row">
                <div class="mb-3 col-4">
                    <label class="form-label" for="role">Role Name</label>
                    <input type="text" name="role" id="role" class="form-control" placeholder="Enter Role name" value="{{ isset($role) && isset($role->role) ? $role->role : old('role') }}"/>
                </div>
                <div class="mb-3 col-12">
                    <label for="name" class="form-label form-label">Permissions</label>
                    <div class="table-responsive">
                        <table class="table table-flush-spacing">
                            <tbody>
                                <tr>
                                    <td class="text-nowrap fw-semibold">Administrator Access <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i></td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll" />
                                            <label class="form-check-label" for="selectAll">Select All</label>
                                        </div>
                                    </td>
                                </tr>
                                @foreach ($rights as $key => $item)
                                <tr>
                                    <td class="text-nowrap fw-semibold">{{ $item['name'] }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <div class="form-check me-3 me-lg-5">
                                                <input class="form-check-input" name="rights_permission[]" type="checkbox" id="list{$key}" value="{{ $item['description'].'.list' }}" @if(isset($role) && is_array($selectedRights) && in_array($item['description'].'.list', $selectedRights)) checked @endif/>
                                                <label class="form-check-label" for="List">List</label>
                                            </div>
                                            <div class="form-check me-3 me-lg-5">
                                                <input class="form-check-input" name="rights_permission[]" type="checkbox" id="create{$key}" value="{{ $item['description'].'.create' }}" @if(isset($role) && is_array($selectedRights) && in_array($item['description'].'.create', $selectedRights)) checked @endif/>
                                                <label class="form-check-label" for="Create">Create</label>
                                            </div>
                                            <div class="form-check me-3 me-lg-5">
                                                <input class="form-check-input" name="rights_permission[]" type="checkbox" id="update{$key}" value="{{ $item['description'].'.update' }}" @if(isset($role) && is_array($selectedRights) && in_array($item['description'].'.update', $selectedRights)) checked @endif/>
                                                <label class="form-check-label" for="Update">Update</label>
                                            </div>
                                            <div class="form-check me-3 me-lg-5">
                                                <input class="form-check-input" name="rights_permission[]" type="checkbox" id="delete{$key}" value="{{ $item['description'].'.delete' }}" @if(isset($role) && is_array($selectedRights) && in_array($item['description'].'.delete', $selectedRights)) checked @endif/>
                                                <label class="form-check-label" for="Delete">Delete</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-end">
                    <button class="btn btn-primary me-2" type="submit">{{ isset($role) ? "Edit Role" : "Add Role" }}</button>
                    <a href="{{ route('role') }}" class="btn btn-info">Back</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


@section('page-script')

<script>
    $(document).ready(function() {
        var formMode = $("#role").data('mode');
        
        $.validator.addMethod('imageData', function(value, element) {
            if (element.files.length === 0) {
                return true;
            }
        var extension = value.split('.').pop().toLowerCase();
        console.log(extension);
        var allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        return $.inArray(extension, allowedExtensions) !== -1;
        }, 'Please choose a valid image file.');

        $(".role").validate({
            rules: {
                role: 'required',
            },
            messages: {
                role: 'Please enter role name',
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    })
</script>
@endsection
