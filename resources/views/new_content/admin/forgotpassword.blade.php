@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Forgot Password')

@section('vendor-style')
<!-- Remove FormValidation CSS as we're using jQuery Validate instead -->
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('vendor-script')
<!-- Remove FormValidation scripts as they conflict with jQuery Validate -->
@endsection

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
        <!-- Forgot Password -->
        <div class="card">
            <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4 mt-2">
                <a href="{{url('/')}}" class="app-brand-link gap-2">
                <span class="app-brand-logo demo" id="app-brand-logo"><img src="{!! asset('assets/img/QF-logo-main.webp') !!}" class="logoVertical"  alt=""></span>
                <!-- <span class="app-brand-text demo text-body fw-bold">{{ config('variables.templateName') }}</span> -->
                </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-1 pt-2">Forgot Password? 🔒</h4>
            <p class="mb-4">Enter your email and we'll send you a link to reset your password</p>
            <form id="forgot_pass" class="mb-3" action="{{route('forgotpass')}}" method="POST">
                @csrf
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email_id" placeholder="Enter your email" value="{{ old('email_id') }}" autofocus>
                    @if ($errors->has('email_id'))
						<span class="error text-danger">{{ $errors->first('email_id') }}</span>
					@endif
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100">Send Reset Link</button>
            </form>
            <div class="text-center">
                <a href="{{url('admin')}}" class="d-flex align-items-center justify-content-center">
                <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                Back to login
                </a>
            </div>
            </div>
        </div>
        <!-- /Forgot Password -->
        </div>
    </div>
</div>
@endsection

@section('page-script')
<!-- Remove the conflicting pages-auth.js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        $('#forgot_pass').validate({
            rules: {
                email_id: {
                    required: true,
                    email: true,
                },
            },
            messages: {
                email_id: {
                    required: 'Please enter email address',
                    email: 'Please enter a valid email address',
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@endsection