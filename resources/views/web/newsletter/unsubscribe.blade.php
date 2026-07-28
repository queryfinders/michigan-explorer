@extends('web.layout.app_layout')

@section('title', 'Unsubscribe Newsletter')

@section('webLayoutContent')
<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card border-0 shadow-lg p-4 rounded-4">
                <div class="card-body">
                    @if($success)
                        <div class="mb-4 text-warning">
                            <i class="fa-solid fa-bell-slash fa-4x"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Unsubscribed</h2>
                        <p class="text-muted mb-4">{{ $message }}</p>
                        <p class="text-muted">We're sorry to see you go. You can re-subscribe at any time from our homepage.</p>
                    @else
                        <div class="mb-4 text-danger">
                            <i class="fa-solid fa-triangle-exclamation fa-4x"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Failed to Unsubscribe</h2>
                        <p class="text-muted mb-4">{{ $message }}</p>
                        <p class="text-muted">We could not process your unsubscribe request. Please contact support or try again later.</p>
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
