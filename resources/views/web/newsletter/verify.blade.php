@extends('web.layout.app_layout')

@section('title', 'Newsletter Verification')

@section('webLayoutContent')
<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card border-0 shadow-lg p-4 rounded-4">
                <div class="card-body">
                    @if($status === 'success')
                        <div class="mb-4 text-success">
                            <i class="fa-solid fa-circle-check fa-4x"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Subscription Confirmed!</h2>
                        <p class="text-muted mb-4">{{ $message }}</p>
                        <p class="text-muted">You are now officially a member of the Explorer Club. Welcome aboard!</p>
                    @elseif($status === 'already_verified')
                        <div class="mb-4 text-info">
                            <i class="fa-solid fa-circle-info fa-4x"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Already Confirmed</h2>
                        <p class="text-muted mb-4">{{ $message }}</p>
                        <p class="text-muted">You are already subscribed to the Michigan Explorer newsletter.</p>
                    @else
                        <div class="mb-4 text-danger">
                            <i class="fa-solid fa-triangle-exclamation fa-4x"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Verification Failed</h2>
                        <p class="text-muted mb-4">{{ $message }}</p>
                        <p class="text-muted">Please try subscribing again on our home page.</p>
                    @endif
                    
                    <div class="mt-4">
                        <a href="{{ url('/') }}" class="btn btn-primary px-4 py-2 rounded-pill"><i class="fa-solid fa-house me-1"></i> Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
