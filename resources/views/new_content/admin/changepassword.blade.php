@extends('layouts/layoutMaster')

@section('title', 'Change Passward')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection



@section('content')

<!-- Change Password -->
<div class="card">
    <!-- <h5 class="card-header text-align-center">Change Password</h5> -->
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-center h-px-500">
        <form class="w-px-400 border rounded p-3 p-md-5" id="change_pass" action="{{ route('change.password') }}" method="post">
            <h3 class="mb-4">Change Password</h3>
            @csrf
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>	
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                <!-- <div class="mb-3">
                    <label class="form-label" for="oldpassword">Old Password</label>
                    <input type="password" id="oldpassword" name="oldpassword" class="form-control" placeholder="Enter Old Password:" value="{{old('oldpassword')}}" autofocus/>
					@if ($errors->has('oldpassword'))
						<span class="error text-danger">{{ $errors->first('oldpassword') }}</span>
					@endif
                </div> -->
                <div class="mb-2 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="oldpassword">Old Password</label>
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password" id="oldpassword" class="form-control" name="oldpassword" placeholder="Enter Old Password" aria-describedby="password" />
                        <span class="input-group-text cursor-pointer" id="toggleOldPassword"><i class="ti ti-eye-off"></i></span>
                        @if ($errors->has('oldpassword'))
						    <span class="error text-danger">{{ $errors->first('oldpassword') }}</span>
					    @endif
                    </div>
                </div>
                <div class="mb-2 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="newpassword">New Password</label>
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" name="newpassword" class="form-control" placeholder="Enter New Password" value="{{old('newpassword')}}"/>
                        <span class="input-group-text cursor-pointer" id="togglePassword"><i class="ti ti-eye-off"></i></span>
                        @if ($errors->has('newpassword'))
                            <span class="error text-danger">{{ $errors->first('newpassword') }}</span>
                        @endif
                    </div>
                </div>
                <div class="mb-2 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="confirmnewpassword">Confirm Password</label>
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password" id="cnfpassword" name="cnfpassword" class="form-control" placeholder="Enter Confirm Password" value="{{old('cnfpassword')}}"/>
                        <span class="input-group-text cursor-pointer" id="toggleCnfPassword"><i class="ti ti-eye-off"></i></span>
                        @if ($errors->has('cnfpassword'))
                            <span class="error text-danger">{{ $errors->first('cnfpassword') }}</span>
                        @endif
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" id="btnPass" class="btn btn-primary">Submit</button>
                </div>
      </form>
    </div>
  </div>
</div>
<!-- End Change Password -->


<!-- Offcanvas -->
@include('_partials/_offcanvas/offcanvas-send-invoice')
<!-- /Offcanvas -->
@endsection

@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        $('#change_pass').validate({
            rules: {
                oldpassword:'required',
                newpassword:'required',
                cnfpassword: {
                    required: true,
                    equalTo: '#change_pass input[name="newpassword"]',
                },
            },
            messages: {
                oldpassword: 'Please enter old password',
                newpassword: 'Please enter new password',
                cnfpassword: {
                    required: 'Please enter confirm password',
                    equalTo: 'Confirm password and new password do not match.',
                },
            },
            errorPlacement: function(error, element) {
                if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });

</script>
@endsection