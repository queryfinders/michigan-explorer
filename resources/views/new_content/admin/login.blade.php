@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Login')

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

<script>
 
</script>
@endsection

@section('page-script')
<script>
    $(document).ready(function () {
        $('#loginForm').validate({
            rules: {
              email_id: {
                required:true,
                email: true,
              },
              password:'required',
            },
            messages: {
              email_id: {
                required: 'Please enter email address',
                email: 'Please enter only valid email',
              },
              password: 'Please enter password',
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

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
      <!-- Login -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mt-2">
            <a href="{{url('/')}}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo" id="app-brand-logo"><img src="{!! asset('assets/img/QF-logo-main.webp') !!}" class="logoVertical"  alt=""></span>
              <!-- <span class="app-brand-text demo text-body fw-bold ms-1">{{config('variables.templateName')}}</span> -->
            </a>
          </div>
          <!-- /Logo -->
          
          <p class="mb-4 pt-2">Please sign-in to your account</p>

          <form id="loginForm" class="mb-3" action="{{ route('admin.login')}}" method="POST" autocomplete="off">
          @csrf

          @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show">
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>	
                <strong>{{ $message }}</strong>
            </div>
          @endif
          @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <strong>{{ $message }}</strong>
            </div>
          @endif
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email_id" placeholder="Enter your email " value="{{ old('email_id') }}" autofocus>
                @if ($errors->has('email'))
                  <span class="error text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div class="mb-1 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
              </div>
              <div class="input-group input-group-merge login">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer" id="togglePassword"><i class="ti ti-eye-off"></i></span>
                  @if ($errors->has('password'))
                    <span class="error text-danger">{{ $errors->first('password') }}</span>
                  @endif
              </div>
            </div>
            <div class="mb-3">
              <a href="{{url('admin/forgot_password')}}">
                <small>Forgot Password?</small>
              </a>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection