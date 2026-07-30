@extends('layouts/layoutMaster')

@section('title', 'Create User')

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

<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user-add') }}">Users</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ isset($user) ? "Edit User" : "Create User" }}</li>
  </ol>
</nav>

<div class="card">
    <h5 class="card-header">{{ isset($user) ? "Edit User" : "Create User" }}</h5>
    <div class="card-body">
        <form id="user" class="mb-3 user" action="{{ isset($user) && isset($user->id) ? route('store-user', $user->id) : route('store-user') }}" data-mode="{{ isset($user) && isset($user->id) ? 'update' : 'insert' }}" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="row">
                <div class="mb-3 col-4">
                    <label class="form-label" for="name">User Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" value="{{ isset($user) && isset($user->name) ? $user->name : old('name') }}"/>
                </div>
                <div class="mb-3 col-4">
                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                    <input type="text" name="email_id" id="email_id" class="form-control" placeholder="Enter Email Addess" value="{{ isset($user) && isset($user->email_id) ? $user->email_id : old('email_id') }}"/>
                    <span id="email_error" class="auto-style-5">
                </div>
                <div class="mb-3 col-4">
                    <label class="form-label" for="contact_no">Contact No. <span class="text-danger">*</span></label>
                    <input type="text" name="contact_no" id="contact_no" class="form-control" placeholder="Enter Contact No." value="{{ isset($user) && isset($user->contact_no) ? $user->contact_no : old('contact_no') }}"/>
                </div>
                <div class="mb-3 col-4">
                    <label class="form-label" for="job_title">Job Title <span class="text-danger">*</span></label>
                    <input type="text" name="job_title" id="job_title" class="form-control" placeholder="Enter Job title" value="{{ isset($user) && isset($user->job_title) ? $user->job_title : old('job_title') }}"/>
                </div>
                <div class="mb-3 col-4">
                    <label for="profile_url" class="form-label">Profile</label>
                    <input type="file" name="profile_url" id="profile_url" class="form-control"/>
                    <small>Note: Image ratio (300px * 260px)</small>
                    @if(isset($user) && $user->profile_url)
                        <img src="{{ asset('storage/app/public/' . $user->profile_url) }}" id="profile_url_preview" alt="{{ $user->profile_url }}" class="mt-2 pics" height="50" width="70">
                    @endif
                </div>
                @if(!(isset($user) && isset($user->password)))
                <div class="mb-3 col-4">
                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" value=""/>
                </div>
                @endif
                <div class="mb-3 col-4">
                    <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role_id" id="role_id" class="select2 form-select">
                        <option value="">Select role</option>
                            @if(isset($role) && !empty($role) && count($role) > 0)
                                @foreach($role as $item)
                                    <option value="{{ $item->id }}" {{ old('role_id') == $item->id || (isset($user) && $user->role_id == $item->id) ? 'selected' : '' }}>{{ $item->role }}</option>
                                @endforeach
                            @endif
                    </select>
                </div>
                <div class="mb-3 d-flex justify-content-end">
                    <button class="btn btn-primary me-2" type="submit">{{ isset($user) ? "Edit User" : "Add User" }}</button>
                    <!-- <a href="{{ route('user-add') }}" class="btn btn-info">Back</a> -->
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


@section('page-script')

<script>
    $(document).ready(function() {
        var formMode = $("#user").data('mode');
        if (formMode === 'update') {
            $("#user").data('editing', true);
        }
        $("#email_id").blur(function(){
            var email_id = $("#email_id").val();
            if (formMode === 'insert') {
                $.ajax({
                    url: "{{ route('user.checkemail') }}",
                    type: 'POST',
                    data: { email_id: email_id,_token: '{{ csrf_token() }}' },
                    success: function (data) {
                        if (data.exists && email_id !== "") {
                            $("#email_error").text("Email already exists.");
                            $("#email_id").data('valid', false);
                        } else {
                            $("#email_error").text("");
                            $("#email_id").data('valid', true);
                        }
                    }
                });
            }
        });
        
        $.validator.addMethod('imageData', function(value, element) {
            if (element.files.length === 0) {
                return true;
            }
        var extension = value.split('.').pop().toLowerCase();
        console.log(extension);
        var allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        return $.inArray(extension, allowedExtensions) !== -1;
        }, 'Please choose a valid image file.');

        $(".user").validate({
            rules: {
                name: 'required',
                password: {
                    required: function() {
                        return !$('#password').closest('.mb-3').hasClass('d-none'); // Check if password field is visible
                    }
                },
                email_id: {
                    required: true,
                    email: true,
                },
                contact_no: {
                    required:true,
                    number: true,
                    minlength: 10,
                    maxlength: 10  
                },
                job_title: 'required',
                profile_url: {
                    required: function(element) {
                        return formMode === 'insert'; 
                    },
                    imageData: true
                },
                role_id: 'required',
            },
            messages: {
                name: 'Please enter user name',
                password: 'Please enter your password',
                email_id: {
                    required: 'Please enter email address',
                    email: 'Please enter a valid email address',
                },
                contact_no: {
                    required: 'Please Enter mobile number',
                    number: 'Enter only numeric value',
                    minlength: 'Enter only 10 digits number',
                    maxlength: 'Enter only 10 digits number'    
                },
                job_title: 'please enter job title',
                profile_url: {
                    required: "Please select an image file .",
                    imageData: "Please choose a JPG, JPEG, PNG, SVG, WEBP file."
                },
                role_id: 'Please select role',
            },
            errorPlacement: function(error, element) {
                if(element.parent('.position-relative').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
                if (formMode === 'insert') {
                    if ($("#email_id").data('valid')) {
                        form.submit();
                    }
                }else{
                    form.submit();
                }
            }
        });
    })
</script>
@endsection
