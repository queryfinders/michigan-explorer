@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Reset Password')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
      <!-- Reset Password -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-4 mt-2">
            <a href="{{url('/')}}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo" id="app-brand-logo"><img src="{!! asset('assets/img/QF-logo-main.webp') !!}" class="logoVertical"  alt=""></span>
              <!-- <span class="app-brand-text demo text-body fw-bold ms-1">{{ config('variables.templateName') }}</span> -->
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1 pt-2">Reset Password 🔒</h4>
          <!-- <p class="mb-4">for <span class="fw-bold">john.doe@email.com</span></p> -->
          <form id="resetPass" action="{{ route('resetpass') }}" method="POST" autocomplete="off">
          @csrf
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>	
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
              @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <strong>{{ $message }}</strong>
                </div>
              @endif
            <input type="hidden" name="token" value="{{ $token }}"/>
            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="email">Email ID</label>
              <div class="input-group input-group-merge">
                <input type="text" name="email_id" id="email" class="form-control" placeholder="Enter email" value="{{ $email_id ?? old('email_id')}}" autofocus/>
              </div>
            </div>
            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" name="password" class="form-control" placeholder="Enter Password" value="{{old('password')}}" autocomplete="off"/>
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
            </div>
            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="password">Confirm Password</label>
              <div class="input-group input-group-merge">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Enter Confirm Password" value="{{old('password_confirmation')}}" autocomplete="off"/>
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
            </div>
            <button type="submit" class="btn btn-primary d-grid w-100 mb-3">
              Set new password
            </button>
            <div class="text-center">
              <a href="{{url('admin')}}"><i class="ti ti-chevron-left scaleX-n1-rtl"></i>Back to login</a>
            </div>
          </form>
        </div>
      </div>
      <!-- /Reset Password -->
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        $('#resetpass').validate({
            rules: {
                password:'required',
                password_confirmation: {
                    required: true,
                    equalTo: '#resetpass input[name="password"]',
                },
            },
            messages: {
                password: 'Please enter new password',
                password_confirmation: {
                    required: 'Please enter confirm password',
                    equalTo: 'Confirm password and password do not match.',
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });

</script>
@endsection